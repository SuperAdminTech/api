<?php

namespace App\Action;


use App\Dto\Register;
use App\Entity\User;
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
     * RegisterAction constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    function __invoke(Register $data): User {
        $user = new User();
        $user->username = $data->username;
        $user->password = $this->encoder->encodePassword($user, $data->password);
        return $user;
    }
}