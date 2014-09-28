<?php

namespace LunaAtra\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use LunaAtra\PrivacyBundle\Model\PrivacyInterface;
/**
 * Charact
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LunaAtra\ProfileBundle\Entity\CharactRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Charact implements PrivacyInterface
{
    private $urlName;
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
     * @ORM\Column(name="announce", type="string", length=45, nullable=true)
     */
    private $announce;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordre", type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=255)
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    private $bio;

    /**
     * @var string
     *
     * @ORM\Column(name="server", type="string", length=255, nullable=true)
     */
    private $server;

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy", type="array", nullable=true)
     */
    private $privacy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date")
     */
    private $birthday;

    /**
    * @ORM\ManyToOne(targetEntity="LunaAtra\ProfileBundle\Entity\User")
    */
    protected $user;

    /**
    * @ORM\ManyToOne(targetEntity="LunaAtra\CoreBundle\Entity\Game")
    */
    protected $game;

    /**
     * @var date $lastImageUpdate
     *
     * @ORM\Column(name="lastImageUpdate", type="string", nullable=true)
     */
    private $lastImageUpdate;

    /**
     * @Assert\File(maxSize="1000000", mimeTypes = {"image/jpeg", "image/png", "image/gif"}, mimeTypesMessage = "Please upload a valid image")
     */
    public $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    public function get($name)
    {
        return  isset($this->$name) ? $this->$name : null;
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



    public function __construct()
    {
        $this->urlName = "single-character";
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        $lastDigit = substr($this->id, -1);
        return null === $this->path ? "bundles/core/images/charisson$lastDigit.jpg"  : $this->getUploadDir().'/'.$this->path;
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
        return 'uploads/charactersImages';
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
     * @return Charact
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
     * Set level
     *
     * @param integer $level
     * @return Charact
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set class
     *
     * @param string $class
     * @return Charact
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return Charact
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
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return Charact
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set lastImageUpdate
     *
     * @param string $lastImageUpdate
     * @return Charact
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
     * @return Charact
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

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Charact
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Charact
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
     * Set announce
     *
     * @param string $announce
     * @return Charact
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
    /** usefull for the translation of activities
    */
    public function getUrlName()
    {
        return "single-character";
    }

    /**
     * Set server
     *
     * @param string $server
     * @return Charact
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return string 
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set game
     *
     * @param \LunaAtra\CoreBundle\Entity\Game $game
     * @return Charact
     */
    public function setGame(\LunaAtra\CoreBundle\Entity\Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \LunaAtra\CoreBundle\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set privacy
     *
     * @param array $privacy
     * @return Charact
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;

        return $this;
    }

    /**
     * Get privacy
     *
     * @return array 
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }
}
