<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

use App\Annotation\ApplicationAware;
use App\Entity\Compose\Base;
use App\Entity\Compose\NameTrait;
use App\Security\Restricted;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use App\Dto\NewUserAccount;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"name", "application"}, message="Account name already taken.")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/accounts"
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
 * @ApplicationAware(applicationFieldName="application_id")
 */
class Account extends Base implements Restricted {

    use NameTrait;

    /**
     * @var Permission[]
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="account", cascade={"remove"})
     * @MaxDepth(1)
     * @Groups({"user:read", "user:write"})
     */
    public $permissions = [];

    /**
     * @var Message[]
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="account", cascade={"remove"})
     * @Groups({"user:read", "admin:read", "admin:write", "super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $messages = [];

    /**
     * @var Application
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="accounts")
     * @Groups({"user:read", "admin:read", "admin:write", "super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $application;

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
