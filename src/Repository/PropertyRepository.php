<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 *
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function add(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getChosenPropertiesCreatedByManagers()
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join("p.creator", "c");
        return $qb->andWhere("LOWER(p.status) = 'rented'")
            ->andWhere("LOWER(p.city) = 'zurich'")
            ->andWhere("p.numberOfRooms BETWEEN 3 and 5")
            ->andWhere("c.roles LIKE '%ROLE_MANAGER%'")
            ->getQuery()
            ->getResult();
    }

    public function getChosenPropertyNamesCreatedByAdmins()
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.creator', 'c');
        $qb->join('p.translations', 't');

        $qb->select('p.name')->andWhere("LOWER(p.status) = 'active'")
            ->andWhere("LOWER(p.city) = 'zurich'")
            ->andWhere("c.roles LIKE '%ROLE_ADMIN%'")
            ->andWhere("t.locale IN ('de', 'en')");

        return $qb->groupBy('p.name')
            ->having('COUNT(p.id) = 2')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Property[] Returns an array of Property objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Property
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
