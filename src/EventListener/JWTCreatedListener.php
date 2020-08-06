<?php


namespace App\EventListener;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener {

    /** @var RequestStack */
    private $requestStack;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * JWTCreatedListener constructor.
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $em
     */
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function onJWTCreated(JWTCreatedEvent $event){
        $payload = $event->getData();

        $request = $this->requestStack->getCurrentRequest();
        $payload['ip'] = $request->getClientIp();
        /** @var User $user */
        $user = $event->getUser();
        $payload['application'] = [
            'name' => $user->application->name,
            'realm' => $user->application->realm
        ];
        $payload['permissions'] = $user->permissions;

        $event->setData($payload);
    }
}