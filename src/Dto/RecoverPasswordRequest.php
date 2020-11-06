<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RecoverPasswordRequest
 * @package App\Dto
 */
class RecoverPasswordRequest {

    /**
     * @var string
     * @Groups({"public:write"})
     * @Assert\Email()
     */
    public $username;

    /**
     * @var string
     * @Groups({"public:write"})
     * @Assert\NotNull()
     */
    public $realm;
}
