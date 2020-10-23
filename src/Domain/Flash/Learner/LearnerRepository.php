<?php

declare(strict_types=1);

namespace App\Domain\Flash\Learner;

use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LearnerRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $manager;
    /** @var EntityRepository */
    private $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Learner::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Learner::class);
    }

    public function getById(Id $id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function add(Learner $learner)
    {
        $this->manager->persist($learner);
    }
}
