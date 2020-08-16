<?php


namespace App\EventListener;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Annotations\Reader;

final class ReaderFilterConfigurator
{
    private $em;
    private $tokenStorage;
    private $reader;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, Reader $reader)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->reader = $reader;
    }

    public function onKernelRequest(): void
    {
        if (!$user = $this->getUser()) {
            return;
            //throw new \RuntimeException('There is no authenticated user.');
        }

        $filter = $this->em->getFilters()->enable('reader_filter');
        $filter->setParameter('id', $user->id);
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