<?php

declare(strict_types=1);

namespace App\Domain\Flash\Learner\Entity\Types;

use App\Domain\Flash\Service\AnswerMangerService\Models\ISettings;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DateInterval;
use JMS\Serializer\Annotation as Serializer;

/** @ORM\Embeddable */
class Session
{
    /**
     * @var array
     * @ORM\Column(type="json")
     * @Serializer\Groups({App\Domain\Flash\Entity\Learner\Learner::GROUP_DETAILS})
     */
    private $intervals;

    public const DEFAULT_PERIOD = 86400;
    public const DEFAULT_DURATION = 600;

    public const DEFAULT_VISIT = 3;

    private const DAYS_IN_WEEK = 7;
    private const DAYS_IN_MONTH = 28;

    public const WEEK =  'WEEK';
    public const MONTH = 'MONTH';

    public function __construct(
        array $intervals = [],
        string $period = self::WEEK
    ) {
        if (empty($intervals)) {
            $intervalsCount = round($this->getPeriodDuration($period) / self::DEFAULT_DURATION);

            for ($i=0; $i<$intervalsCount; $i++) {
                $intervals[] = [
                    'TIME' => $i * self::DEFAULT_DURATION,
                    'VISIT' => self::DEFAULT_VISIT
                ];
            }
        }

        $this->intervals = $intervals;
    }

    public function getIntervals(): array
    {
        return $this->intervals;
    }

    public function updateIntervalSchedule(int $interval)
    {
        foreach ($this->intervals as &$int) {
            if ($int['TIME'] === $interval) {
                $int['VISIT']++;
                break;
            }
        }
    }

    public function getPeriodDuration(string $period): int
    {
        switch ($period) {
            case self::MONTH:
                return self::DAYS_IN_MONTH * self::DEFAULT_PERIOD;
            default:
                return self::DAYS_IN_WEEK * self::DEFAULT_PERIOD;
        }
    }
}
