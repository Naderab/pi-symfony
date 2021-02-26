<?php

namespace ModuleDevisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * offre
 *
 * @ORM\Table(name="offre")
 * @ORM\Entity(repositoryClass="ModuleDevisBundle\Repository\offreRepository")
 */
class offre
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateoffre", type="date")
     */
    private $dateoffre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datevalidite", type="date")
     */
    private $datevalidite;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;


    /**
     * @var string
     *
     * @ORM\Column(name="demande", type="string", length=255)
     */
    private $demande;

    /**
     * @var string
     *
     * @ORM\Column(name="archiver", type="string", length=255)
     */
    private $archiver;

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
     * @return offre
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
     * Set type
     *
     * @param string $type
     *
     * @return offre
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return offre
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
     * Set dateoffre
     *
     * @param \DateTime $dateoffre
     *
     * @return offre
     */
    public function setDateoffre($dateoffre)
    {
        $this->dateoffre = $dateoffre;

        return $this;
    }

    /**
     * Get dateoffre
     *
     * @return \DateTime
     */
    public function getDateoffre()
    {
        return $this->dateoffre;
    }

    /**
     * Set datevalidite
     *
     * @param \DateTime $datevalidite
     *
     * @return offre
     */
    public function setDatevalidite($datevalidite)
    {
        $this->datevalidite = $datevalidite;

        return $this;
    }

    /**
     * Get datevalidite
     *
     * @return \DateTime
     */
    public function getDatevalidite()
    {
        return $this->datevalidite;
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
     * @return string
     */
    public function getDemande()
    {
        return $this->demande;
    }

    /**
     * @param string $demande
     */
    public function setDemande($demande)
    {
        $this->demande = $demande;
    }

    /**
     * @return string
     */
    public function getArchiver()
    {
        return $this->archiver;
    }

    /**
     * @param string $archiver
     */
    public function setArchiver($archiver)
    {
        $this->archiver = $archiver;
    }



}

