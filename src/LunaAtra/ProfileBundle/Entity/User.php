<?php
namespace LunaAtra\ProfileBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    public $announce;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    public $bio;

    /**
     * @ORM\OneToMany(targetEntity="LunaAtra\ProfileBundle\Entity\Charact", mappedBy="user")
     * @ORM\OrderBy({"ordre" = "ASC"})
     * @var type
     */
    protected $characters;

    /**
     * @ORM\OneToMany(targetEntity="LunaAtra\ProfileBundle\Entity\Blog", mappedBy="user")
     * @ORM\OrderBy({"publishedDate" = "DESC"})
     * @var type
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="LunaAtra\CoreBundle\Entity\Activity", mappedBy="seeder")
     * @ORM\OrderBy({"date" = "DESC"})
     * @var type
     */
    protected $activities;

    /**
     * @ORM\ManyToMany(targetEntity="Guild", inversedBy="users")
     * @ORM\JoinTable(name="users_to_guilds")
     **/
    private $guilds;

    /**
     * @ORM\ManyToMany(targetEntity="LunaAtra\CoreBundle\Entity\Notification", mappedBy="users")
     **/
    private $notifications;

    /**
     * @Assert\File(maxSize="2000000", mimeTypes = {"image/jpeg", "image/png", "image/gif"}, mimeTypesMessage = "Please upload a valid image")
     */
    public $file;

    /**
     * @ORM\OneToMany(targetEntity="LunaAtra\ProfileBundle\Entity\ProfileCover", mappedBy="user", cascade={"persist"} )
     **/
    private $cover;

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



    public function __construct()
    {
        parent::__construct();
        $this->guilds = new ArrayCollection();
        $this->cover = new ArrayCollection();
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
        return 'uploads/profileImages';
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
     * Set path
     *
     * @param string $path
     * @return User
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
     * @param \DateTime $lastImageUpdate
     * @return User
     */
    public function setLastImageUpdate($lastImageUpdate)
    {
        $this->lastImageUpdate = $lastImageUpdate;

        return $this;
    }

    /**
     * Get lastImageUpdate
     *
     * @return \DateTime 
     */
    public function getLastImageUpdate()
    {
        return $this->lastImageUpdate;
    }

    /**
     * Set announce
     *
     * @param string $announce
     * @return User
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
     * @return User
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
     * Add characters
     *
     * @param \LunaAtra\ProfileBundle\Entity\Charact $characters
     * @return User
     */
    public function addCharacter(\LunaAtra\ProfileBundle\Entity\Charact $characters)
    {
        $this->characters[] = $characters;

        return $this;
    }

    /**
     * Remove characters
     *
     * @param \LunaAtra\ProfileBundle\Entity\Charact $characters
     */
    public function removeCharacter(\LunaAtra\ProfileBundle\Entity\Charact $characters)
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
     * Add ownNotifications
     *
     * @param \LunaAtra\CoreBundle\Entity\Notification $ownNotifications
     * @return User
     */
    public function addOwnNotification(\LunaAtra\CoreBundle\Entity\Notification $ownNotifications)
    {
        $this->ownNotifications[] = $ownNotifications;

        return $this;
    }

    /**
     * Remove ownNotifications
     *
     * @param \LunaAtra\CoreBundle\Entity\Notification $ownNotifications
     */
    public function removeOwnNotification(\LunaAtra\CoreBundle\Entity\Notification $ownNotifications)
    {
        $this->ownNotifications->removeElement($ownNotifications);
    }

    /**
     * Get ownNotifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwnNotifications()
    {
        return $this->ownNotifications;
    }

    /**
     * Add guilds
     *
     * @param \LunaAtra\ProfileBundle\Entity\Guild $guilds
     * @return User
     */
    public function addGuild(\LunaAtra\ProfileBundle\Entity\Guild $guilds)
    {
        $this->guilds[] = $guilds;

        return $this;
    }

    /**
     * Remove guilds
     *
     * @param \LunaAtra\ProfileBundle\Entity\Guild $guilds
     */
    public function removeGuild(\LunaAtra\ProfileBundle\Entity\Guild $guilds)
    {
        $this->guilds->removeElement($guilds);
    }

    /**
     * Get guilds
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuilds()
    {
        return $this->guilds;
    }

    /**
     * Add notifications
     *
     * @param \LunaAtra\CoreBundle\Entity\Notification $notifications
     * @return User
     */
    public function addNotification(\LunaAtra\CoreBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;

        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \LunaAtra\CoreBundle\Entity\Notification $notifications
     */
    public function removeNotification(\LunaAtra\CoreBundle\Entity\Notification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add activities
     *
     * @param \LunaAtra\CoreBundle\Entity\Activity $activities
     * @return User
     */
    public function addActivity(\LunaAtra\CoreBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;

        return $this;
    }

    /**
     * Remove activities
     *
     * @param \LunaAtra\CoreBundle\Entity\Activity $activities
     */
    public function removeActivity(\LunaAtra\CoreBundle\Entity\Activity $activities)
    {
        $this->activities->removeElement($activities);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }

    public function getUrlName()
    {
        return "user";
    }

    /**
     * Set cover
     *
     * @param \LunaAtra\ProfileBundle\Entity\ProfileCover $cover
     * @return User
     */
    public function setCover(\LunaAtra\ProfileBundle\Entity\ProfileCover $cover = null)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return \LunaAtra\ProfileBundle\Entity\ProfileCover 
     */
    public function getCover()
    {
        return $this->cover[0];
    }

    /**
     * Add cover
     *
     * @param \LunaAtra\ProfileBundle\Entity\ProfileCover $cover
     * @return User
     */
    public function addCover(\LunaAtra\ProfileBundle\Entity\ProfileCover $cover)
    {
        $this->cover[] = $cover;

        return $this;
    }

    /**
     * Remove cover
     *
     * @param \LunaAtra\ProfileBundle\Entity\ProfileCover $cover
     */
    public function removeCover(\LunaAtra\ProfileBundle\Entity\ProfileCover $cover)
    {
        $this->cover->removeElement($cover);
    }

    /**
     * Add posts
     *
     * @param \LunaAtra\ProfileBundle\Entity\Blog $posts
     * @return User
     */
    public function addPost(\LunaAtra\ProfileBundle\Entity\Blog $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \LunaAtra\ProfileBundle\Entity\Blog $posts
     */
    public function removePost(\LunaAtra\ProfileBundle\Entity\Blog $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
