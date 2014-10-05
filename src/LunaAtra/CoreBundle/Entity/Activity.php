<?php

namespace LunaAtra\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LunaAtra\CoreBundle\Entity\ActivityRepository")
 */
class Activity
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
     * @ORM\Column(name="translation", type="string", length=255)
     */
    private $translation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
    * @ORM\ManyToOne(targetEntity="LunaAtra\ProfileBundle\Entity\User")
    */
    protected $seeder;

    /**
    * @ORM\ManyToOne(targetEntity="LunaAtra\ProfileBundle\Entity\Charact",inversedBy="activities")
    * @ORM\JoinColumn(name="character_id", referencedColumnName="id", onDelete="SET NULL")
    */
    protected $character;

    /**
    * @ORM\ManyToOne(targetEntity="LunaAtra\ProfileBundle\Entity\Blog",inversedBy="activities")
    * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="SET NULL")
    */
    protected $post;
    
    /**
     * @var array
     *
     * @ORM\Column(name="data", type="array")
     */
    private $data;

    /**
     * @ORM\ManyToMany(targetEntity="LunaAtra\ProfileBundle\Entity\User", inversedBy="activities")
     * @ORM\JoinTable(name="users_to_activities")
     **/
    private $users;

    public function get($name)
    {
        return  isset($this->$name) ? $this->$name : null;
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
     * Set translation
     *
     * @param string $translation
     * @return Notification
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * Get translation
     *
     * @return string 
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Notification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return Notification
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array 
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime("now");
    }

    /**
     * Set seeder
     *
     * @param \LunaAtra\ProfileBundle\Entity\User $seeder
     * @return Notification
     */
    public function setSeeder(\LunaAtra\ProfileBundle\Entity\User $seeder = null)
    {
        $this->seeder = $seeder;

        return $this;
    }

    /**
     * Get seeder
     *
     * @return \LunaAtra\ProfileBundle\Entity\User 
     */
    public function getSeeder()
    {
        return $this->seeder;
    }

    /**
     * Add users
     *
     * @param \LunaAtra\ProfileBundle\Entity\User $users
     * @return Notification
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

   /**
     * Set character
     *
     * @param \LunaAtra\ProfileBundle\Entity\Charact $character
     * @return Activity
     */
    public function setCharacter(\LunaAtra\ProfileBundle\Entity\Charact $character = null)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * Get character
     *
     * @return \LunaAtra\ProfileBundle\Entity\Charact 
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     *    To create activity :   
     *    array(
     *      "key_of_translation" => array("entity" => "name of the entity", "id" => "id of the entity", "column" => "column name of the value", {"urlKey" => "parameter needed to construct the url", "urlData" => "data needed to construct url", "isUrl" => true})
     *    ) 
     *    
     *
    */


    public function createPost($user,$post)
    {
        $this->setSeeder($user);
        $this->setPost($post);
        $this->setTranslation("user.create.post");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username"),
                "user_url" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username","urlKey" => "username", "urlData" =>"username",  "isUrl" => true),
                "post_title" => array("entity" => "ProfileBundle:Blog", "id" => $post->getId(), "column" => "title"),
                "post_url" => array("entity" => "ProfileBundle:Blog","id" => $post->getId(), "column" => "id","urlKey" => "id", "urlData" => "id",  "isUrl" => true)
            )
         );
    }

    public function updatePost($user,$post)
    {
        $this->setSeeder($user);
        $this->setPost($post);
        $this->setTranslation("user.update.post");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username"),
                "user_url" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username","urlKey" => "username", "urlData" =>"username",  "isUrl" => true),
                "post_title" => array("entity" => "ProfileBundle:Blog", "id" => $post->getId(), "column" => "title"),
                "post_url" => array("entity" => "ProfileBundle:Blog","id" => $post->getId(), "column" => "id","urlKey" => "id", "urlData" => "id",  "isUrl" => true)
            )
         );
    }

    public function deletePost($user,$post)
    {
        $this->setSeeder($user);
        $this->setTranslation("user.delete.post");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username"),
                "user_url" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username","urlKey" => "username", "urlData" =>"username",  "isUrl" => true),
                "post_title" => $post->getTitle(),
                "post_url" => "post-deleted"
            )
         );
    }

    public function setPostFallback($post)
    {
        $data = $this->getData();
        $data["post_title"] = $post->getTitle();
        $data["post_url"] = "post-deleted";
        $this->setData($data);
    }

    public function CreateProfile($user)
    {
        $this->setSeeder($user);
        $this->setTranslation("user.create.profile");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username"),
                "user_url" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username","urlKey" => "username", "urlData" =>"username",  "isUrl" => true)
           )
         );
    }

    public function setUpdateProfile($user)
    {
        $this->setSeeder($user);
        $this->setTranslation("user.update.profile");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username"),
                "user_url" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username","urlKey" => "username", "urlData" =>"username",  "isUrl" => true)
            )
         );
    }

    public function CreateCharacter($user,$character)
    {
        $this->setSeeder($user);
        $this->setCharacter($character);
        $this->setTranslation("user.create.character");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username"),
                "user_url" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username","urlKey" => "username", "urlData" =>"username",  "isUrl" => true),
                "character_username" => array("entity" => "ProfileBundle:Charact", "id" => $character->getId(), "column" => "name"),
                "character_url" => array("entity" => "ProfileBundle:Charact","id" => $character->getId(), "column" => "id","urlKey" => "id", "urlData" => "id",  "isUrl" => true)
            )
         );
    }

    public function updateCharacter($user,$character)
    {
        $this->setSeeder($user);
        $this->setCharacter($character);
        $this->setTranslation("user.update.character");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username"),
                "user_url" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username","urlKey" => "username", "urlData" =>"username",  "isUrl" => true),
                "character_username" => array("entity" => "ProfileBundle:Charact", "id" => $character->getId(), "column" => "name"),
                "character_url" => array("entity" => "ProfileBundle:Charact","id" => $character->getId(), "column" => "id","urlKey" => "id", "urlData" => "id",  "isUrl" => true)
            )
         );
    }

    public function DeleteCharacter($user,$character)
    {
        $this->setSeeder($user);
        $this->setTranslation("user.delete.character");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username"),
                "user_url" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username","urlKey" => "username", "urlData" =>"username",  "isUrl" => true),
                "character_username" => $character->getName(),
                "character_url" => "single-character-deleted"
            )
         );
    }

    public function setCharacterFallback($character)
    {
        $data = $this->getData();
        $data["character_username"] = $character->getName();
        $data["character_url"] = "single-character-deleted";
        $this->setData($data);
    }
    

    public function updateCover($user)
    {
        $this->setSeeder($user);
        $this->setTranslation("user.update.cover");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username"),
                "user_url" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username","urlKey" => "username", "urlData" =>"username",  "isUrl" => true)
            )
         );
    }


    /**
     * Set post
     *
     * @param \LunaAtra\ProfileBundle\Entity\Blog $post
     * @return Activity
     */
    public function setPost(\LunaAtra\ProfileBundle\Entity\Blog $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \LunaAtra\ProfileBundle\Entity\Blog 
     */
    public function getPost()
    {
        return $this->post;
    }
}
