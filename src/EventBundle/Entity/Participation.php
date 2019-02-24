<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\ParticipationRepository")
 */
class Participation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPlace", type="integer")
     */
    private $nbPlace;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user",referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private $User;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Evenement")
     * @ORM\JoinColumn(name="id_event",referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private $Event;

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @param int $User
     */
    public function setUser($User)
    {
        $this->User = $User;
    }

    /**
     * @return int
     */
    public function getEvent()
    {
        return $this->Event;
    }

    /**
     * @param int $Event
     */
    public function setEvent($Event)
    {
        $this->Event = $Event;
    }






    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nbPlace
     *
     * @param integer $nbPlace
     *
     * @return Participation
     */
    public function setNbPlace($nbPlace)
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    /**
     * Get nbPlace
     *
     * @return int
     */
    public function getNbPlace()
    {
        return $this->nbPlace;
    }
}

