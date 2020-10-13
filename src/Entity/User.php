<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

use App\Entity\Compose\Base;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\SignUp;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/users"
 *          },
 *          "post"={
 *              "path"="/public/users",
 *              "input"=SignUp::class,
 *              "openapi_context"={
 *                  "summary"="Call to register users",
 *                  "description"="Creates a new User in the system, with default account and permissions."
 *              }
 *          },
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/user/users/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || object == user"
 *          },
 *          "put"={
 *              "path"="/user/users/{id}",
 *              "security"="is_granted('ROLE_SUPER_ADMIN') || object == user"
 *          },
 *          "delete"={
 *              "path"="/sadmin/users/{id}",
 *              "security"="object != user",
 *              "security_message"="User cannot delete itself"
 *          }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "username": "partial"})
 */
class User extends Base implements UserInterface {

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\Length(allowEmptyString="false", max="180")
     * @Groups({"public:read", "admin:read", "admin:write", "super:read", "super:write"})
     */
    public $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read", "admin:read", "admin:write", "super:read", "super:write"})
     */
    public $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"super:read", "super:write"})
     */
    public $password;

    /**
     * @var Permission[]
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="user", cascade={"remove"})
     * @Groups({"user:read", "admin:read", "admin:write", "super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $permissions = [];

    /**
     * @var string The plain password
     * @Groups({"user:write", "admin:write", "super:read", "super:write"})
     */
    public $plain_password;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plain_password = null;
    }

}
