<?php

declare(strict_types=1);

namespace App\DataFixtures\Helper;

use App\DataFixtures\User\UserFixtures;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Types\Name;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use App\Domain\User\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;

class VolunteerFixtures extends BaseFixture implements ContainerAwareInterface
{
    public const ADMIN = 'ADMIN_VOLUNTEERS';
    public const USER = 'USER_VOLUNTEERS';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(1, self::ADMIN, function () {
            /** @var User $user */
            $user = $this->getRandomReference(UserFixtures::ADMINS);
            /** @var Volunteer  */

            $this->faker->image();
            $learner = Volunteer::create(new Id($user->getId()->getValue()));
            return $learner->changeName(new Name('Роман', 'Игнашов'));
        });

        $this->createMany(1, self::USER, function () {
            /** @var User $user */
            $user = $this->getRandomReference(UserFixtures::USERS);
            /** @var Volunteer  */
            $learner = Volunteer::create(new Id($user->getId()->getValue()));
            return $learner->changeName(new Name('Тестовый', 'Пользователь'));
        });


        $manager->flush();
    }
}
