<?php

namespace App\Entity\Compose;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 * @ApiFilter(SearchFilter::class, properties={"id": "exact"})
 */
abstract class Base {
    use IdTrait, TimestampableTrait;
}
