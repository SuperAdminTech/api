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
        $payload['id'] = $user->id;

        //change expiration if configured for this application
        $custom_ttl = $user->application->config->custom_jwt_ttl;
        if($custom_ttl) {
            $expiration = new \DateTime('+'.$custom_ttl.' seconds');
            $payload['exp'] = $expiration->getTimestamp();
        }
        if (count($user->permissions) <= 0)
            throw new \LogicException("User cannot have zero accounts.");
        $app = $user->permissions[0]->account->application;
        $payload['application'] = [
            'name' => $app->name,
            'realm' => $app->realm
        ];
        $payload['permissions'] = [];
        foreach ($user->permissions as $permission){
            //check if account is enabled
            if($permission->account->isEnabled()){
                $payload['permissions'] []= [
                    'grants' => $permission->grants,
                    'account' => [
                        'id' => $permission->account->id,
                        'name' => $permission->account->name
                    ]
                ];
            }

        }

        $event->setData($payload);
    }
}