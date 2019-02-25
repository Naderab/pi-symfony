<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wishlist
 *
 * @ORM\Table(name="wishlist")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\WishlistRepository")
 */
class Wishlist
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
}

