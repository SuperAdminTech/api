<?php

namespace App\Action;


use ApiPlatform\Core\Validator\ValidatorInterface;
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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * RegisterAction constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->validator = $validator;
    }

    function __invoke(NewAccount $data): Account {

        $this->validator->validate($data);

        # Getting the logged user
        $user = $this->tokenStorage->getToken()->getUser();

        # Creating account
        $account = new Account();
        $account->name = $data->name;
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