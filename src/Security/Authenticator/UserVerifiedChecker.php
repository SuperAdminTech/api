<?php

namespace App\Security\Authenticator;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVerifiedChecker implements UserCheckerInterface{

    public function checkPreAuth(UserInterface $user)
    {
        // Implement checkPreAuth() method.
        if(!$user->isEnabled()){
            $ex = new DisabledException('Account is disabled');
            $ex->setUser($user);
            throw $ex;

        }
        if(!$user->isValidated()) throw new HttpException(401, 'User email is not validated.');

    }

    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}