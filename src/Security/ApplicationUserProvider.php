<?php


namespace App\Security;


use App\Entity\Application;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
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
    public function loadUserByUsernameAndPayload($username, array $payload)
    {
        $appsRepo = $this->em->getRepository(Application::class);
        $app = $appsRepo->findOneBy(['realm' => $payload['application']->realm]);
        if (!$app) throw new UsernameNotFoundException("Realm not belongs to any in application");

        $usersRepo = $this->em->getRepository(User::class);
        $user = $usersRepo->findOneBy(['username' => $username, 'application' => $app]);
        if (!$user) throw new UsernameNotFoundException("Username and Realm not found");
        return $user;
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