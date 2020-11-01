<?php

declare(strict_types=1);

namespace App\Domain\Helper\Comment;

use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Types\Id as VolunteerId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $manager;
    /** @var EntityRepository */
    private $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Task::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Task::class);
    }

    public function getById(Id $id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function add(Task $task)
    {
        $this->manager->persist($task);
    }

    public function getAll(VolunteerId $id) {
        return $this->repository->findBy(['volunteer' => $id]);
    }

    public function remove(Task $task) {
        $this->manager->remove($task);
    }
}
