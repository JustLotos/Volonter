<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Flash\Learner\Entity;

use App\Domain\Helper\Task\Entity\Volunteer;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testCreate(): void {
        $id = Id::next();

        $learner = Volunteer::create($id);

        self::assertEquals($id->getValue(), $learner->getId()->getValue());
        self::assertEquals('Not specified', $learner->getName()->getFirst());
        self::assertEquals('Not specified', $learner->getName()->getFirst());
        self::assertEquals('Not specified Not specified', $learner->getName()->getFull());
    }
}