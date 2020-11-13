<?php

declare(strict_types=1);

namespace App\DataFixtures\Helper;

use App\Domain\Helper\Tag\Entity\Tag;
use App\Domain\Helper\Tag\Entity\Types\Id;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;
use App\Domain\Helper\Task\Entity\Task;

class TagFixtures extends BaseFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    public const ADMINS = 'ADMIN_HELPER_TAG';
    public const ADMIN_TAG_COUNT = 10;
    public const USERS = 'USER_HELPER_TAG';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(self::ADMIN_TAG_COUNT, self::ADMINS, function ($i) {
            /** @var Task $task */
            $task = $this->getRandomReference(TaskFixtures::ADMINS);
            $tag = new Tag(Id::next(), 'tag'.$i);
            $tag->addTask($task);
            return $tag;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ TaskFixtures::class ];
    }
}
