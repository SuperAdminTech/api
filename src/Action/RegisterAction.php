<?php

namespace App\Action;


use App\Dto\Register;
use App\Entity\Application;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterAction
 * @package App\Action
 */
class RegisterAction
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * RegisterAction constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->encoder = $encoder;
        $this->em = $em;
    }

    function __invoke(Register $data): User {
        /** @var Application $app */
        $app = $this->em->getRepository(Application::class)->findOneBy(['token' => $data->app_token]);
        if(!$app) throw new HttpException(404, "Application Token not found");
        $user = new User();
        $user->application = $app;
        $user->username = $data->username;
        $user->password = $this->encoder->encodePassword($user, $data->password);
        return $user;
    }
}