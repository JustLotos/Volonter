<?php

declare(strict_types=1);

namespace App\Domain\Helper\Task\Entity;

use App\Domain\Helper\Tag\Entity\Tag;
use App\Domain\Helper\Task\Entity\Types\Geo;
use App\Domain\Helper\Task\UseCase\Create\Command as CreateCommand;
use App\Domain\Helper\Task\UseCase\Update\Command;
use App\Domain\Helper\Task\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @Serializer\Type(name="string")
     * @ORM\Column(type="text", length=255)
     * @Serializer\Groups({Task::GROUP_SIMPLE})
     */
    private $body;

    /**
     * @var Geo | null
     * @ORM\Embedded(class="App\Domain\Helper\Task\Entity\Types\Geo", columnPrefix="geo_")
     * @Serializer\Groups({Task::GROUP_SIMPLE})
     */
    private $geo;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Task::GROUP_DETAILS})
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Task::GROUP_DETAILS})
     */
    private $updatedAt;

    /**
     * @var Volunteer
     * @ORM\ManyToOne(targetEntity="App\Domain\Helper\Volunteer\Entity\Volunteer", inversedBy="tasks")
     * @ORM\JoinColumn(name="volunteer_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Serializer\Groups({Task::GROUP_DETAILS})
     */
    private $volunteer;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Helper\Comment\Entity\Comment",
     *     mappedBy="task",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @Serializer\Groups({Task::GROUP_DETAILS})
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Domain\Helper\Tag\Entity\Tag", mappedBy="tasks", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="tag_task",
     *     joinColumns={ @ORM\JoinColumn(name="task_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="tag_id", referencedColumnName="id") }
     * )
     * @Serializer\Groups({Task::GROUP_DETAILS})
     */
    private $tags;


    public const GROUP_SIMPLE   = 'GROUP_SIMPLE';
    public const GROUP_SETTINGS = 'GROUP_SETTINGS';
    public const GROUP_DETAILS  = 'GROUP_DETAILS';

    public function __construct(Volunteer $volunteer, Id $id, CreateCommand $command, DateTimeImmutable $createdAt)
    {
        $this->volunteer = $volunteer;
        $this->id = $id;
        $this->title = $command->title;
        $this->body = $command->body;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->geo = $command->geo;
        $this->tags = new ArrayCollection();
    }

    public function getVolunteer() : Volunteer
    {
        return $this->volunteer;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getGeo(): Geo
    {
        return $this->geo;
    }

    public function update(Command $command): self
    {
        $this->title = $command->title;
        $this->body = $command->title;
        $this->updatedAt = new DateTimeImmutable('now');
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function addTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addTask($this);
        }
    }

    public function removeTag(Tag $tag)
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }
}
