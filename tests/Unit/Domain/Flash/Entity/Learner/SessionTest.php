<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Flash\Entity\Learner;

use App\Domain\Helper\Volunteer\Entity\Types\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function testCreate()
    {
        $session = new Session();

        self::assertIsArray($session->getIntervals());
        self::assertGreaterThan(0, count($session->getIntervals()));

        for ($i=0; $i<count($session->getIntervals()); $i++) {
            $interval = $session->getIntervals()[$i];

            self::assertArrayHasKey('TIME', $interval);
            self::assertArrayHasKey('VISIT', $interval);
            self::assertEquals($i*Session::DEFAULT_DURATION, $interval['TIME']);
            self::assertEquals(Session::DEFAULT_VISIT, $interval['VISIT']);
        }
    }

    public function testUpdate()
    {
        $session = new Session();
        $session->updateIntervalSchedule(600);
        self::assertEquals(4, $session->getIntervals()[1]['VISIT']);
    }
}
