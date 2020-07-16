<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Action\RegisterAction;

/**
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={
 *          "post"={
 *              "path"="/app/register",
 *              "controller"=RegisterAction::class
 *          }
 *     }
 * )
 */
class Register {

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
    public $app_token;
}
