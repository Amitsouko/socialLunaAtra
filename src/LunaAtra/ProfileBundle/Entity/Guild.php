<?php

namespace LunaAtra\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;
/**
 * Guild
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LunaAtra\ProfileBundle\Entity\GuildRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Guild
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
     * @ORM\Column(name="tag", type="string", length=255)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="announce", type="string", length=255, nullable=true)
     */
    private $announce;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text",nullable=true)
     */
    private $bio;

    /**
     * @var boolean
     *
     * @ORM\Column(name="recruting", type="boolean")
     */
    private $recruting;

    /**
     * @var string
     *
     * @ORM\Column(name="recrutingAnnounce", type="text")
     */
    private $recrutingAnnounce;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="guilds")
     **/
    private $users;


    /**
     * @Assert\File(maxSize="1000000", mimeTypes = {"image/jpeg", "image/png", "image/gif"}, mimeTypesMessage = "Please upload a valid image")
     */
    public $file;

    /**
     * @var date $lastImageUpdate
     *
     * @ORM\Column(name="lastImageUpdate", type="string", nullable=true)
     */
    private $lastImageUpdate;


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

    public function __construct()
    {
        parent::__construct();
        $this->users = new ArrayCollection();
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? "bundles/core/images/charisson.jpg"  : $this->getUploadDir().'/'.$this->path;
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
        return 'uploads/guildImages';
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
     * @return Guild
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
     * Set tag
     *
     * @param string $tag
     * @return Guild
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set announce
     *
     * @param string $announce
     * @return Guild
     */
    public function setAnnounce($announce)
    {
        $this->announce = $announce;

        return $this;
    }

    /**
     * Get announce
     *
     * @return string 
     */
    public function getAnnounce()
    {
        return $this->announce;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return Guild
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set recruting
     *
     * @param boolean $recruting
     * @return Guild
     */
    public function setRecruting($recruting)
    {
        $this->recruting = $recruting;

        return $this;
    }

    /**
     * Get recruting
     *
     * @return boolean 
     */
    public function getRecruting()
    {
        return $this->recruting;
    }

    /**
     * Set recrutingAnnounce
     *
     * @param string $recrutingAnnounce
     * @return Guild
     */
    public function setRecrutingAnnounce($recrutingAnnounce)
    {
        $this->recrutingAnnounce = $recrutingAnnounce;

        return $this;
    }

    /**
     * Get recrutingAnnounce
     *
     * @return string 
     */
    public function getRecrutingAnnounce()
    {
        return $this->recrutingAnnounce;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Guild
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
     * Set lastImageUpdate
     *
     * @param string $lastImageUpdate
     * @return Guild
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
     * Add users
     *
     * @param \LunaAtra\ProfileBundle\Entity\User $users
     * @return Guild
     */
    public function addUser(\LunaAtra\ProfileBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \LunaAtra\ProfileBundle\Entity\User $users
     */
    public function removeUser(\LunaAtra\ProfileBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
