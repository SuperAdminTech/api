<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Permission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PermissionWithUsernameDataTransformer implements DataTransformerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * PermissionWithUsernameDataTransformer constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @inheritDoc
     */
    public function transform($object, string $to, array $context = [])
    {
        $permission = new Permission();
        $permission->grants = $object->grants;

        /** @var Account $account */
        $account = $object->account;
        $permission->account = $account;

        /** @var User $currentUser */
        $currentUser = $this->tokenStorage->getToken()->getUser();
        if (!$account->allowsWrite($currentUser))
            throw new HttpException(Response::HTTP_FORBIDDEN, "User not manager");

        array_unique($object->grants);
        $allowed_grants = array_merge(Permission::ACCOUNT_ALL, $account->application->grants);
        foreach ($object->grants as $grant){
            if (!in_array($grant, $allowed_grants))
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Invalid grant '$grant' for this application");
        }

        $user = $this->findUserInApplication($object->username, $account->application);

        if (!$user) throw new HttpException(Response::HTTP_BAD_REQUEST, "Invalid username");
        $permission->user = $user;

        return $permission;
    }

    /**
     * @param $username
     * @param Application $application
     * @return User|null
     */
    private function findUserInApplication($username, Application $application): ?User {
        $repo = $this->em->getRepository(User::class);
        $users = $repo->findBy(['username' => $username]);
        /** @var User $user */
        foreach ($users as $user){
            foreach ($user->permissions as $permission){
                if ($permission->account->application->id == $application->id) {
                    return $user;
                }
            }
        }
        return null;
    }


    /**
     * @inheritDoc
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof Permission) {
            return false;
        }

        return Permission::class === $to && null !== ($context['input']['class'] ?? null);
    }
}