<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class SignInController
{

    function signIn(Request $request){
        throw new AuthenticationCredentialsNotFoundException("Credentials not found");
    }
}