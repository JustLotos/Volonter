<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\BaseFixture;
use App\DataFixtures\User\UserFixtures;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id as LearnerId;
use App\Domain\Flash\Learner\Entity\Types\Name;
use App\Domain\User\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LearnerFixtures extends BaseFixture implements DependentFixtureInterface
{
    public const ADMIN_LEARNERS = 'ADMIN_LEARNERS';
    public const USER_LEARNERS = 'USER_LEARNERS';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(1, self::ADMIN_LEARNERS, function () {
            /** @var User $user */
            $user = $this->getRandomReference(UserFixtures::ADMINS);
            /** @var Learner  */

            $this->faker->image();
            $learner = Learner::create(new LearnerId($user->getId()->getValue()));
            return $learner->changeName(new Name('Роман', 'Игнашов'));
        });

        $this->createMany(1, self::USER_LEARNERS, function () {
            /** @var User $user */
            $user = $this->getRandomReference(UserFixtures::USERS);
            /** @var Learner  */
            $learner = Learner::create(new LearnerId($user->getId()->getValue()));
            return $learner->changeName(new Name('Тестовый', 'Пользователь'));
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
