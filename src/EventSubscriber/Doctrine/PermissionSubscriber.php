<?php

namespace App\EventSubscriber\Doctrine;

use App\Entity\Permission;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class PermissionSubscriber implements EventSubscriber
{


    public function getSubscribedEvents()
    {
        return [
            Events::postPersist
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args){

        $permission = $args->getEntity();
        $em = $args->getEntityManager();
        if($permission instanceof Permission){
            $user = $permission->user;
            $app = $permission->account->application;
            if($user){
                $user->application = $app;
            }
            $em->flush();
        }

    }

}
