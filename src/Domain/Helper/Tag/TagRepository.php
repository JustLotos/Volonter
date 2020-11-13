<?php

declare(strict_types=1);

namespace App\Domain\Helper\Tag;

use App\Domain\Helper\Tag\Entity\Tag;
use App\Domain\Helper\Tag\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Types\Id as VolunteerId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TagRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $manager;
    /** @var EntityRepository */
    private $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Tag::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(Tag::class);
    }

    public function getById(Id $id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function add(Tag $tag)
    {
        $this->manager->persist($tag);
    }

    public function getAll(VolunteerId $id)
    {
        return $this->repository->findBy(['volunteer' => $id]);
    }

    public function remove(Tag $tag)
    {
        $this->manager->remove($tag);
    }
}
