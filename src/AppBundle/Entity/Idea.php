<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Idea
 *
 * @ORM\Table(name="idea")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IdeaRepository")
 */
class Idea
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
     * @Assert\NotBlank(message="veuillez renseigner un titre")
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message="veuillez renseigner un titre")
     * @ORM\Column(name="description", type="string", length=350)
     */
    private $description;

    /**
     * @var string
     * @Assert\NotBlank(message="veuillez renseigner un titre")
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isPublished", type="boolean")
     */
    private $isPublished;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="idea", cascade={"persist"})
     */
    private $categories;

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     * @return Idea
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="veuillez renseigner un titre")
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    public function  _construct(){
        $this->date = new \DateTime();
    }


    /*
     *  @Assert\Image(maxSize="6M", allowLandscape=false, allowLandscapeMessage="Image trop grande")
     */
    /**
     * @var
     * @Assert\NotBlank(message="veuillez renseigner un titre")
     *
     * @ORM\Column(name="path_image", type="string")
     */
    private $pathImage;

    /**
     * @return mixed
     */
    public function getPathImage()
    {
        return $this->pathImage;
    }

    /**
     * @param mixed $pathImage
     * @return Idea
     */
    public function setPathImage($pathImage)
    {
        $this->pathImage = $pathImage;
        return $this;
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
     * Set title
     *
     * @param string $title
     *
     * @return Idea
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param bool $isPublished
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}

