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
     */
    public $subject;

    /**
     * @var string
     * @Groups({"admin:write"})
     */
    public $body;

    /**
     * @var string
     * @Groups({"admin:write"})
     */
    public $body_html;
}