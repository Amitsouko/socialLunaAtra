<?php

namespace LunaAtra\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;
/**
 * ProfileCover
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ProfileCover
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @Assert\File(maxSize="4000000", mimeTypes = {"image/jpeg", "image/png", "image/gif"}, mimeTypesMessage = "Please upload a valid image")
     */
    public $file;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255,nullable=true)
     */
    private $name;


    /**
     * @ORM\ManyToOne(targetEntity="LunaAtra\ProfileBundle\Entity\User", inversedBy="cover",cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    /**
     * @var date $lastImageUpdate
     *
     * @ORM\Column(name="lastImageUpdate", type="string", nullable=true)
     */
    private $lastImageUpdate;


    public function __construct()
    {
        $this->user = new ArrayCollection();
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    public function get($name)
    {
        return  isset($this->$name) ? $this->$name : null;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {   $lastDigit = substr($this->id, -1);
        $facticeImage = 'bundles/core/images/background-home/'. rand(0,3) .'.jpg';
        return null === $this->path ? $facticeImage  : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/coverImages';
    }


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
     * @return ProfileCover
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
     * Set path
     *
     * @param string $path
     * @return ProfileCover
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set lastImageUpdate
     *
     * @param string $lastImageUpdate
     * @return ProfileCover
     */
    public function setLastImageUpdate($lastImageUpdate)
    {
        $this->lastImageUpdate = $lastImageUpdate;

        return $this;
    }

    /**
     * Get lastImageUpdate
     *
     * @return string 
     */
    public function getLastImageUpdate()
    {
        return $this->lastImageUpdate;
    }

    /**
     * Set user
     *
     * @param \LunaAtra\ProfileBundle\Entity\User $user
     * @return ProfileCover
     */
    public function setUser(\LunaAtra\ProfileBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \LunaAtra\ProfileBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
