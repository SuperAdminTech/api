<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Compose\Base;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use App\Dto\MessageToUsername;
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
 *              "path"="/admin/messages",
 *              "input"=MessageToUsername::class
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
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @Groups({"admin:write"})
     */
    public $user;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"user:read"})
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
     * @Groups({"user:read"})
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $subject;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"user:read"})
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $body;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user:read"})
     */
    public $body_html;
}