<?php

namespace App\Entity\Compose;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class Base {
    use IdTrait, TimestampableTrait;
}
