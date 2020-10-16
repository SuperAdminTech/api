<?php


namespace App\EventSubscriber\Doctrine;


use App\Entity\Config;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ramsey\Uuid\Uuid;
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

/**
 * Class UserSignUpSubscriber
 * @package App\EventSubscriber\Doctrine
 */
class UserSignUpSubscriber implements EventSubscriber {

    /** @var Environment */
    private $twig;

    /**
     * UserSignUpSubscriber constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array {
        return [
            Events::prePersist,
            Events::postPersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args){
        $user = $args->getEntity();
        if ($user instanceof User && $user->email_verification_code == null){
            $user->email_verification_code = Uuid::uuid4()->toString();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function postPersist(LifecycleEventArgs $args){
        $user = $args->getEntity();
        if ($user instanceof User && !$user->email_validated){
            $application = $user->permissions[0]->account->application;
            $config = $application->config;
            $transport = Transport::fromDsn($config->mailer_dsn);
            $mailer = new Mailer($transport);
            $text = $this->twig->render(
                'email/sign_up.txt.twig',
                [
                    'user' => $user,
                    'application' => $application
                ]
            );
            $html = $this->twig->render(
                'email/sign_up.html.twig',
                [
                    'user' => $user,
                    'application' => $application
                ]
            );
            $email = new Email();
            try {
                $from = $config->mailer_from?? Config::DEFAULT_MAILER_FROM;
                $email->from(new Address($from, $application->name))
                    ->to($user->getUsername())
                    ->subject("Welcome to {$application->name}")
                    ->text($text)
                    ->html($html);
                $mailer->send($email);
            } catch (RfcComplianceException $ignored) { }

        }
    }

}