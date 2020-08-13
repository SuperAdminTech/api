<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Application;
use App\Entity\Permission;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"username", "application"}, message="Username already taken")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN') || (is_granted('ROLE_USER') && object == user)"},
 *          "put"={"security"="is_granted('ROLE_ADMIN') || (is_granted('ROLE_USER') && object == user)"}
 *     }
 * )
 */
class User extends Base implements UserInterface
{

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\Length(allowEmptyString="false", max="180")
     */
    public $username;

    /**
     * @ORM\Column(type="json")
     */
    public $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    public $password;

    /**
     * @var Application
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="users")
     */
    public $application;

    /**
     * @var Permission[]
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="user")
     */
    public $permissions;

    /**
     * @var Application[]
     * @ORM\OneToMany(targetEntity=Application::class, mappedBy="administrator")
     */
    public $applications = [];

    /**
     * @var string The plain password
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
