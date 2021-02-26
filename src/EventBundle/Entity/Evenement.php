<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\EvenementRepository")
 */
class Evenement
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime")
     */
    private $dateFin;



    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     */
    private $categorie;



    /**
     * @var text
     *
     * @ORM\Column(name="adresse", type="text")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Ajouter une image jpg")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="min_participants", type="integer")
     */
    private $minParticipants;

    /**
     * @var int
     *
     * @ORM\Column(name="maxParticipants", type="integer")
     */
    private $maxParticipants;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float" ,nullable=true)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="nombretickets", type="integer"  ,nullable=true)
     */
    private $nombretickets;

    /**
     * @var int
     *
     * @ORM\Column(name="nombrevu", type="integer" ,nullable=true)
     */
    private $nombrevu;

    /**
     * @var int
     *
     * @ORM\Column(name="publie", type="integer" , nullable=true)
     */
    private $publie;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer" , nullable=true)
     */
    private $rating;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user",referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private $OwnerUser;



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



    /**
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Evenement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Evenement
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Evenement
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Evenement
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set minParticipants
     *
     * @param integer $minParticipants
     *
     * @return Evenement
     */
    public function setMinParticipants($minParticipants)
    {
        $this->minParticipants = $minParticipants;

        return $this;
    }

    /**
     * Get minParticipants
     *
     * @return int
     */
    public function getMinParticipants()
    {
        return $this->minParticipants;
    }

    /**
     * Set maxParticipants
     *
     * @param integer $maxParticipants
     *
     * @return Evenement
     */
    public function setMaxParticipants($maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * Get maxParticipants
     *
     * @return int
     */
    public function getMaxParticipants()
    {
        return $this->maxParticipants;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return int
     */
    public function getNombretickets()
    {
        return $this->nombretickets;
    }

    /**
     * @param int $nombretickets
     */
    public function setNombretickets($nombretickets)
    {
        $this->nombretickets = $nombretickets;
    }

    /**
     * @return int
     */
    public function getNombrevu()
    {
        return $this->nombrevu;
    }

    /**
     * @param int $nombrevu
     */
    public function setNombrevu($nombrevu)
    {
        $this->nombrevu = $nombrevu;
    }




}

