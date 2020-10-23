<?php

declare(strict_types=1);

namespace App\Domain\Flash\Learner\Entity\Types;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/** @ORM\Embeddable */
class Settings
{
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({App\Domain\Flash\Entity\Learner\Learner::GROUP_DETAILS})
     */
    private $individualPlan;

    public function __construct(bool $individualPlan)
    {
        $this->individualPlan = $individualPlan;
    }

    public function turnOnIndividualPlan()
    {
        $this->individualPlan = true;
    }

    public function switchOffIndividualPlan()
    {
        $this->individualPlan = false;
    }
}
