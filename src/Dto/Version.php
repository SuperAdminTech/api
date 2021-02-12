<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "path"="/public/versions/{id}"
 *          }
 *     },
 *     collectionOperations={}
 * )
 */
class Version {

    /**
     * @var string
     * @Groups({"public:read"})
     * @ApiProperty(identifier=true)
     */
    public $id = 'current';

    /**
     * @var string
     * @Groups({"public:read"})
     */
    public $code;

    public function __construct($code) {
        $this->code = $code;
    }
}
