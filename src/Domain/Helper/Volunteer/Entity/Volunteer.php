<?php

declare(strict_types=1);

namespace App\Domain\Helper\Volunteer\Entity;

use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Types\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="helper_volunteers")
 */
class Volunteer
{
    /**
     * @var Id
     * @ORM\Column(type="helper_volunteer_id")
     * @Serializer\Groups({Volunteer::GROUP_SIMPLE})
     * @Serializer\Type(name="App\Domain\Helper\Volunteer\Entity\Types\Id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var Name
     * @ORM\Embedded(class="App\Domain\Helper\Volunteer\Entity\Types\Name")
     * @Serializer\Type(name="App\Domain\Helper\Volunteer\Entity\Types\Name")
     * @Serializer\Groups({Volunteer::GROUP_SIMPLE})
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Helper\Task\Entity\Task",
     *     mappedBy="volunteer",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    private $tasks;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Domain\Helper\Comment\Entity\Comment", mappedBy="volunteer", orphanRemoval=true, cascade={"persist"})
     */
    private $comments;

    public const GROUP_SIMPLE   = 'GROUP_SIMPLE';
    public const GROUP_SETTINGS = 'GROUP_SETTINGS';
    public const GROUP_DETAILS  = 'GROUP_DETAILS';

    private function __construct(Id $id)
    {
        $this->id = $id;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): ?Name {
        return $this->name;
    }

    public static function create(Id $id, Name $name = null): self
    {
        $learner = new self($id);
        $learner->changeName($name ?: new Name());
        return $learner;
    }

    public function changeName(Name $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getTasks() {
        return $this->tasks->toArray();
    }

}
