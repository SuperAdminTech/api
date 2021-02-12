<?php


namespace App\Utils;


use App\Entity\Config;
use App\Entity\Message;
use App\Entity\Permission;
use App\Entity\User;
use App\Exception\InvalidDataException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Exception\RfcComplianceException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;

class EmailUtils
{

    /** @var Environment */
    private $twig;

    /**
     * EmailUtils constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param User $user
     * @param string $emailTemplateName
     * @param string $subject
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws InvalidDataException
     */
    public function sendEmailTemplate(User $user, $emailTemplateName = 'sign_up', $subject = 'Welcome to {{ application.name }}') {
        $application = $user->permissions[0]->account->application;
        $config = $application->config;

        $templateVars = [
            'user' => $user,
            'application' => $application
        ];
        $text = $this->twig->render("email/{$emailTemplateName}.txt.twig", $templateVars);
        $html = $this->twig->render("email/{$emailTemplateName}.html.twig", $templateVars);
        try {
            $email = new Email();
            $from = $config->mailer_from?? Config::DEFAULT_MAILER_FROM;
            $stringTwig = new Environment(new ArrayLoader(['subject' => $subject]));
            $email->from(new Address($from, $application->name))
                ->to($user->getUsername())
                ->subject($stringTwig->render('subject', $templateVars))
                ->text($text)
                ->html($html);
            $this->sendEmail($user, $email);
        } catch (RfcComplianceException $e) {
            throw new InvalidDataException("Mailing error: " . $e->getMessage());
        }
    }

    /**
     * @param User $to
     * @param Email $email
     * @throws TransportExceptionInterface
     */
    public function sendEmail(User $to, Email $email) {
        $application = $to->permissions[0]->account->application;
        $config = $application->config;
        $transport = Transport::fromDsn($config->mailer_dsn);
        $mailer = new Mailer($transport);
        $mailer->send($email);
    }


    /**
     * @param Message $message
     * @throws TransportExceptionInterface
     * @throws InvalidDataException
     */
    public function sendMessage(Message $message) {

        foreach ($message->account->permissions as $permission) {
            if (in_array(Permission::ACCOUNT_MANAGER, $permission->grants)) {
                $user = $permission->user;
                $application = $user->permissions[0]->account->application;
                $config = $application->config;

                $vars = [
                    'user' => $user,
                    'application' => $application
                ];

                $template = new Environment(new ArrayLoader([
                    'subject' => $message->subject,
                    'body' => $message->body,
                    'body_html' => $message->body_html
                ]));

                try {
                    $email = new Email();
                    $from = $config->mailer_from?? Config::DEFAULT_MAILER_FROM;
                    $email->from(new Address($from, $application->name))
                        ->to($user->getUsername())
                        ->subject($template->render('subject', $vars))
                        ->text($template->render('body', $vars));
                    if ($message->body_html)
                        $email->html($template->render('body_html', $vars));
                    $this->sendEmail($user, $email);
                } catch (LoaderError | RuntimeError | SyntaxError $e) {
                    throw new InvalidDataException("Invalid e-mail template data: {$e->getMessage()}");
                }
            }
        }
    }

}