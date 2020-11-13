<?php

declare(strict_types=1);

namespace App\Domain\Helper\Tag\Entity;

use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Tag\Entity\Types\Id;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="helper_tags", uniqueConstraints={ @ORM\UniqueConstraint(columns={"name"}) })
 */
class Tag
{
    /**
     * @var Id
     * @ORM\Id
     * @ORM\Column(type="helper_tag_id")
     * @Serializer\Groups({Tag::GROUP_SIMPLE})
     * @Serializer\Type(name="string")
     */
    private $id;

    /**
     * @Serializer\Type(name="string")
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({Tag::GROUP_SIMPLE})
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(
     *     targetEntity="App\Domain\Helper\Task\Entity\Task",
     *     inversedBy="tags",
     *     cascade={"persist"}
     * )
     * @ORM\JoinTable(
     *     name="tag_task",
     *     joinColumns={ @ORM\JoinColumn(name="tag_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="task_id", referencedColumnName="id") }
     * )
     */
    private $tasks;

    public const GROUP_SIMPLE   = 'GROUP_SIMPLE';
    public const GROUP_SETTINGS = 'GROUP_SETTINGS';
    public const GROUP_DETAILS  = 'GROUP_DETAILS';

    public function __construct(Id $id, string $name, array $tasks = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->tasks = new ArrayCollection();

        array_map(function (Task $task) {
            $this->addTask($task);
        }, $tasks);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addTask(Task $task)
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->addTag($this);
        }
    }

    public function removeTask(Task $task)
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->add($task);
        }
    }

    public function getId(): Id
    {
        return $this->id;
    }
}
