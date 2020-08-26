<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Account;
use App\Entity\Permission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NewUserAccountDataTransformer implements DataTransformerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var ValidatorInterface */
    private $validator;


    /**
     * PermissionWithUsernameDataTransformer constructor.
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


    /**
     * @inheritDoc
     */
    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        # Getting the logged user
        $user = $this->tokenStorage->getToken()->getUser();

        # Creating account
        $account = new Account();
        $account->name = $object->name;

        $this->validator->validate($account);
        $this->em->persist($account);

        # Creating permission between user and account
        $permission = new Permission();
        $permission->user = $user;
        $permission->account = $account;
        $permission->grants = [Permission::ACCOUNT_MANAGER];

        $this->validator->validate($permission);
        $this->em->persist($permission);

        # Link users and accounts
        $user->permissions []= $permission;
        $account->permissions = [$permission];

        return $account;

    }

    /**
     * @inheritDoc
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof Account) {
            return false;
        }

        return Account::class === $to && null !== ($context['input']['class'] ?? null);
    }
}