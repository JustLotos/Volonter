<?php

declare(strict_types=1);

namespace App\DataFixtures\Helper;

use App\Domain\Helper\Task\Entity\Types\Id as TaskId;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;
use App\Domain\Helper\Task\Entity\Task;

class TaskFixtures extends BaseFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    public const ADMIN = 'ADMIN_HELPER_TASK';
    public const USER = 'USER_HELPER_TASK';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(20, self::ADMIN, function () {
            /** @var Volunteer $volunteer */
            $volunteer = $this->getRandomReference(VolunteerFixtures::ADMINS);
            return new Task($volunteer, TaskId::next(), $this->faker->title);
        });

        $this->createMany(20, self::USER, function () {
            /** @var Volunteer $volunteer */
            $volunteer = $this->getRandomReference(VolunteerFixtures::ADMINS);
            return new Task($volunteer, TaskId::next(), $this->faker->title);
        });


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            VolunteerFixtures::class
        ];
    }
}
