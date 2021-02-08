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
use App\Dto\VerifyEmail;
use App\Dto\RecoverPasswordRequest;
use App\Dto\RecoverPassword;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "path"="/sadmin/users"
 *          },
 *          "sign_up"={
 *              "method"="post",
 *              "path"="/public/users",
 *              "input"=SignUp::class,
 *              "write"=false,
 *              "openapi_context"={
 *                  "summary"="Call to register users",
 *                  "description"="Creates a new User in the system, with default account and permissions."
 *              }
 *          },
 *          "recover_password_request"={
 *              "method"="post",
 *              "path"="/public/users/recover",
 *              "input"=RecoverPasswordRequest::class,
 *              "write"=false,
 *              "openapi_context"={
 *                  "summary"="Requests a user recover password",
 *                  "description"="Initiates password recovery for a user."
 *              }
 *          }
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
 *          "verify_email"={
 *              "method"="put",
 *              "path"="/public/users/{id}/verify",
 *              "input"=VerifyEmail::class,
 *              "openapi_context"={
 *                  "summary"="Verifies user email",
 *                  "description"="Verifies user email with the email sent code."
 *              }
 *          },
 *          "recover_password"={
 *              "method"="put",
 *              "path"="/public/users/{id}/recover",
 *              "input"=RecoverPassword::class,
 *              "openapi_context"={
 *                  "summary"="Changes the user's password",
 *                  "description"="Uses email provided code to change the user's password, also validates the user's email too if not valid yet."
 *              }
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

    public const RECOVER_PASSWORD_EXPIRES_IN = 1800; // 30 min

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\Email(mode="strict")
     * @Assert\NotNull()
     * @Groups({"public:read", "admin:read", "admin:write", "super:read", "super:write"})
     */
    public $username;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @Groups({"user:read", "admin:write", "super:write"})
     */
    public $email_validated = false;

    /**
     * @var string
     * @ORM\Column(type="guid", nullable=true)
     * @Groups({"super:read", "super:write"})
     */
    public $email_verification_code;

    /**
     * @var string
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"super:read", "super:write"})
     */
    public $recover_password_requested_at;

    /**
     * @var string
     * @ORM\Column(type="guid", nullable=true)
     * @Groups({"super:read", "super:write"})
     */
    public $recover_password_code;

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
     * @var ApiKey[]
     * @ORM\OneToMany(targetEntity=ApiKey::class, mappedBy="user", cascade={"remove"})
     * @Groups({"user:read", "admin:read", "admin:write", "super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $api_keys = [];

    /**
     * @var Permission[]
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="user", cascade={"remove"})
     * @Groups({"user:read", "admin:read", "admin:write", "super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $permissions = [];

    /**
     * @var Message[]
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="user", cascade={"remove"})
     * @Groups({"user:read", "admin:read", "admin:write", "super:read", "super:write"})
     * @MaxDepth(1)
     */
    public $messages = [];

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
