<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\BaseFixture;
use App\Domain\Flash\Entity\Deck\Deck;
use App\Domain\Flash\Entity\Deck\Types\Settings;
use App\Domain\Flash\Learner\Entity\Learner;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DeckFixture extends BaseFixture implements DependentFixtureInterface
{
    public const ADMIN_DECKS = 'ADMIN_DECKS';
    public const USER_DECKS = 'USER_DECKS';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(1, self::ADMIN_DECKS, function () {
            /** @var Learner $learner */
            $learner = $this->getRandomReference(LearnerFixtures::ADMIN_LEARNERS);
            return $this->getBaseDeck($learner);
        });

        $this->createMany(2, self::USER_DECKS, function () {
            /** @var Learner $learner */
            $learner = $this->getRandomReference(LearnerFixtures::USER_LEARNERS);
            return $this->getBaseDeck($learner);
        });
        $manager->flush();
    }

    private function getBaseDeck(Learner $learner): Deck
    {
        $settings = new Settings(
            3600,
            60,
            $this->faker->numberBetween(10, 40),
            $this->faker->numberBetween(10, 40),
            $this->faker->randomFloat(2, 0.5, 4)
        );

        return new Deck($learner, $this->faker->name(), $settings, new DateTimeImmutable(), $this->faker->realText());
    }

    public function getDependencies()
    {
        return [LearnerFixtures::class];
    }
}
