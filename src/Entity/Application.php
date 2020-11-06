<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
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

    /**
     * @var string $name
     * @ORM\Column(type="string")
     * @Groups({"user:read", "super:write"})
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
     * @var Config
     * @ORM\OneToOne(targetEntity=Config::class)
     * @Groups({"admin:read", "admin:write"})
     */
    public $config;

    /**
     * @var string[]
     * @ORM\Column(type="json")
     * @Groups({"admin:read", "admin:write"})
     */
    public $grants = [];

    /**
     * @var string[]
     * @ORM\Column(type="json")
     * @Groups({"admin:read", "admin:write"})
     */
    public $default_grants = [];

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
