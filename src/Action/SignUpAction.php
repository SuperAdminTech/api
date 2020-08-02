<?php

namespace App\Action;


use App\Dto\SignUp;
use App\Entity\Application;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class SignUpAction
 * @package App\Action
 */
class SignUpAction
{

    /**
     * @var EntityManagerInterface
     */
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
        $user = new User();
        $user->application = $app;
        $user->username = $data->username;
        $user->plain_password = $data->password;
        return $user;
    }
}