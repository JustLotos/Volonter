<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity;

use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Service\TokenService;
use App\Tests\Builders\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\Domain\User\Entity\User;

class ByEmailCreationTest extends TestCase
{
    public function testRegisterByEmail(): void
    {
        /** Create */
        $user = UserBuilder::getNewByEmail();
        $settings = UserBuilder::getDefaultSettings();
        /** Assertion */
        self::assertEquals($settings['id'], $user->getId()->getValue());
        self::assertEquals($settings['dateCreate']->getTimestamp(), $user->getDateCreated()->getTimestamp());
        self::assertEquals($settings['dateCreate']->getTimestamp(), $user->getDateUpdated()->getTimestamp());
        self::assertEquals($settings['role']->getName(), $user->getRole()->getName());
        self::assertEquals($settings['email'], $user->getEmail());
        self::assertEquals($settings['password'], $user->getPassword());
        self::assertEquals(true, $user->isWait());
    }

    /** Проверка исключения о том что учетная запись подтверждена */
    public function testRequestConfirmRegisterByEmailAlreadyConfirmed(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->activate();
        self::expectExceptionMessage('User is already confirmed.');
        $user->requestRegisterConfirm((new TokenService())->generateTokenByClass(ConfirmToken::class));
    }

    /** Проверка исключения о том что запрос на подтверждение учетной записи уже сделан*/
    public function testRequestConfirmRegisterByEmailAlreadyReset(): void
    {
        $user = UserBuilder::getNewByEmail();
        self::expectExceptionMessage('Resetting is already requested.');
        $user->requestRegisterConfirm((new TokenService())->generateTokenByClass(ConfirmToken::class));
        $user->requestRegisterConfirm((new TokenService())->generateTokenByClass(ConfirmToken::class));
    }

    /** Проверка метода запроса на подтверждение учетной записи */
    public function testRequestConfirmRegister(): User
    {
        $user = UserBuilder::getNewByEmail();
        $settings = UserBuilder::getDefaultSettings();
        $token = $user->requestRegisterConfirm($settings['token']);
        self::assertEquals($settings['token']->getToken(), $user->getConfirmToken()->getToken());
        self::assertEquals($token->getToken(), $user->getConfirmToken()->getToken());
        self::assertTrue($user->isWait());
        return $user;
    }

    /**
     * Проверка работы метода подтверждения регистрации
     * @depends testRequestConfirmRegister
     */
    public function testConfirmRegister(User $user): void
    {
        $user->confirmRegister(new DateTimeImmutable());
        self::assertTrue($user->isActive());
        self::assertNull($user->getConfirmToken());
    }

    /** Проверка истечения срока годности у токена подтверждения регистрации */
    public function testConfirmRegisterExpiredToken(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->requestRegisterConfirm((new TokenService())->generateTokenByClass(ConfirmToken::class));
        self::expectExceptionMessage('Confirm token is expired.');
        $user->confirmRegister(new DateTimeImmutable('+1 hour'));
    }

    /** Проверка повторного подтверждения регистрации */
    public function testConfirmRegisterAlreadyConfirmed(): void
    {
        $user = UserBuilder::getNewByEmail();
        $user->requestRegisterConfirm((new TokenService())->generateTokenByClass(ConfirmToken::class));
        self::expectExceptionMessage('Confirm user in not requested.');
        $user->confirmRegister(new DateTimeImmutable());
        $user->confirmRegister(new DateTimeImmutable());
    }
}
