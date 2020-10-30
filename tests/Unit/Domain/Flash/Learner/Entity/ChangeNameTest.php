<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Flash\Learner\Entity;

use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Types\Name;
use PHPUnit\Framework\TestCase;
use App\Domain\Helper\Task\Entity\Volunteer;

class ChangeNameTest extends TestCase
{
    public function testChangeName(): void
    {
        $learner = Volunteer::create(Id::next());
        $learner->changeName(new Name('test', 'test'));

        self::assertEquals('test', $learner->getName()->getFirst());
        self::assertEquals('test', $learner->getName()->getLast());
        self::assertEquals('test test', $learner->getName()->getFull());
    }
}
