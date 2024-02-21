<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Specialization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Specialization>
 *
 * @method Specialization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specialization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specialization[]    findAll()
 * @method Specialization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecializationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specialization::class);
    }
}
