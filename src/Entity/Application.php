<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_SUPER_ADMIN')"},
 *          "post"={"security"="is_granted('ROLE_SUPER_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_ADMIN') && object == user.application)"},
 *          "put"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_ADMIN') && object == user.application)"},
 *          "delete"={"security"="is_granted('ROLE_SUPER_ADMIN')"}
 *     }
 * )
 */
class Application extends Base {

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
     * @var User
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="application")
     * @Groups({"super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $users;

}
