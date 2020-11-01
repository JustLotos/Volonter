<?php


namespace App\Domain\Helper\Comment\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    public $text;


    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    public $taskId;
}