<?php

namespace LunaAtra\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friends
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LunaAtra\ProfileBundle\Entity\FriendsRepository")
 */ 
class Friends
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
    * @ORM\ManyToOne(targetEntity="LunaAtra\ProfileBundle\Entity\User")
    */
    protected $requester;

    /**
    * @ORM\ManyToOne(targetEntity="LunaAtra\ProfileBundle\Entity\User")
    */
    protected $requested;

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
     * Set date
     *
     * @param \DateTime $date
     * @return Friends
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
     * Set status
     *
     * @param integer $status
     * @return Friends
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set requester
     *
     * @param \LunaAtra\ProfileBundle\Entity\User $requester
     * @return Friends
     */
    public function setRequester(\LunaAtra\ProfileBundle\Entity\User $requester = null)
    {
        $this->requester = $requester;

        return $this;
    }

    /**
     * Get requester
     *
     * @return \LunaAtra\ProfileBundle\Entity\User 
     */
    public function getRequester()
    {
        return $this->requester;
    }

    /**
     * Set requested
     *
     * @param \LunaAtra\ProfileBundle\Entity\User $requested
     * @return Friends
     */
    public function setRequested(\LunaAtra\ProfileBundle\Entity\User $requested = null)
    {
        $this->requested = $requested;

        return $this;
    }

    /**
     * Get requested
     *
     * @return \LunaAtra\ProfileBundle\Entity\User 
     */
    public function getRequested()
    {
        return $this->requested;
    }
}
