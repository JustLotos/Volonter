<?php

declare(strict_types=1);

namespace App\Domain\Helper\Task\Entity\Types;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Embeddable()
 */
class Geo
{
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type(name="string")
     * @Serializer\Groups({App\Domain\Helper\Task\Entity\Task::GROUP_SIMPLE})
     */
    private $x;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type(name="string")
     * @Serializer\Groups({App\Domain\Helper\Task\Entity\Task::GROUP_SIMPLE})
     */
    private $y;

    public function __construct(string $x, string $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): string
    {
        return $this->x;
    }

    public function getY(): string
    {
        return $this->y;
    }
}