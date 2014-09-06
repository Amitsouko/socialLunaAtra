<?php

namespace LunaAtra\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LunaAtra\CoreBundle\Entity\NotificationRepository")
 */
class Notification
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
     * @var array
     *
     * @ORM\Column(name="data", type="array")
     */
    private $data;

    /**
     * @ORM\ManyToMany(targetEntity="LunaAtra\ProfileBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinTable(name="users_to_notifications")
     **/
    private $users;


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

    public function setUpdateProfile($user)
    {
        $this->setSeeder($user);
        $this->setTranslation("user.update.profile");
        $this->setData(
         array(
                "username" => array("entity" => "ProfileBundle:User", "id" => $user->getId(), "column" => "username")
            )
         );
    }

}
