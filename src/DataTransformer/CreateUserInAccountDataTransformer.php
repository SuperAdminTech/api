<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\CreateUserInAccount;
use App\Dto\SignUp;
use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Permission;
use App\Entity\User;
use App\Utils\EmailUtils;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CreateUserInAccountDataTransformer implements DataTransformerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var ValidatorInterface */
    private $validator;

    /** @var EmailUtils */
    private $mailing;


    /**
     * PermissionWithUsernameDataTransformer constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     * @param ValidatorInterface $validator
     * @param EmailUtils $mailing
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, ValidatorInterface $validator, EmailUtils $mailing)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->validator = $validator;
        $this->mailing = $mailing;
    }


    /**
     * @inheritDoc
     */
    public function transform($object, string $to, array $context = [])
    {
        $appsRepo = $this->em->getRepository(Application::class);
        $usersRepo = $this->em->getRepository(User::class);
        $accsRepo = $this->em->getRepository(Account::class);

        //die(print_r($object->application_id,true));

        /** @var Application $app */
        $app = $appsRepo->find($object->application);
        if(!$app) throw new HttpException(Response::HTTP_BAD_REQUEST, "Application id empty or invalid");

        $account = $accsRepo->find($object->account);
        if(!$account) throw new HttpException(Response::HTTP_BAD_REQUEST, "Account id empty or invalid");

        //check that application belongs to account and admin belongs to account too
        /** @var User $admin */
        $admin = $this->tokenStorage->getToken()->getUser();

        if(!$this->sameApplication($admin, $app)) throw new HttpException(403, 'You do not have permissions in this application');

        //TODO check there is not superadmin role

        $users = $usersRepo->findBy(['username' => $object->username]);

        /** @var User $user */
        foreach ($users as $user){
            foreach ($user->permissions as $permission){
                if ($permission->account->application->id == $app->id){
                    throw new HttpException(Response::HTTP_BAD_REQUEST, "Username already taken.");
                }
            }
        }

        # Creating user
        $user = new User();
        $user->username = $object->username;
        $user->plain_password = Uuid::uuid4()->toString();
        $user->email_verification_code = Uuid::uuid4()->toString();
        $user->roles = $object->roles;
        $user->application = $app;
        $this->validator->validate($user);
        $this->em->persist($user);

        # Creating permission between user and account
        $permission = new Permission();
        $permission->user = $user;
        $permission->account = $account;
        $permission->grants = $app->default_grants;
        $this->validator->validate($permission);
        $this->em->persist($permission);

        # Link users and accounts
        $user->permissions = [$permission];
        $account->permissions = [$permission];

        $this->mailing->sendEmailTemplate(
            $user,
            'sign_up',
            'Welcome to {{ application.name }}'
        );
        $this->em->flush();

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

        return User::class === $to && CreateUserInAccount::class == $context['input']['class'];
    }

    private function sameApplication(User $admin, Application $app): bool {
        foreach ($admin->permissions as $permission) {
            if ($app->id == $permission->account->application->id)
                return true;
        }
        return false;
    }
}