<?php


namespace App\Domain\Helper\Task\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class Command
{
    /**
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    public $title;

    /**
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    public $body;

    /**
     * @Serializer\Type(name="array")
     */
    public $tags;

    /**
     * @Serializer\Type(name="array")
     */
    public $geo;
}