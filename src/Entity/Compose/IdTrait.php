<?php


namespace App\Entity\Compose;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait IdTrait
 * @package App\Entity
 */
trait IdTrait {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * @Groups({"user:read"})
     */
    public $id;
}