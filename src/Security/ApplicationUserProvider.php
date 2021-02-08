<?php


namespace App\Security;


use App\Entity\Account;
use App\Entity\Application;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;


class ApplicationUserProvider implements PayloadAwareUserProviderInterface {


    /** @var EntityManagerInterface */
    private $em;

    /**
     * ApplicationUserProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsernameAndPayload($username, array $payload) {
        $usersRepo = $this->em->getRepository(User::class);
        $users = $usersRepo->findBy(['username' => $username]);
        /** @var User $user */
        foreach ($users as $user){
            foreach ($user->permissions as $permission){
                if ($permission->account->application->realm == $payload['application']['realm']) {
                    return $user;
                }
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function loadUserByUsername($username)
    {
        throw new \Exception("Method loadUserByUsername() not implemented");
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function refreshUser(UserInterface $user)
    {
        throw new \Exception("Method refreshUser() not implemented");
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}