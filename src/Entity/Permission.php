<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Account;

/**
 * @ORM\Entity
 * @ApiResource()
 */
class Permission extends Base {
    const ACCOUNT_WORKER = "ACCOUNT_WORKER";
    const ACCOUNT_MANAGER = "ACCOUNT_MANAGER";

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="permissions")
     */
    public $user;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="permissions")
     */
    public $account;

    /**
     * @ORM\Column(type="json")
     */
    public $grants = [];
}
