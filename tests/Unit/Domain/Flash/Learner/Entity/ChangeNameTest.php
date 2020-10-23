<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Flash\Learner\Entity;

use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Domain\Flash\Learner\Entity\Types\Name;
use PHPUnit\Framework\TestCase;
use App\Domain\Flash\Learner\Entity\Learner;

class ChangeNameTest extends TestCase
{
    public function testChangeName(): void
    {
        $learner = Learner::create(Id::next());
        $learner->changeName(new Name('test', 'test'));

        self::assertEquals('test', $learner->getName()->getFirst());
        self::assertEquals('test', $learner->getName()->getLast());
        self::assertEquals('test test', $learner->getName()->getFull());
    }
}
