<?php

declare(strict_types=1);

namespace App\DataFixtures\User;

use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\Types\Id;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\Types\Role;
use App\Domain\User\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;

class UserFixtures extends BaseFixture implements ContainerAwareInterface
{
    public const ADMINS = 'ADMINS';
    public const USERS = 'USERS';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(1, self::ADMINS, function () {
            $user = User::createByEmail(
                Id::next(),
                new DateTimeImmutable(),
                Role::createAdmin(),
                new Email(getenv('TEST_USER_EMAIL')),
                new Password(getenv('TEST_USER_PASSWORD'))
            );
            $user->activate();
            return $user;
        });

        $this->createMany(100, self::USERS, function () {
            $user = User::createByEmail(
                Id::next(),
                new DateTimeImmutable(),
                Role::createUser(),
                new Email('test@test.test'),
                new Password('12345678Ab')
            );
            $user->activate();
            return $user;
        });

        $manager->flush();
    }
}
