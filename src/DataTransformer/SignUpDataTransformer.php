<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
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

class SignUpDataTransformer implements DataTransformerInterface
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

        /** @var Application $app */
        $app = $appsRepo->findOneBy(['realm' => $object->realm]);
        if(!$app) throw new HttpException(Response::HTTP_BAD_REQUEST, "Application realm empty or invalid");

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
        $user->plain_password = $object->password;
        $user->email_verification_code = Uuid::uuid4()->toString();
        $this->validator->validate($user);
        $this->em->persist($user);

        # Creating account
        $account = new Account();
        $account->application = $app;
        $account->name = $object->username;
        while ($accsRepo->findOneBy(['name' => $account->name])) {
            $mailUsername = explode('@', $object->username);
            $account->name = "{$mailUsername}#" . rand(1000, 9999);
        }
        $this->validator->validate($account);
        $this->em->persist($account);

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

        return User::class === $to && SignUp::class == $context['input']['class'];
    }
}