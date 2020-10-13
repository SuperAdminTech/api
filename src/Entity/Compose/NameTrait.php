<?php

namespace App\Entity\Compose;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait NameTrait
 * @package App\Entity
 */
trait NameTrait {

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"user:read"})
     */
    public $name;

}