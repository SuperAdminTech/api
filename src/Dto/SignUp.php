<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class SignUp {

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $username;

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $password;

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $realm;
}
