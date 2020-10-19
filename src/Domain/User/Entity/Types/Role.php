<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\Types;

use JMS\Serializer\Annotation as Serializer;
use Webmozart\Assert\Assert;
use App\Domain\User\Entity\User;

class Role
{
    public const USER = 'ROLE_USER';
    public const ADMIN = 'ROLE_ADMIN';

    private $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::USER,
            self::ADMIN,
        ]);

        $this->name = $name;
    }

    public static function createUser(): self
    {
        return new self(self::USER);
    }

    public static function createAdmin(): self
    {
        return new self(self::ADMIN);
    }

    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    public function isEqual(self $role): bool
    {
        return $this->getName() === $role->getName();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("roles")
     * @Serializer\Groups({USER::GROUP_SIMPLE})
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
