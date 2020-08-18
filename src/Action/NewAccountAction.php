<?php

namespace App\Action;


use App\Dto\NewAccount;
use App\Entity\Account;
use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class NewAccountAction
 * @package App\Action
 */
class NewAccountAction
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

    function __invoke(NewAccount $data): Account {

        # Getting the logged user
        $user = $this->tokenStorage->getToken()->getUser();

        # Creating account
        $account = new Account();
        $this->em->persist($account);

        # Creating permission between user and account
        $permission = new Permission();
        $permission->user = $user;
        $permission->account = $account;
        $permission->grants = [Permission::ACCOUNT_MANAGER];
        $this->em->persist($permission);

        # Link users and accounts
        $user->permissions []= $permission;
        $account->permissions = [$permission];

        # Save into the DB
        $this->em->flush();

        return $account;
    }
}