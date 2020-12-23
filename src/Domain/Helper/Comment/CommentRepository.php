<?php

declare(strict_types=1);

namespace App\Domain\Helper\Comment;

use App\Domain\Helper\Comment\Entity\Comment;
use App\Domain\Helper\Comment\Entity\Types\Id;
use App\Domain\Helper\Task\Entity\Types\Id as TaskId;
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
        parent::__construct($registry, Comment::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Comment::class);
    }

    public function getById(Id $id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function add(Comment $comment)
    {
        $this->manager->persist($comment);
    }

    public function getAllByTask(TaskId $id)
    {
        return $this->repository->findBy(['task' => $id, 'updatedAt' => 'ASC']);
    }

    public function remove(Comment $comment)
    {
        $this->manager->remove($comment);
    }
}
