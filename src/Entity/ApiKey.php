<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Compose\Base;
use App\Entity\Compose\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use SuperAdmin\Bundle\Entity\Compose\UserOwnedTrait;
use SuperAdmin\Bundle\Security\UserOwned;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Class ApiKey
 * @package App\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/api_keys"
 *          },
 *          "post"={
 *              "path"="/admin/api_keys"
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/api_keys/{id}"
 *          },
 *          "delete"={
 *              "path"="/admin/api_keys/{id}"
 *          }
 *     }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={"user", "name"}, message="ApiKey name already in use.")
 */
class ApiKey extends Base {
    use NameTrait;

    /**
     * TODO: CIDRs and URLs are not checked at this point, must implement!
     */
    public const CIDR_ALL = '0.0.0.0/0';
    public const URLS_ALL = '.*';

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Groups({"user:read"})
     */
    public $secret;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="api_keys")
     * @Groups({"user:read", "user:write"})
     * @MaxDepth(1)
     * @ApiProperty(readableLink=false, writableLink=false)
     */
    public $user;

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"user:read"})
     */
    public $cidr = [ApiKey::CIDR_ALL];

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Groups({"user:read"})
     */
    public $urls = [ApiKey::URLS_ALL];

}