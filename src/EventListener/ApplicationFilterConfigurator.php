<?php


namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Annotations\Reader;

final class ApplicationFilterConfigurator
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var Reader */
    private $reader;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /**
     * ApplicationFilterConfigurator constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     * @param Reader $reader
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, Reader $reader, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->reader = $reader;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onKernelRequest(): void
    {
        /** @var User $user */
        $user = $this->getUser();
        // filter won't be active for super admins or unauthenticated users
        if (!$user) return;
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) return;


        $filter = $this->em->getFilters()->enable('application_filter');

        $apps = [];
        foreach ($user->permissions as $permission){
            $apps []= $permission->account->application->id;
        }

        $filter->setParameter('applications', base64_encode(json_encode($apps)));
        $filter->setAnnotationReader($this->reader);
    }

    private function getUser(): ?UserInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();
        return $user instanceof UserInterface ? $user : null;
    }
}