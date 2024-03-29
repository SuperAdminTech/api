<?php


namespace App\EventSubscriber\Doctrine;


use App\Entity\Account;
use App\Entity\Permission;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AccountSubscriber
 * @package App\EventSubscriber\Doctrine
 */
class AccountSubscriber implements EventSubscriber
{
    /**
     * @var
     */
    private $tokenStorage;

    /**
     * AccountSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate
        ];
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args) {
        $account = $args->getObject();

        if ($account instanceof Account) {
            if($args->hasChangedField('enabled')) {
                //check is is trying to disable its own account
                $em = $args->getObjectManager();
                //if no token is because is executed from command or migrations, not logged user, we dont need this to check user
                if($this->tokenStorage->getToken()){
                    $user = $this->tokenStorage->getToken()->getUser();
                    $permision = $em->getRepository(Permission::class)->findOneBy(['account' => $account->id, 'user' => $user->id]);
                    if($permision) throw new HttpException(403, 'You can not disable your own account');
                }
            }

            if($args->hasChangedField('application')){
                //change application for all users inside account
                $permissions = $account->permissions;
                foreach ($permissions as $permission){
                    $user = $permission->user;
                    $app = $args->getNewValue('application');
                    $user->application = $app;
                }
            }
        }
    }
}