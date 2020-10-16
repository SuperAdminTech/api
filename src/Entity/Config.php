<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

use App\Entity\Compose\Base;
use App\Security\Restricted;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"name", "application"}, message="Account name already taken.")
 * @ApiResource(
 *     collectionOperations={ },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/configs/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || object.allowsRead(user)"
 *          },
 *          "put"={
 *              "path"="/admin/configs/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || object.allowsWrite(user)"
 *          }
 *     }
 * )
 */
class Config extends Base implements Restricted {
    public const NULL_DSN = "null://null";
    public const DEFAULT_MAILER_FROM = "no-reply@auth.dfnder.com";

    /**
     * @var Application
     * @ORM\OneToOne(targetEntity=Application::class)
     * @Groups({"admin:write", "super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $application;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"admin:read", "admin:write", "super:read", "super:write"})
     */
    public $frontend_url;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"admin:read", "admin:write", "super:read", "super:write"})
     */
    public $mailer_dsn = self::NULL_DSN;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"admin:read", "admin:write", "super:read", "super:write"})
     */
    public $mailer_from;

    /**
     * @inheritDoc
     */
    function allowsRead(User $user): bool {
        foreach ($user->permissions as $permission) {
            if ($this->application->id == $permission->account->application->id){
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    function allowsWrite(User $user): bool {
        foreach ($user->permissions as $permission) {
            if (in_array(Permission::ACCOUNT_MANAGER, $permission->grants)
                && $this->application->id == $permission->account->application->id){
                return true;
            }
        }
        return false;
    }
}
