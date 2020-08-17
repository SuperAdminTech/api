<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_SUPER_ADMIN')"},
 *          "post"={"security"="is_granted('ROLE_SUPER_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_USER') && object.user == user)"},
 *          "put"={"security"="is_granted('ROLE_SUPER_ADMIN')"},
 *          "delete"={"security"="is_granted('ROLE_SUPER_ADMIN')"}
 *     }
 * )
 */
class Permission extends Base {
    const ACCOUNT_WORKER = "ACCOUNT_WORKER";
    const ACCOUNT_MANAGER = "ACCOUNT_MANAGER";

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="permissions")
     */
    public $user;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="permissions")
     */
    public $account;

    /**
     * @ORM\Column(type="json")
     */
    public $grants = [];
}
