<?php


namespace App\EventSubscriber\Doctrine;


use App\Entity\Message;
use App\Entity\User;
use App\Exception\InvalidDataException;
use App\Utils\EmailUtils;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws InvalidDataException
     */
    public function prePersist(LifecycleEventArgs $args){
        $message = $args->getEntity();

        if ($message instanceof Message && $message->channel == Message::CHANNEL_EMAIL){
            try {
                $this->mailer->sendMessage($message);
                $message->status = Message::STATUS_SENT;
            } catch (TransportExceptionInterface $e) {
                $message->status = Message::STATUS_FAILED;
            }
        }
    }
}