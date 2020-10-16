<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class VerifyEmail {

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $code;
}
