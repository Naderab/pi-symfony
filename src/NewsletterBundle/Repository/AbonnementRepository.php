<?php

namespace NewsletterBundle\Repository;

/**
 * AbonnementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AbonnementRepository extends \Doctrine\ORM\EntityRepository
{
    public function findemail()
    {
        $query = $this->getEntityManager()
            ->createQuery("SELECT email FROM NewsletterBundle:Abonnement email");

        return $query->getResult();
    }

}
