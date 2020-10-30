<?php

declare(strict_types=1);

namespace App\Domain\Helper\Volunteer;

use App\Domain\Helper\Volunteer\Entity\Volunteer;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class VolunteerRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $manager;
    /** @var EntityRepository */
    private $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Volunteer::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Volunteer::class);
    }

    public function getById(Id $id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function add(Volunteer $learner)
    {
        $this->manager->persist($learner);
    }
}
