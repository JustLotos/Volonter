<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\Types\ConfirmToken;
use DateInterval;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class TokenService
{
    public function generateTokenByClass(string $tokenClassName, DateTimeImmutable $interval = null): ConfirmToken
    {
        if (!($interval instanceof DateTimeImmutable)) {
            $interval = new DateInterval(getenv('CONFIRM_TOKEN_DURATION'));
        }
        return new $tokenClassName(
            Uuid::uuid4()->toString(),
            (new DateTimeImmutable())->add($interval)
        );
    }
}
