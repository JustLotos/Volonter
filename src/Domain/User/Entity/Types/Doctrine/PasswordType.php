<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\Types\Doctrine;

use App\Domain\User\Entity\Types\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class PasswordType extends StringType
{
    public const NAME = 'users_user_password';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Password ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Password($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}
