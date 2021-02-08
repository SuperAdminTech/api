<?php


namespace App\EventSubscriber\Doctrine;


use App\Entity\Message;
use App\Entity\User;
use App\Utils\EmailUtils;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class MessageSubscriber
 * @package App\EventSubscriber\Doctrine
 */
class MessageSubscriber implements EventSubscriber
{
    /** @var EmailUtils */
    private $mailer;

    /**
     * MessageSubscriber constructor.
     * @param EmailUtils $mailer
     */
    public function __construct(EmailUtils $mailer)
    {
        $this->mailer = $mailer;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args){
        $message = $args->getEntity();

        if ($message instanceof Message && $message->channel == Message::CHANNEL_EMAIL){
            $this->mailer->sendMessage($message);
        }
    }
}