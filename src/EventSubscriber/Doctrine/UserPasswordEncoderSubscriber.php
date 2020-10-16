<?php


namespace App\EventSubscriber\Doctrine;


use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserPasswordEncoderSubscriber
 * @package App\EventSubscriber\Doctrine
 */
class UserPasswordEncoderSubscriber implements EventSubscriber
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserPasswordEncoderSubscriber constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::prePersist
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args){
        $user = $args->getEntity();
        if($user instanceof User){
            $this->encodePassword($user);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args){
        $user = $args->getEntity();
        if($user instanceof User){
            if($user->plain_password){
                $user = $this->encodePassword($user);
                $em = $args->getEntityManager();
                $meta = $args->getEntityManager()->getClassMetadata(User::class);
                $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $user);
            }
        }
    }

    /**
     * @param User $user
     * @return User
     */
    private function encodePassword(User $user): User {
        $encoded = $this->encoder->encodePassword($user, $user->plain_password);
        $user->setPassword($encoded);
        $user->eraseCredentials();
        return $user;
    }
}