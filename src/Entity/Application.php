<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Compose\Base;
use App\Security\Restricted;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/applications"
 *          },
 *          "post"={
 *              "path"="/sadmin/applications"
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/applications/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || object.allowsRead(user)"
 *          },
 *          "put"={
 *              "path"="/admin/applications/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || object.allowsWrite(user)"
 *          },
 *          "delete"={
 *              "path"="/sadmin/applications/{id}"
 *          }
 *     }
 * )
 */
class Application extends Base implements Restricted {

    const USERNAME_IS_EMAIL = 'username_is_email';
    const VALIDATE_EMAIL = 'validate_email';
    const ACCOUNT_ACTIVATED_BY_DEFAULT = 'account_activated_by_default';

    /**
     * @var string $name
     * @ORM\Column(type="string")
     * @Groups({"public:read", "super:write"})
     */
    public $name;

    /**
     * @var string $realm
     * @ORM\Column(type="string")
     * @Groups({"super:read", "super:write"})
     */
    public $realm;

    /**
     * @var Account
     * @ORM\OneToMany(targetEntity=Account::class, mappedBy="application")
     * @Groups({"super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $accounts = [];

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"admin:read", "admin:write"})
     */
    public $config = [];

    /**
     * @var string[]
     * @ORM\Column(type="json")
     * @Groups({"admin:read", "admin:write"})
     */
    public $grants = [];

    function allowsRead(User $user): bool {
        foreach ($user->permissions as $permission) {
            if ($this->id == $permission->account->application->id){
                return true;
            }
        }
        return false;
    }

    function allowsWrite(User $user): bool {
        foreach ($user->permissions as $permission) {
            if (in_array(Permission::ACCOUNT_MANAGER, $permission->grants) && $this->id == $permission->account->application->id){
                return true;
            }
        }
        return false;
    }
}
