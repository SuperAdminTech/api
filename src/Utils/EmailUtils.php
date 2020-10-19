<?php


namespace App\Utils;


use App\Entity\Config;
use App\Entity\User;
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
     * @param string $subjectTemplate
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendEmail(User $user, $emailTemplateName = 'sign_up', $subject = 'Welcome to {{ application.name }}') {
        $application = $user->permissions[0]->account->application;
        $config = $application->config;
        $transport = Transport::fromDsn($config->mailer_dsn);
        $mailer = new Mailer($transport);
        $templateVars = [
            'user' => $user,
            'application' => $application
        ];
        $text = $this->twig->render("email/{$emailTemplateName}.txt.twig", $templateVars);
        $html = $this->twig->render("email/{$emailTemplateName}.html.twig", $templateVars);
        $email = new Email();
        try {
            $from = $config->mailer_from?? Config::DEFAULT_MAILER_FROM;
            $stringTwig = new Environment(new ArrayLoader(['subject' => $subject]));
            $email->from(new Address($from, $application->name))
                ->to($user->getUsername())
                ->subject($stringTwig->render('subject', $templateVars))
                ->text($text)
                ->html($html);
            $mailer->send($email);
        } catch (RfcComplianceException $ignored) { }
    }


}