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


    public function updatepublie($id)
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

    public function updatenonpublie($id)
    {

        $qb = $this->_em->createQueryBuilder();
        $q = $qb->update('EventBundle:Evenement', 'p')
            ->set('p.publie', '?1')
            ->where('p.id = ?2')
            ->setParameter(1, "0")
            ->setParameter(2, $id)

            ->getQuery();
        return $q->getResult();
    }


    public function OrderByPrixAsc(){
        $currentdate = new \DateTime('now');

        $qb=$this->CreateQueryBuilder('s');
        $qb->where('s.dateFin>=:dateFin')
            ->setParameter('dateFin', $currentdate);
        $qb->orderBy('s.prix', 'ASC');
        return $qb->getQuery()->getResult();


    }

    public function findByPublie()
    {
        $currentdate = new \DateTime('now');

        $qb = $this->createQueryBuilder('s');
        $qb->where('s.publie=:pub')
            ->andWhere('s.dateFin>=:dateFin')

            ->setParameter('pub', 1)
            ->setParameter('dateFin', $currentdate);

        return $qb->getQuery()->getResult();

    }

    public function OrderByPrixDesc(){
        $currentdate = new \DateTime('now');

        $qb=$this->CreateQueryBuilder('s');
        $qb->where('s.dateFin>=:dateFin')
            ->setParameter('dateFin', $currentdate);

        $qb->orderBy('s.prix', 'DESC');
        return $qb->getQuery()->getResult();


    }

    public function OrderByDate(){
        $currentdate = new \DateTime('now');

        $qb=$this->CreateQueryBuilder('s');
        $qb->where('s.dateFin>=:dateFin')
            ->setParameter('dateFin', $currentdate);

        $qb->orderBy('s.dateDebut', 'DESC');
        return $qb->getQuery()->getResult();


    }

    public function OrderByMeilleur(){
        $currentdate = new \DateTime('now');

        $qb=$this->CreateQueryBuilder('s');
        $qb->where('s.dateFin>=:dateFin')
            ->setParameter('dateFin', $currentdate);

        $qb->orderBy('s.nombrevu', 'DESC');
        return $qb->getQuery()->getResult();


    }

    public function findCat($cat)
    {
        $query=$this->getEntityManager()
            ->createQuery("SELECT m FROM EventBundle:Evenement m where m.categorie=:catt");
        $query->setParameter('catt',$cat);

        return $query->getResult();
    }

    public function findBySlug($id)
    {
        $qb=$this->createQueryBuilder('s');
        $qb->where('s.slug=:slug')->setParameter('slug',$id);
        return $qb->getQuery()->getSingleResult();
    }
}
