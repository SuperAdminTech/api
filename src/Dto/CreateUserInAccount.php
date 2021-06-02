<?php

namespace App\Dto;

use App\Entity\Account;
use App\Entity\Application;
use Symfony\Component\Serializer\Annotation\Groups;

class CreateUserInAccount {

    /**
     * @var string
     * @Groups({"user:read", "user:write"})
     */
    public $username;

    /**
     * @var Account
     * @Groups({"user:read", "user:write"})
     */
    public $account;

    /**
     * @var Application
     * @Groups({"user:read", "user:write"})
     */
    public $application;

    /**
     * @var string[]
     * @Groups({"user:read", "user:write"})
     */
    public $permissions;

    /**
     * @var string[]
     * @Groups({"user:read", "user:write"})
     */
    public $roles;


}
