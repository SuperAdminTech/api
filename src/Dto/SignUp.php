<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Action\SignUpAction;

/**
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={
 *          "post"={
 *              "path"="/app/sign_up",
 *              "controller"=SignUpAction::class
 *          }
 *     }
 * )
 */
class SignUp {

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $realm;
}
