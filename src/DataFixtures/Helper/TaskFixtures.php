<?php

declare(strict_types=1);

namespace App\DataFixtures\Helper;

use App\Domain\Helper\Task\Entity\Types\Geo;
use App\Domain\Helper\Task\UseCase\Create\Command;
use App\Domain\Helper\Task\Entity\Types\Id as TaskId;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;
use App\Domain\Helper\Task\Entity\Task;

class TaskFixtures extends BaseFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    public const ADMINS = 'ADMIN_HELPER_TASK';
    public const ADMIN_TASK_COUNT = 3;
    public const USERS = 'USER_HELPER_TASK';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(self::ADMIN_TASK_COUNT, self::ADMINS, function () {
            /** @var Volunteer $volunteer */
            $volunteer = $this->getRandomReference(VolunteerFixtures::ADMINS);

            $command = new Command();
            $command->title = $this->faker->title;
            $command->body = $this->faker->text;
            $command->geo = new Geo(
                $this->faker->numberBetween().'',
                $this->faker->numberBetween().''
            );

            return new Task(
                $volunteer,
                TaskId::next(),
                $command,
                DateTimeImmutable::createFromMutable($this->faker->dateTime)
            );
        });

        $this->createMany(1, self::USERS, function () {
            /** @var Volunteer $volunteer */
            $volunteer = $this->getRandomReference(VolunteerFixtures::USERS);
            $command = new Command();
            $command->title = $this->faker->title;
            $command->body = $this->faker->text;
            $command->geo = new Geo(
                $this->faker->numberBetween().'',
                $this->faker->numberBetween().''
            );

            return new Task(
                $volunteer,
                TaskId::next(),
                $command,
                DateTimeImmutable::createFromMutable($this->faker->dateTime)
            );
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
