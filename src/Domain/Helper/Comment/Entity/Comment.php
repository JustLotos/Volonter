<?php

declare(strict_types=1);

namespace App\Domain\Helper\Comment\Entity;

use App\Domain\Helper\Comment\UseCase\Update\Command;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Comment\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="helper_comments")
 */
class Comment
{
    /**
     * @var Id
     * @ORM\Id
     * @ORM\Column(type="helper_comment_id")
     * @Serializer\Groups({Comment::GROUP_SIMPLE})
     * @Serializer\Type(name="string")
     */
    private $id;

    /**
     * @Serializer\Type(name="string")
     * @ORM\Column(type="text", length=255)
     * @Serializer\Groups({Comment::GROUP_SIMPLE})
     */
    private $text;

    /**
     * @var Volunteer
     * @ORM\ManyToOne(targetEntity="App\Domain\Helper\Volunteer\Entity\Volunteer", inversedBy="tasks")
     * @ORM\JoinColumn(name="volunteer_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Serializer\Groups({Comment::GROUP_SIMPLE})
     */
    private $volunteer;

    /**
     * @var Task
     * @ORM\ManyToOne(targetEntity="App\Domain\Helper\Task\Entity\Task", inversedBy="tasks")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $task;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Comment::GROUP_DETAILS})
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Comment::GROUP_SIMPLE})
     */
    private $updatedAt;


    public const GROUP_SIMPLE   = 'GROUP_SIMPLE';
    public const GROUP_SETTINGS = 'GROUP_SETTINGS';
    public const GROUP_DETAILS  = 'GROUP_DETAILS';

    public function __construct(Volunteer $volunteer, Id $id, Task $task, string $text, DateTimeImmutable $createdAt)
    {
        $this->volunteer = $volunteer;
        $this->id = $id;
        $this->text = $text;
        $this->task =$task;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
    }

    public function getVolunteer() : Volunteer
    {
        return $this->volunteer;
    }

    public function getTask(): Task
    {
        return  $this->task;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function update(Command $command): self
    {
        $this->text = $command->text;
        $this->updatedAt = new DateTimeImmutable('now');
        return $this;
    }
}
