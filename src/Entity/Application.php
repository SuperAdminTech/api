<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ORM\Entity
 * @ApiResource()
 */
class Application extends Base {

    /**
     * @var string $name
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @var string $realm
     * @ORM\Column(type="string")
     */
    public $realm;

    /**
     * @var User
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="application")
     */
    public $users;
}
