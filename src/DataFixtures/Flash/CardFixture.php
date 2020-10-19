<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\BaseFixture;
use App\Domain\Flash\Entity\Card\Card;
use App\Domain\Flash\Entity\Card\Types\Record;
use App\Domain\Flash\Entity\Card\Types\Repeat;
use App\Domain\Flash\Entity\Deck\Deck;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CardFixture extends BaseFixture implements DependentFixtureInterface
{
    public const ADMIN_CARDS = 'ADMIN_CARDS';
    public const USER_CARDS = 'USER_CARDS';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(40, self::ADMIN_CARDS, function () {
            /** @var Deck $deck */
            $deck = $this->getRandomReference(DeckFixture::ADMIN_DECKS);
            return $this->getCard($deck);
        });

        $this->createMany(2, self::USER_CARDS, function () {
            /** @var Deck $deck */
            $deck = $this->getRandomReference(DeckFixture::USER_DECKS);
            return $this->getCard($deck);
        });

        $manager->flush();
    }

    private function getCard(Deck $deck): Card
    {
        $front = Record::createFrontSide($this->faker->streetName);
        $back = Record::createBackSide($this->faker->firstNameFemale);
        $count = $this->faker->numberBetween(0, 20);
        $repeat = new Repeat(
            new DateTimeImmutable('-1 day'),
            $deck->getSettings()->getStartTimeInterval(),
            $count,
            $this->faker->numberBetween(0, $count)
        );

        return Card::create($deck, $this->faker->name, $front, $back, $repeat, new DateTimeImmutable());
    }
    public function getDependencies()
    {
        return [DeckFixture::class];
    }
}
