<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\Types\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class IdType extends GuidType
{
    public const NAME = 'users_user_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof \App\Domain\User\Entity\Types\Id ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new \App\Domain\User\Entity\Types\Id($value) : null;
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
