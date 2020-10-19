<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\Types;

class Password
{
    private $value;

    public function __construct(string $value, int $length = 8)
    {
        $this->value = $value;
        $this->checkLength($length);
        $this->checkLowercase();
        $this->checkUppercase();
        $this->checkNumber();
        $this->checkDictionary();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function checkLength(int $length): bool
    {
        if (!preg_match('/^\S*(?=\S{'.$length.',})\S*$/', $this->value)) {
            throw new \DomainException("Password must be at least $length characters long");
        }
        return true;
    }

    private function checkLowercase(): bool
    {
        if (!preg_match('/^\S*(?=\S*[a-z])\S*$/', $this->value)) {
            throw new \DomainException('Password must contain at least 1 lowercase letter');
        }
        return true;
    }

    private function checkUppercase(): bool
    {
        if (!preg_match('/^\S*(?=\S*[A-Z])\S*$/', $this->value)) {
            throw new \DomainException('Password must contain at least 1 uppercase letter');
        }
        return true;
    }

    private function checkNumber(): bool
    {
        if (!preg_match('/^\S*(?=\S*[\d])\S*$/', $this->value)) {
            throw new \DomainException('Password must contain at least 1 number');
        }
        return true;
    }

    private function checkDictionary(): bool
    {
        return true;
    }

    public function isEqual(self $password): bool
    {
        return $this->value === $password->getValue();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
