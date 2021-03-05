<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit2
 *
 * @ORM\Table(name="produit2")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\Produit2Repository")
 */
class Produit2
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
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Categorie",inversedBy="produit")
     * @ORM\JoinColumn(name="id_categorie",referencedColumnName="id")
     *
     */
    private $categorie;
    /**
     * @var int
     *
     * @ORM\Column(name="publie", type="integer")
     *
     */
    private $publie;
    /**
     * @var integer
     *
     * @ORM\Column(name="prix", type="integer", length=255)
     */
    private $Prix;
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Ajouter une image jpg")
     * @Assert\File(maxSize="5242880",
     *      mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          "image/gif",
     *          "application/pdf",
     *          "application/x-pdf"})
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer" , nullable=true)
     */
    private $rating;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="SousCategorie")
     * @ORM\JoinColumn(name="id_SousCategorie",referencedColumnName="id")
     *
     */
    private $SousCategorie;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user",referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private $OwnerUser;


    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getPrix()
    {
        return $this->Prix;
    }

    /**
     * @param int $Prix
     */
    public function setPrix($Prix)
    {
        $this->Prix = $Prix;
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Produit
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Produit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param int $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * @return int
     */
    public function getSousCategorie()
    {
        return $this->SousCategorie;
    }

    /**
     * @param int $SousCategorie
     */
    public function setSousCategorie($SousCategorie)
    {
        $this->SousCategorie = $SousCategorie;
    }

    /**
     * @return int
     */
    public function getPublie()
    {
        return $this->publie;
    }

    /**
     * @param int $publie
     */
    public function setPublie($publie)
    {
        $this->publie = $publie;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getOwnerUser()
    {
        return $this->OwnerUser;
    }

    /**
     * @param int $OwnerUser
     */
    public function setOwnerUser($OwnerUser)
    {
        $this->OwnerUser = $OwnerUser;
    }




}

