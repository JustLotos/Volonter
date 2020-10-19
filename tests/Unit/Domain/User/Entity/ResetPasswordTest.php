<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity;

use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Service\TokenService;
use App\Tests\Builders\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\Domain\User\Entity\User;

class ResetPasswordTest extends TestCase
{
    /** Базовая проверка восстановления пароля */
    public function testRequestResetPassword(): User
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        $token = (new TokenService())->generateTokenByClass(ConfirmToken::class);
        $password = new Password('12345679Ab');

        $user->requestResetPassword($token, $password);
        self::assertTrue($user->isBlocked());
        self::assertEquals($token->getToken(), $user->getConfirmToken()->getToken());
        self::assertTrue($user->getTemporaryPassword() instanceof Password);
        self::assertEquals($password->getValue(), $user->getTemporaryPassword()->getValue());
        return $user;
    }

    /** Проверка исключения неверного статуса при запросе восстановления пароля */
    public function testRequestResetPasswordUserActive(): void
    {
        $user = UserBuilder::getNewByEmail();
        $token = (new TokenService())->generateTokenByClass(ConfirmToken::class);
        $password = new Password('12345679Ab');
        self::expectExceptionMessage('User is not active.');
        $user->requestResetPassword($token, $password);
    }

    /** Проверка исключения при повторном запросе восстановления пароля */
    public function testRequestResetPasswordAlreadyRequested(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        $token = (new TokenService())->generateTokenByClass(ConfirmToken::class);
        $password = new Password('12345679Ab');
        self::expectExceptionMessage('Resetting is already requested.');
        $user->requestResetPassword($token, $password);
        $user->activate();
        $user->requestResetPassword($token, $password);
    }

    public function testRequestResetPasswordTheSame(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        $token = (new TokenService())->generateTokenByClass(ConfirmToken::class);
        $password = new Password('12345678Ab');
        self::expectExceptionMessage('Password the same.');
        $user->requestResetPassword($token, $password);
    }

    /**
     * Проверка подтверждения смены пароля
     * @depends testRequestResetPassword
     */
    public function testConfirmResetPassword(User $user): void
    {
        $resetValue = $user->getTemporaryPassword()->getValue();
        $user->confirmResetPassword();

        self::assertTrue($user->isActive());
        self::assertEquals($user->getPassword()->getValue(), $resetValue);
        self::assertNull($user->getTemporaryPassword());
        self::assertNull($user->getConfirmToken());
        self::assertTrue($user->isActive());
    }

    /** Проверка исключения подтверждения при отсутствующемм запросе */
    public function testConfirmResetPasswordNotRequested(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        self::expectExceptionMessage('Resetting is not requested.');
        $user->confirmResetPassword();
    }

    public function testConfirmResetPasswordTokenExpired(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        $user->requestResetPassword(
            (new TokenService())->generateTokenByClass(ConfirmToken::class),
            new Password('12345679Ab')
        );
        self::expectExceptionMessage('Reset token is expired.');
        $user->confirmResetPassword(new DateTimeImmutable('+1 day'));
    }

}
