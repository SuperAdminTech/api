<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Action\SignUpAction;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups({"public:write"})
     */
    public $username;

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $password;

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $realm;
}
