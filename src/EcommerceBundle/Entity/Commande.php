<?php

namespace EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="EcommerceBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @Assert\GreaterThan(value="0",message="La quantité ne peut pas etre négative ")
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;



    /**
     * @var int
     *
     *@Assert\GreaterThan(value="0",message="La prixTotal ne peut pas etre négative ")
     * @ORM\Column(name="prix_total", type="integer")
     */
    private $prixTotal;

    /**
     * @var int
     *
     *@Assert\GreaterThan(value="0",message="La prixCommande ne peut pas etre négative ")
     * @ORM\Column(name="prix_commande", type="integer")
     */
    private $prixCommande;

    /**
     * @ORM\OneToMany(targetEntity="EcommerceBundle\Entity\Produit", mappedBy="idCommande")
     */
    private $products;


    /**
     * @ORM\ManyToOne(targetEntity="EcommerceBundle\Entity\Panier")
     * @ORM\JoinColumn(name="id_panier", referencedColumnName="id" ,nullable=true, onDelete="CASCADE")
     */
    private $idPanier;

    /**
     * @return mixed
     */
    public function getIdPanier()
    {
        return $this->idPanier;
    }

    /**
     * @param mixed $idPanier
     */
    public function setIdPanier($idPanier)
    {
        $this->idPanier = $idPanier;
    }
    /**
     * @var \DateTime
     * @ORM\Column(name="dateCommande", type="date")
     */
    private $dateCommande;
    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;



    public function addQuantite($quantite)
    {
        $this->quantite += $quantite;
    }


    /**
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param string $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }


    /**
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * @param \DateTime $dateCommande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;
    }




    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $idClient;


    /**
     * @return mixed
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * @param mixed $idClient
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;
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
     * Set quantite
     *
     * @param string $quantite
     *
     * @return Commande
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return string
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prixTotal
     *
     * @param int $prixTotal
     *
     * @return Commande
     */
    public function setPrixTotal($prixTotal)
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    /**
     * Get prixTotal
     *
     * @return int
     */
    public function getPrixTotal()
    {
        return $this->prixTotal;
    }

    /**
     * Set prixCommande
     *
     * @param int $prixCommande
     *
     * @return Commande
     */
    public function setPrixCommande($prixCommande)
    {
        $this->prixCommande = $prixCommande;

        return $this;
    }

    /**
     * Get prixCommande
     *
     * @return int
     */
    public function getPrixCommande()
    {
        return $this->prixCommande;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \EcommerceBundle\Entity\Produit $product
     *
     * @return Commande
     */
    public function addProduct(\EcommerceBundle\Entity\Produit $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \EcommerceBundle\Entity\Produit $product
     */
    public function removeProduct(\EcommerceBundle\Entity\Produit $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}