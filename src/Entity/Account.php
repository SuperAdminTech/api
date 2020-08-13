<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Security\Restricted;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN')"},
 *          "post"={"security"="is_granted('ROLE_ADMIN') || in_array(user, object.writers)"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN') || in_array(user, object.readers)"},
 *          "put"={"security"="is_granted('ROLE_ADMIN') || in_array(user, object.writers)"},
 *          "delete"={"security"="is_granted('ROLE_ADMIN') || in_array(user, object.writers)"}
 *     }
 * )
 */
class Account extends Base implements Restricted {

    /**
     * @var Permission[]
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="account")
     */
    public $permissions;

    /**
     * @inheritDoc
     */
    function getWriters(): array
    {
        $owners = [];
        /** @var Permission $permission */
        foreach ($this->permissions as $permission){
            if (in_array(Permission::ACCOUNT_MANAGER, $permission->grants))
                $owners []= $permission->user;
        }
        return $owners;
    }

    /**
     * @inheritDoc
     */
    function getReaders(): array
    {
        $owners = [];
        /** @var Permission $permission */
        foreach ($this->permissions as $permission){
            $owners []= $permission->user;
        }
        return $owners;
    }
}
