<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\MessageToUsername;
use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Message;
use App\Entity\Permission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageToUsernameDataTransformer implements DataTransformerInterface
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
     * @var MessageToUsername $object
     */
    public function transform($object, string $to, array $context = []) {

        $userRepo = $this->em->getRepository(User::class);
        $sameUsernameUsers = $userRepo->findBy(['username' => $object->username]);

            /** @var User $currentUser */
        $currentUser = $this->tokenStorage->getToken()->getUser();

        foreach ($currentUser->permissions as $permission) {
            $user = $this->findUserInApplication($sameUsernameUsers, $permission->account->application);
            if ($user) {
                $message = new Message();
                $message->user = $user;
                $message->subject = $object->subject;
                $message->channel = $object->channel;
                $message->body = $object->body;
                $message->body_html = $object->body_html;
                return $message;
            }
        }

        throw new HttpException(Response::HTTP_BAD_REQUEST, "Invalid username");
    }

    /**
     * @param array $users
     * @param Application $application
     * @return User|null
     */
    private function findUserInApplication(array $users, Application $application): ?User {
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
        if ($data instanceof Message) {
            return false;
        }

        return Message::class === $to && null !== ($context['input']['class'] ?? null);
    }
}