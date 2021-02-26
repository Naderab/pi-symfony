<?php

namespace PointCollectBundle\Repository;

/**
 * MarkersRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MarkersRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param string $titre
     *
     * @return array
     */
    public function findLike($titre)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.address LIKE :address')
            ->setParameter( 'address', "%$titre%")
            ->orderBy('a.address')
            ->getQuery()
            ->execute()
            ;
    }

    public function findByType($type)
    {
        return $this->createQueryBuilder('or')
            ->select('or, pr')
            ->join('PointCollectBundle:TypePointCollect', 'pr', 'WITH', 'pr.type_point_collect_id = or.id')
            ->where('or.name = :name')
            ->setParameter('name', $type)
            ->getQuery()
            ->getResult();
    }

    public function findBySlug($variable)
    {
        $qb=$this->createQueryBuilder('s');
        $qb->where('s.slug=:slug')->setParameter('slug',$variable);
        return $qb->getQuery()->getSingleResult();
    }
}
