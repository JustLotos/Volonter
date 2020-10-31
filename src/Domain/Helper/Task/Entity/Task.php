<?php

declare(strict_types=1);

namespace App\Domain\Helper\Task\Entity;

use App\Domain\Helper\Task\UseCase\Update\Command;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="helper_tasks")
 */
class Task
{
    /**
     * @var Id
     * @ORM\Id
     * @ORM\Column(type="helper_task_id")
     * @Serializer\Groups({Task::GROUP_SIMPLE})
     * @Serializer\Type(name="string")
     */
    private $id;

    /**
     * @Serializer\Type(name="string")
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({Task::GROUP_SIMPLE})
     */
    private $title;

    /**
     * @var Volunteer
     * @ORM\ManyToOne(targetEntity="App\Domain\Helper\Volunteer\Entity\Volunteer", inversedBy="tasks")
     * @ORM\JoinColumn(name="volunteer_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $volunteer;


    public const GROUP_SIMPLE   = 'GROUP_SIMPLE';
    public const GROUP_SETTINGS = 'GROUP_SETTINGS';
    public const GROUP_DETAILS  = 'GROUP_DETAILS';

    public function __construct(Volunteer $volunteer, Id $id, string $title)
    {
        $this->volunteer = $volunteer;
        $this->id = $id;
        $this->title = $title;
    }

    public function getVolunteer() : Volunteer
    {
        return $this->volunteer;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function update(Command $command): self {
        $this->title = $command->title;
        return $this;
    }
}
