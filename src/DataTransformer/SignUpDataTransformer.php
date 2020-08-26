<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Permission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SignUpDataTransformer implements DataTransformerInterface
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
        /** @var Application $app */
        $app = $this->em
            ->getRepository(Application::class)
            ->findOneBy(['realm' => $object->realm]);
        if(!$app) throw new HttpException(400, "Application realm empty or invalid");

        # Creating user
        $user = new User();
        $user->application = $app;
        $user->username = $object->username;
        $user->plain_password = $object->password;
        $this->em->persist($user);

        # Creating account
        $account = new Account();
        $account->name = $object->username;
        $this->em->persist($account);

        # Creating permission between user and account
        $permission = new Permission();
        $permission->user = $user;
        $permission->account = $account;
        $permission->grants = [Permission::ACCOUNT_MANAGER];
        $this->em->persist($permission);

        # Link users and accounts
        $user->permissions = [$permission];
        $account->permissions = [$permission];

        return $user;

    }

    /**
     * @inheritDoc
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && null !== ($context['input']['class'] ?? null);
    }
}