<?php

namespace ModuleDevisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devis
 *
 * @ORM\Table(name="devis")
 * @ORM\Entity(repositoryClass="ModuleDevisBundle\Repository\DevisRepository")
 */
class Devis
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
     * @var \DateTime
     *
     * @ORM\Column(name="datedevis", type="date")
     */
    private $datedevis;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datevalidite", type="date")
     */
    private $datevalidite;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var float
     *
     * @ORM\Column(name="prixunit", type="float")
     */
    private $prixunit;

    /**
     * @var int
     *
     * @ORM\Column(name="qte", type="integer")
     */
    private $qte;

    /**
     * @var float
     *
     * @ORM\Column(name="totalHT", type="float")
     */
    private $totalHT;

    /**
     * @var int
     *
     * @ORM\Column(name="TVA", type="integer")
     */
    private $tVA;

    /**
     * @var float
     *
     * @ORM\Column(name="totalTTC", type="float")
     */
    private $totalTTC;


    /**
     * @var int
     *
     * @ORM\OneToOne(targetEntity="offre")
     * @ORM\JoinColumn(name="offre_id", referencedColumnName="id")
     *
    */
    private $offre;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmer", type="string", length=255)
     */
    private $confirmer;

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
     * Set datedevis
     *
     * @param \DateTime $datedevis
     *
     * @return Devis
     */
    public function setDatedevis($datedevis)
    {
        $this->datedevis = $datedevis;

        return $this;
    }

    /**
     * Get datedevis
     *
     * @return \DateTime
     */
    public function getDatedevis()
    {
        return $this->datedevis;
    }

    /**
     * Set datevalidite
     *
     * @param \DateTime $datevalidite
     *
     * @return Devis
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
     * Set description
     *
     * @param string $description
     *
     * @return Devis
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Devis
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set prixunit
     *
     * @param float $prixunit
     *
     * @return Devis
     */
    public function setPrixunit($prixunit)
    {
        $this->prixunit = $prixunit;

        return $this;
    }

    /**
     * Get prixunit
     *
     * @return float
     */
    public function getPrixunit()
    {
        return $this->prixunit;
    }

    /**
     * Set qte
     *
     * @param integer $qte
     *
     * @return Devis
     */
    public function setQte($qte)
    {
        $this->qte = $qte;

        return $this;
    }

    /**
     * Get qte
     *
     * @return int
     */
    public function getQte()
    {
        return $this->qte;
    }

    /**
     * Set totalHT
     *
     * @param float $totalHT
     *
     * @return Devis
     */
    public function setTotalHT($totalHT)
    {
        $this->totalHT = $totalHT;

        return $this;
    }

    /**
     * Get totalHT
     *
     * @return float
     */
    public function getTotalHT()
    {
        return $this->totalHT;
    }

    /**
     * Set tVA
     *
     * @param integer $tVA
     *
     * @return Devis
     */
    public function setTVA($tVA)
    {
        $this->tVA = $tVA;

        return $this;
    }

    /**
     * Get tVA
     *
     * @return int
     */
    public function getTVA()
    {
        return $this->tVA;
    }

    /**
     * Set totalTTC
     *
     * @param float $totalTTC
     *
     * @return Devis
     */
    public function setTotalTTC($totalTTC)
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    /**
     * Get totalTTC
     *
     * @return float
     */
    public function getTotalTTC()
    {
        return $this->totalTTC;
    }

    /**
     * @return mixed
     */
    public function getOffre()
    {
        return $this->offre;
    }

    /**
     * @param mixed $offre
     */
    public function setOffre($offre)
    {
        $this->offre = $offre;
    }



    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getConfirmer()
    {
        return $this->confirmer;
    }

    /**
     * @param string $confirmer
     */
    public function setConfirmer($confirmer)
    {
        $this->confirmer = $confirmer;
    }



}

