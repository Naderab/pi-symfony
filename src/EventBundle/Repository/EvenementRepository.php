<?php

namespace EventBundle\Repository;

/**
 * EvenementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EvenementRepository extends \Doctrine\ORM\EntityRepository
{
    public function update($id)
    {

        $qb = $this->_em->createQueryBuilder();
        $q = $qb->update('EventBundle:Evenement', 'p')
            ->set('p.publie', '?1')
            ->where('p.id = ?2')
            ->setParameter(1, "1")
            ->setParameter(2, $id)

            ->getQuery();
        return $q->getResult();
    }

    public function OrderByPrixAsc(){

        $qb=$this->CreateQueryBuilder('s');

        $qb->orderBy('s.prix', 'ASC');
        return $qb->getQuery()->getResult();


    }

    public function OrderByPrixDesc(){

        $qb=$this->CreateQueryBuilder('s');

        $qb->orderBy('s.prix', 'DESC');
        return $qb->getQuery()->getResult();


    }

    public function OrderByDate(){

        $qb=$this->CreateQueryBuilder('s');

        $qb->orderBy('s.dateDebut', 'DESC');
        return $qb->getQuery()->getResult();


    }

    public function OrderByMeilleur(){

        $qb=$this->CreateQueryBuilder('s');

        $qb->orderBy('s.nombrevu', 'DESC');
        return $qb->getQuery()->getResult();


    }
}