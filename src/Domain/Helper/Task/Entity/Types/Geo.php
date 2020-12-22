<?php

declare(strict_types=1);

namespace App\Domain\Helper\Task\Entity\Types;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Geo
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $x;

    /**
     * @ORM\Column(type="string", nullable=true)
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