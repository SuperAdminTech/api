<?php

namespace App\Action;


use App\Dto\GivePermissions;
use App\Entity\Account;
use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class GivePermissionsAction
 * @package App\Action
 */
class GivePermissionsAction
{

    /** @var EntityManagerInterface */
    private $em;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * RegisterAction constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    function __invoke(GivePermissions $data): Permission {

        # Getting the logged user
        $user = $this->tokenStorage->getToken()->getUser();

        # Creating permission between user and account
        $permission = new Permission();
        $permission->user = $user;
        $permission->account = $data->account;
        $permission->grants = $data->grants;
        $this->em->persist($permission);

        # Link users and accounts
        $user->permissions []= $permission;
        $data->account->permissions []= $permission;

        # Save into the DB
        $this->em->flush();

        return $permission;
    }
}