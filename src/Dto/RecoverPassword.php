<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class RecoverPassword
 * @package App\Dto
 */
class RecoverPassword {

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $code;

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $password;
}
