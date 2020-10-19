<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Register\ByEmail\Confirm;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    public $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
