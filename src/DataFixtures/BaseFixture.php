<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function count;
use function sprintf;
use function strpos;

abstract class BaseFixture extends Fixture
{
    protected $container;

    public function setContainer(
        ?ContainerInterface $container = null
    ) : void {
        $this->container = $container;
    }

    /** @var ObjectManager */
    private $manager;
    /** @var Generator */
    protected $faker;
    private $referencesIndex = [];

    abstract protected function loadData(ObjectManager $manager) : void;

    public function load(ObjectManager $manager) : void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData($manager);
    }

    /**
     * Create many objects at once:
     *
     *      $this->createMany(10, function(int $i) {
     *          $user = new User();
     *          $user->setFirstName('Ryan');
     *
     *           return $user;
     *      });
     *
     * @param string $groupName Tag these created objects with this group name,
     *                          and use this later with getRandomReference(s)
     *                          to fetch only from this specific group.
     */
    protected function createMany(int $count, string $groupName, callable $factory) : void
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = $factory($i);
            if ($entity === null) {
                throw new LogicException(
                    'Did you forget to return the entity object from your callback to BaseFixture::createMany()?'
                );
            }

            $this->manager->persist($entity);
            // store for usage later as groupName_#COUNT#
            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);
        }
    }

    protected function getRandomReference(string $groupName)
    {
        if (! isset($this->referencesIndex[$groupName])) {
            $this->referencesIndex[$groupName] = [];
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $groupName . '_') !== 0) {
                    continue;
                }

                $this->referencesIndex[$groupName][] = $key;
            }
        }

        if (empty($this->referencesIndex[$groupName])) {
            throw new InvalidArgumentException(
                sprintf('Did not find any references saved with the group name "%s"', $groupName)
            );
        }

        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$groupName]);

        return $this->getReference($randomReferenceKey);
    }

    protected function getRandomReferences(string $className, int $count)
    {
        $references = [];
        while (count($references) < $count) {
            $references[] = $this->getRandomReference($className);
        }

        return $references;
    }
}
