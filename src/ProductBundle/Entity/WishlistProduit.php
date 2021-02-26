<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WishlistProduit
 *
 * @ORM\Table(name="wishlist_produit")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\WishlistProduitRepository")
 */
class WishlistProduit
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
     * @ORM\ManyToOne(targetEntity="Produit2")
     * @ORM\JoinColumn(name="id_produit",referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private $Produit;

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
    public function getProduit()
    {
        return $this->Produit;
    }

    /**
     * @param int $Produit
     */
    public function setProduit($Produit)
    {
        $this->Produit = $Produit;
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

