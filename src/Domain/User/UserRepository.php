<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\Entity\Types\Id;
use App\Domain\User\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class UserRepository extends ServiceEntityRepository
{
    /** @var EntityManager */
    private $manager;
    /** @var EntityRepository */
    private $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, User::class);
        $this->manager = $em;
        $this->repository = $em->getRepository(User::class);
    }

    /** @return User|object|null*/
    public function findByConfirmToken(string $token): ?User
    {
        return $this->repository->createQueryBuilder('t')
            ->andWhere('t.confirmToken.token = :token')
            ->setParameter(':token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function get(Id $id): User
    {
        if (!$user = $this->repository->find($id->getValue())) {
            throw new EntityNotFoundException('User is not found.');
        }
        /** @var User $user */
        return $user;
    }
    public function getByEmail(string $email): User
    {
        if (!$user = $this->repository->findOneBy(['email' => $email])) {
            throw new EntityNotFoundException('User is not found.');
        }
        /** @var User $user */
        return $user;
    }
    public function hasByEmail(string $email): bool
    {
        return $this->repository->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email)
                ->getQuery()->getSingleScalarResult() > 0;
    }
    public function add(User $user): void
    {
        $this->manager->persist($user);
    }
}
