<?php


namespace App\Domain\Helper\Task\UseCase\AddTag;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class Command
{
    /**
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    public $name;
}