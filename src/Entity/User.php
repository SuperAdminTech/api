<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Annotation\ApplicationAware;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\SignUp;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"username", "application"}, message="Username already taken")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN')"},
 *          "post"={
 *              "path"="/app/users/sign_up",
 *              "input"=SignUp::class,
 *              "openapi_context"={
 *                  "summary"="Call to register users",
 *                  "description"="Creates a new User in the system, with default account and permissions."
 *              }
 *          },
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_ADMIN') && object.application == user.application) || (is_granted('ROLE_USER') && object == user)"},
 *          "put"={"security"="is_granted('ROLE_SUPER_ADMIN') || (is_granted('ROLE_ADMIN') && object.application == user.application) || (is_granted('ROLE_USER') && object == user)"},
 *          "delete"={"security"="is_granted('ROLE_SUPER_ADMIN')"}
 *     }
 * )
 * @ApplicationAware(applicationFieldName="application_id")
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
     * @var Application
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="users")
     * @Groups({"user:read", "admin:read", "admin:write", "super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $application;

    /**
     * @var Permission[]
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="user")
     * @Groups({"user:read", "admin:read", "admin:write", "super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $permissions;

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
