<?php

declare(strict_types=1);

namespace App\Tests\Builders;

use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\Types\Id;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\Types\Role;
use App\Domain\User\Entity\User;
use App\Domain\User\Service\TokenService;

class UserBuilder
{
    private static $id;
    private static $role;
    private static $dateCreate;
    private static $token;
    private static $email;
    private static $password;

    public static function getNewByEmail(): User
    {
        self::setDefaultSettings();
        return User::registerByEmail(
            self::$id,
            self::$dateCreate,
            self::$role,
            self::$email,
            self::$password
        );
    }

    private static function setDefaultSettings(): void
    {
        self::$id = Id::next();
        self::$dateCreate = new \DateTimeImmutable();
        self::$role = Role::createAdmin();
        self::$token = (new TokenService())->generateTokenByClass(ConfirmToken::class);
        self::$email = new Email('test@test.test');
        self::$password = new Password('12345678Ab');
    }

    public static function getDefaultSettings(): array
    {
        return [
            'id' => self::$id,
            'dateCreate' => self::$dateCreate,
            'role' => self::$role,
            'token' => self::$token,
            'email' => self::$email,
            'password' => self::$password
        ];
    }
}
