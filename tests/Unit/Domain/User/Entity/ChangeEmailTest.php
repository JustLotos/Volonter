<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity;

use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Service\TokenService;
use App\Tests\Builders\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\Domain\User\Entity\User;

class ChangeEmailTest extends TestCase
{
    /** Базовая проверка смены email */
    public function testRequestResetEmail(): User
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        $token = (new TokenService())->generateTokenByClass(ConfirmToken::class);
        $email = new Email('teset@test.test');

        $user->requestChangeEmail($token, $email);
        self::assertTrue($user->isBlocked());
        self::assertEquals($user->getTemporaryEmail()->getValue(), $email->getValue());
        self::assertEquals($user->getConfirmToken()->getToken(), $token->getToken());
        return $user;
    }

    /** Проверка исключения неверного статуса при запросе смены email */
    public function testRequestResetEmailUserActive(): void
    {
        $user = UserBuilder::getNewByEmail();
        $token = (new TokenService())->generateTokenByClass(ConfirmToken::class);
        $email = new Email('teset@test.test');
        self::expectExceptionMessage('User is not active.');
        $user->requestChangeEmail($token, $email);
    }

    /** Проверка исключения при повторном запросе смены email */
    public function testRequestResetEmailAlreadyRequested(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        $token = (new TokenService())->generateTokenByClass(ConfirmToken::class);
        $email = new Email('teset@test.test');
        self::expectExceptionMessage('Resetting is already requested.');
        $user->requestChangeEmail($token, $email);
        $user->activate();
        $user->requestChangeEmail($token, $email);
    }

    /** Проверка исключения при смене email на такой же email */
    public function testRequestResetEmailTheSame(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        $token = (new TokenService())->generateTokenByClass(ConfirmToken::class);
        self::expectExceptionMessage('Email is same.');
        $user->requestChangeEmail($token, UserBuilder::getDefaultSettings()['email']);
    }

    /**
     * Проверка подтверждения смены уьфшд
     * @depends testRequestResetEmail
     */
    public function testConfirmResetPassword(User $user): void
    {
        $resetValue = $user->getTemporaryEmail()->getValue();
        $user->confirmChangeEmail();

        self::assertTrue($user->isActive());
        self::assertEquals($user->getEmail()->getValue(), $resetValue);
        self::assertNull($user->getTemporaryEmail());
        self::assertNull($user->getConfirmToken());
        self::assertTrue($user->isActive());
    }

    /** Проверка исключения подтверждения при отсутствующемм запросе */
    public function testConfirmResetEmailNotRequested(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        self::expectExceptionMessage('Changing is not requested.');
        $user->confirmChangeEmail();
    }

    public function testConfirmResetEmailTokenExpired(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        $user->requestChangeEmail(
            (new TokenService())->generateTokenByClass(ConfirmToken::class),
            new Email('test@mail.test')
        );
        self::expectExceptionMessage('Reset token is expired.');
        $user->confirmResetPassword(new DateTimeImmutable('+1 day'));
    }
}
