<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\PostRepository")
 */
class Post
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
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;

    /**
     * @var integer
     *
     * @ORM\Column(name="publier", type="integer", length=255)
     */
    private $publier;

    /**
     * @var integer
     *
     * @ORM\Column(name="nombrevue", type="integer", length=255)
     */
    private $nombrevue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datepost", type="datetime", length=255)
     */
    private $datepost;


    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     *
     * @ORM\ManyToOne(targetEntity="CategorieBlog")
     * @ORM\JoinColumn(name="CategorieBlog_id",referencedColumnName="id")
     */
    private $CategorieBlog;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"id"})
     * @ORM\Column(length=255, unique=true,nullable=false)
     */
    protected $slug;

    /**
     * @var integer
     *
     * @ORM\Column(name="nombrelike", type="integer", length=255)
     */
    private $nombrelike;

    /**
     * @return int
     */
    public function getNombrelike()
    {
        return $this->nombrelike;
    }

    /**
     * @param int $nombrelike
     */
    public function setNombrelike($nombrelike)
    {
        $this->nombrelike = $nombrelike;
    }


    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getCategorieBlog()
    {
        return $this->CategorieBlog;
    }

    /**
     * @param mixed $CategorieBlog
     */
    public function setCategorieBlog($CategorieBlog)
    {
        $this->CategorieBlog = $CategorieBlog;
    }

    /**
     * @return int
     */
    public function getPublier()
    {
        return $this->publier;
    }

    /**
     * @param int $publier
     */
    public function setPublier($publier)
    {
        $this->publier = $publier;
    }

    /**
     * @return int
     */
    public function getNombrevue()
    {
        return $this->nombrevue;
    }

    /**
     * @param int $nombrevue
     */
    public function setNombrevue($nombrevue)
    {
        $this->nombrevue = $nombrevue;
    }

    /**
     * @return \DateTime
     */
    public function getDatepost()
    {
        return $this->datepost;
    }

    /**
     * @param \DateTime $datepost
     */
    public function setDatepost($datepost)
    {
        $this->datepost = $datepost;
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
     * @return Post
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
     * @return Post
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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Post
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }



}

