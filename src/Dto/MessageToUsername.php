<?php

namespace App\Dto;

use App\Entity\Message;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MessageToUsername
 * @package App\Dto
 */
class MessageToUsername  {

    /**
     * @var string
     * @Groups({"admin:write"})
     * @Assert\Email()
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $username;

    /**
     * @var string
     * @Groups({"admin:write"})
     */
    public $channel = Message::CHANNEL_EMAIL;

    /**
     * @var string
     * @Groups({"admin:write"})
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $subject;

    /**
     * @var string
     * @Groups({"admin:write"})
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public $body;

    /**
     * @var string
     * @Groups({"admin:write"})
     */
    public $body_html;
}