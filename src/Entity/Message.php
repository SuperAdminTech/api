<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Compose\Base;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Class Message
 * @package App\Entity
 * @ORM\Entity
 * @ApiResource()
 */
class Message extends Base {

    public const STATUS_UNSENT = "unsent";
    public const STATUS_SENT = "sent";
    public const STATUS_FAILED = "failed";

    public const CHANNEL_EMAIL = "email";

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @Groups({"user:read", "user:write"})
     * @MaxDepth(1)
     */
    public $user;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $status;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $channel = self::CHANNEL_EMAIL;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $subject;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $body;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $body_html;
}