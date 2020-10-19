<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Change\Email\Confirm;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    public $token;

    public function __construct(string $value)
    {
        $this->token = $value;
    }
}
