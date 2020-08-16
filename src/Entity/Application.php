<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Annotation\ReaderAware;
use App\Security\Restricted;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_USER')"},
 *          "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN') || (is_granted('ROLE_USER') && in_array(user, object.readers)"},
 *          "put"={"security"="is_granted('ROLE_ADMIN') || (is_granted('ROLE_USER') && in_array(user, object.writers))"}
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ReaderAware(readerFieldName="administrator_id")
 */
class Application extends Base implements Restricted {

    /**
     * @var string $name
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @var string $realm
     * @ORM\Column(type="string")
     */
    public $realm;

    /**
     * @var User
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="application")
     */
    public $users;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="applications")
     */
    public $administrator;

    /**
     * @inheritDoc
     */
    function getWriters(): array
    {
        return [$this->administrator];
    }

    /**
     * @inheritDoc
     */
    function getReaders(): array
    {
        return $this->getWriters();
    }
}
