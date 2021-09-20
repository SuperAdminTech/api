<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;

use App\Entity\Compose\Base;
use App\Security\Restricted;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use App\Dto\PermissionWithUsername;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"user", "account"}, message="Permission for this user and account already exists")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "path"="/sadmin/permissions"
 *          },
 *          "get_app_permissions"={
 *              "path"="/admin/permissions",
 *              "method"="get"
 *          },
 *          "post"={
 *              "path"="/sadmin/permissions"
 *          },
 *          "post_app_permissiona"={
 *              "path"="/admin/permissions",
 *              "method"="post"
 *          },
 *          "post_with_username"={
 *              "path"="/user/permissions",
 *              "method"="POST",
 *              "input"=PermissionWithUsername::class,
 *              "openapi_context"={
 *                  "summary"="Gives permissions to your Account to another User",
 *                  "description"="Gives permission to a user identified by username to an account owned by the authenticated user."
 *              }
 *          },
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/user/permissions/{id}",
 *              "security"="is_granted('ROLE_ADMIN') || object.allowsRead(user)"
 *          },
 *          "put"={
 *              "path"="/user/permissions/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_ADMIN') && object.sameApplication(user)) || object.allowsWrite(user)"
 *          },
 *          "put_from_admin"={
 *              "path"="/admin/permissions/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_ADMIN') && object.sameApplication(user)) || object.allowsWrite(user)",
 *              "method"="put"
 *          },
 *          "delete"={
 *              "path"="/user/permissions/{id}",
 *              "security"="is_granted('ROLE_ADMIN') || object.allowsWrite(user)"
 *          }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *      "user.id": "exact",
 *      "account.id": "exact",
 *      "user.name" : "ipartial",
 *      "account.name" : "ipartial"
 * })
 */
class Permission extends Base implements Restricted {
    const ACCOUNT_WORKER = "ACCOUNT_WORKER";
    const ACCOUNT_MANAGER = "ACCOUNT_MANAGER";
    const ACCOUNT_ALL = ["ACCOUNT_WORKER", "ACCOUNT_MANAGER"];

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="permissions")
     * @Groups({"user:read", "user:write"})
     * @MaxDepth(1)
     */
    public $user;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="permissions")
     * @Groups({"user:read", "user:write"})
     * @MaxDepth(1)
     */
    public $account;

    /**
     * @var string[]
     * @ORM\Column(type="json")
     * @Groups({"user:read", "user:write"})
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

    /**
     * @param User $user
     * @return bool
     */
    function sameApplication(User $user): bool {
        foreach ($user->permissions as $permission) {
            if ($this->account->application->id == $permission->account->application->id)
                return true;
        }
        return false;
    }
}
