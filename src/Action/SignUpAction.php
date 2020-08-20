<?php

namespace App\Action;


use App\Dto\SignUp;
use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Permission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class SignUpAction
 * @package App\Action
 */
class SignUpAction
{

    /** @var EntityManagerInterface $em */
    private $em;

    /**
     * RegisterAction constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    function __invoke(SignUp $data): User {
        /** @var Application $app */
        $app = $this->em
            ->getRepository(Application::class)
            ->findOneBy(['realm' => $data->realm]);
        if(!$app) throw new HttpException(400, "Application realm empty or invalid");

        # Creating user
        $user = new User();
        $user->application = $app;
        $user->username = $data->username;
        $user->plain_password = $data->password;
        $this->em->persist($user);

        # Creating account
        $account = new Account();
        $account->name = $data->username;
        $this->em->persist($account);

        # Creating permission between user and account
        $permission = new Permission();
        $permission->user = $user;
        $permission->account = $account;
        $permission->grants = [Permission::ACCOUNT_MANAGER];
        $this->em->persist($permission);

        # Link users and accounts
        $user->permissions = [$permission];
        $account->permissions = [$permission];

        # Save into the DB
        $this->em->flush();

        return $user;
    }
}