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
     * @var User
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="application")
     */
    public $user;
}
