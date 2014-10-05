<?php

namespace LunaAtra\ProfileBundle\Entity;

use LunaAtra\PrivacyBundle\Model\PrivacyInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Blog
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LunaAtra\ProfileBundle\Entity\BlogRepository")
 */
class Blog  implements PrivacyInterface
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text",  nullable=true)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime",  nullable=true)
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_date", type="datetime",  nullable=true)
     */
    private $publishedDate;

    /**
     * @var array
     *
     * @ORM\Column(name="privacy", type="array",  nullable=true)
     */
    private $privacy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="draft", type="boolean")
     */
    private $draft;

    /**
    * @ORM\ManyToOne(targetEntity="LunaAtra\ProfileBundle\Entity\User")
    */
    protected $user;

    /**
    * @ORM\ManyToOne(targetEntity="LunaAtra\CoreBundle\Entity\Game")
    */
    protected $game;

    public function __construct()
    {
        $this->draft = true;
        $this->creationDate = new \DateTime("now");
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
     * Set title
     *
     * @param string $title
     * @return Blog
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
     * Set content
     *
     * @param string $content
     * @return Blog
     */
    public function setContent($content)
    {
        $this->content = strip_tags($content,'<a><abbr><acronym><address><area><b><big><blockquote><br><button><caption><center><cite><code><col><colgroup><dd><del><dfn><dir><div><dl><dt><em><fieldset><font><h1><h2><h3><h4><h5><h6><hr><i><img><input><ins><kbd><label><legend><li><map><menu><ol><optgroup><option><p><pre><q><s><samp><select><small><span><strike><strong><sub><sup><table><tbody><td><textarea><tfoot><th><thead><tr><tt><u><ul>');
        //$this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    public function getTruncatedContent($number = 250)
    {
        $text = strip_tags(preg_replace("/<img[^>]+\>/i", "(image) ", $this->content),'<p><em><strong><a><b><u><strong><em>'); 
        $text = $text." ";
        $text = substr($text,0,$number);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text."...";
        return $text;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Blog
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Blog
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     * @return Blog
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get publishedDate
     *
     * @return \DateTime 
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * Set privacy
     *
     * @param array $privacy
     * @return Blog
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

    /**
     * Set draft
     *
     * @param boolean $draft
     * @return Blog
     */
    public function setDraft($draft)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Get draft
     *
     * @return boolean 
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Set user
     *
     * @param \LunaAtra\ProfileBundle\Entity\User $user
     * @return Blog
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
     * Set game
     *
     * @param \LunaAtra\CoreBundle\Entity\Game $game
     * @return Blog
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
}
    