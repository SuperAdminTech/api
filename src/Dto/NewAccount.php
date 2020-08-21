<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Action\NewAccountAction;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     routePrefix="/user",
 *     shortName="NewAccount",
 *     itemOperations={},
 *     collectionOperations={
 *          "post"={
 *              "path"="/new_account",
 *              "controller"=NewAccountAction::class,
 *              "write"=false,
 *              "openapi_context"={
 *                  "summary"="The creation accounts endpoint",
 *                  "description"="Creates a new Account for the current user, with manager permissions."
 *              },
 *              "security"="is_granted('ROLE_USER')"
 *          }
 *     }
 * )
 */
class NewAccount {

    /**
     * @var string
     * @Groups({"user:write"})
     * @Assert\Length(allowEmptyString="false", max="64")
     * @Assert\NotNull()
     */
    public $name;

}
