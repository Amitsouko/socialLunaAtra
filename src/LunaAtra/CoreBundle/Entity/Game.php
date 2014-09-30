<?php

namespace LunaAtra\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LunaAtra\CoreBundle\Entity\GameRepository")
 */
class Game
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="LunaAtra\ProfileBundle\Entity\Charact", mappedBy="game")
     * @var type
     */
    protected $characters;

    /**
     * @ORM\OneToMany(targetEntity="LunaAtra\ProfileBundle\Entity\Blog", mappedBy="game")
     * @var type
     */
    protected $blogs;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Game
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->characters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add characters
     *
     * @param \LunaAtra\PRofileBundle\Entity\Charact $characters
     * @return Game
     */
    public function addCharacter(\LunaAtra\PRofileBundle\Entity\Charact $characters)
    {
        $this->characters[] = $characters;

        return $this;
    }

    /**
     * Remove characters
     *
     * @param \LunaAtra\PRofileBundle\Entity\Charact $characters
     */
    public function removeCharacter(\LunaAtra\PRofileBundle\Entity\Charact $characters)
    {
        $this->characters->removeElement($characters);
    }

    /**
     * Get characters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * Add blogs
     *
     * @param \LunaAtra\ProfileBundle\Entity\Blog $blogs
     * @return Game
     */
    public function addBlog(\LunaAtra\ProfileBundle\Entity\Blog $blogs)
    {
        $this->blogs[] = $blogs;

        return $this;
    }

    /**
     * Remove blogs
     *
     * @param \LunaAtra\ProfileBundle\Entity\Blog $blogs
     */
    public function removeBlog(\LunaAtra\ProfileBundle\Entity\Blog $blogs)
    {
        $this->blogs->removeElement($blogs);
    }

    /**
     * Get blogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlogs()
    {
        return $this->blogs;
    }
}
