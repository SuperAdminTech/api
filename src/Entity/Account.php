<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Permission;

/**
 * @ORM\Entity
 * @ApiResource()
 */
class Account extends Base {

    /**
     * @var Permission[]
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="account")
     */
    public $permissions;
}
