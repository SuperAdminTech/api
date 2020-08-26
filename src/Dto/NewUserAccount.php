<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class NewUserAccount {

    /**
     * @var string
     * @Groups({"user:write"})
     * @Assert\Length(allowEmptyString="false", max="64")
     * @Assert\NotNull()
     */
    public $name;

}
