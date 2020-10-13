<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

use App\Entity\Compose\Base;
use App\Security\Restricted;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\NewUserAccount;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"name"}, message="Account name already taken.")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "path"="/sadmin/accounts"
 *          },
 *          "post"={
 *              "path"="/admin/accounts"
 *          },
 *          "post_new_user_account"={
 *              "path"="/user/accounts",
 *              "method"="POST",
 *              "input"=NewUserAccount::class,
 *              "openapi_context"={
 *                  "summary"="Creates a Account linked to the authenticated user",
 *                  "description"="Creates a new Account for the current user, with manager permissions."
 *              }
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/user/accounts/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || object.allowsRead(user)"
 *          },
 *          "put"={
 *              "path"="/user/accounts/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || object.allowsWrite(user)"
 *          },
 *          "delete"={
 *              "path"="/user/accounts/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || object.allowsWrite(user)"
 *          }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "name": "partial"})
 */
class Account extends Base implements Restricted {

    /**
     * @var Permission[]
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="account", cascade={"remove"})
     * @MaxDepth(1)
     * @Groups({"user:read", "user:write"})
     */
    public $permissions = [];

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Assert\Length(allowEmptyString="false", max="64")
     * @Groups({"user:read", "user:write"})
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
            if ($permission->user->id == $user->id && in_array(Permission::ACCOUNT_MANAGER, $permission->grants)) {
                return true;
            }
        }
        return false;
    }

}
