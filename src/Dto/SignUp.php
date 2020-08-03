<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Action\SignUpAction;

/**
 * @ApiResource(
 *     routePrefix="/app",
 *     shortName="Register",
 *     itemOperations={},
 *     collectionOperations={
 *          "post"={
 *              "path"="/sign_up",
 *              "controller"=SignUpAction::class,
 *              "write"=false,
 *              "openapi_context"={
 *                  "summary"="The registration endpoint",
 *                  "description"="Creates a new User in the system, with default account and permissions."
 *              }
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
