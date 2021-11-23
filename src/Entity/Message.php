<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Compose\Base;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Message
 * @package App\Entity
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/messages"
 *          },
 *          "post"={
 *              "path"="/admin/messages"
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/messages/{id}"
 *          },
 *          "delete"={
 *              "path"="/admin/messages/{id}"
 *          }
 *     }
 * )
 */
class Message extends Base {

    public const STATUS_UNSENT = "unsent";
    public const STATUS_SENT = "sent";
    public const STATUS_FAILED = "failed";

    public const CHANNEL_EMAIL = "email";

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="messages")
     * @Groups({"admin:write"})
     * @ApiProperty(readableLink=false, writableLink=false)
     */
    public $account;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"user:read", "admin:write"})
     */
    public $status;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"user:read"})
     */
    public $channel = self::CHANNEL_EMAIL;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"user:read", "admin:write"})
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $subject;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Groups({"user:read", "admin:write"})
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $body;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"user:read", "admin:write"})
     */
    public $body_html;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"user:read", "admin:write"})
     */
    public $attachment_file = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "admin:write"})
     */
    public $email;


}