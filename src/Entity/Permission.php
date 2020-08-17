<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Security\Restricted;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"user", "account"}, message="Permission for this user and account already exists")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_SUPER_ADMIN')"},
 *          "post"={"security"="is_granted('ROLE_SUPER_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_USER') && object.allowsRead(user)"},
 *          "put"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_USER') && object.allowsWrite(user))"},
 *          "delete"={"security"="is_granted('ROLE_SUPER_ADMIN')"}
 *     }
 * )
 */
class Permission extends Base implements Restricted {
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
    public $grants = [self::ACCOUNT_WORKER];

    /**
     * @inheritDoc
     */
    function allowsRead(User $user): bool
    {
        return $this->user->id == $user->id;
    }

    /**
     * @inheritDoc
     */
    function allowsWrite(User $user): bool
    {
        return $this->allowsRead($user) && in_array(self::ACCOUNT_MANAGER, $this->grants);
    }
}
