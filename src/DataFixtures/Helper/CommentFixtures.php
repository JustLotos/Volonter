<?php

declare(strict_types=1);

namespace App\DataFixtures\Helper;

use App\Domain\Helper\Comment\Entity\Comment;
use App\Domain\Helper\Volunteer\Entity\Types\Id as CommentId;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;
use App\Domain\Helper\Task\Entity\Task;

class CommentFixtures extends BaseFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    public const ADMIN = 'ADMIN_HELPER_COMMENT';
    public const ADMIN_COMMENT_COUNT = 40;
    public const USER = 'USER_HELPER_COMMENT';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(self::ADMIN_COMMENT_COUNT, self::ADMIN, function () {
            /** @var Volunteer $volunteer */
            $volunteer = $this->getRandomReference(VolunteerFixtures::ADMINS);
            /** @var Task $task */
            $task = $this->getRandomReference(TaskFixtures::ADMINS);

            return new Comment($volunteer, CommentId::next(), $task, $this->faker->text, new \DateTimeImmutable('now'));
        });

        $this->createMany(20, self::USER, function () {
            /** @var Volunteer $volunteer */
            $volunteer = $this->getRandomReference(VolunteerFixtures::USERS);
            /** @var Task $task */
            $task = $this->getRandomReference(TaskFixtures::USERS);

            return new Comment($volunteer, CommentId::next(), $task, $this->faker->text, new \DateTimeImmutable('now'));
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
