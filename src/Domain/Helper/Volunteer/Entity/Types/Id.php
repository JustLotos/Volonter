<?php

declare(strict_types=1);

namespace App\Domain\Helper\Volunteer\Entity\Types;

use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    /**
     *  @var string
     *  @Serializer\Type(name="string")
     *  @Serializer\Groups({App\Domain\Helper\Volunteer\Entity\Volunteer::GROUP_SIMPLE})
     */
    private $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        $this->value = $value;
    }

    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
