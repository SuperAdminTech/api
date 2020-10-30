<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class RecoverPasswordRequest
 * @package App\Dto
 */
class RecoverPasswordRequest {

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $username;

    /**
     * @var string
     * @Groups({"public:write"})
     */
    public $realm;
}
