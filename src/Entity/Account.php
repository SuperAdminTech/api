<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Security\Restricted;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_SUPER_ADMIN')"},
 *          "post"={"security"="is_granted('ROLE_USER')"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_USER') && object.allowsRead(user)"},
 *          "put"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_USER') && object.allowsWrite(user))"},
 *          "delete"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_USER') && object.allowsWrite(user))"}
 *     }
 * )
 */
class Account extends Base implements Restricted {

    /**
     * @var Permission[]
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="account")
     * @MaxDepth(1)
     */
    public $permissions;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Assert\Unique(message="Account name already taken.")
     */
    public $name;

    /**
     * @inheritDoc
     */
    function allowsRead(User $user): bool
    {
        foreach ($this->permissions as $permission){
            if ($permission->user->id == $user->id) return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    function allowsWrite(User $user): bool
    {
        foreach ($this->permissions as $permission){
            if ($permission->user->id == $user->id && in_array(Permission::ACCOUNT_MANAGER, $permission->grants)) return true;
        }
        return false;
    }
}
