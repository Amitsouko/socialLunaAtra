<?php

namespace LunaAtra\PrivacyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use LunaAtra\PrivacyBundle\Model\PrivacyInterface;
use LunaAtra\ProfileBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\User as BaseUser;

class PrivacyManager extends ContainerAware
{
    private $session;
    private $em;
    private $translator;
    private $connectedUser;
    private $assoc;
    private $assocReversed;
    private $assocTranslate;

    public function __construct( $securityContext, $em, $translator, $privacyRank)
    {
        $this->securityContext  = $securityContext;
        if($securityContext->getToken()){
            $this->connectedUser = $securityContext->getToken()->getUser();
        }else{
            $this->connectedUser = false;
        }
        $this->em  = $em;
        $this->translator = $translator;
        $this->assoc = $privacyRank;
        $this->assocReversed = array_flip($privacyRank);
        $assocTranslate = array();
        foreach($this->assoc as $key => $value)
        {
            $this->assocTranslate[$key] = $this->translator->trans("choice.$key",array(), "choices");
        }
    }

    /**
     *   GET the privacy form
    */
    public function getPrivacyForm()
    {
        $return = $this->getForm();
        $return["params"]["data"] = array($this->assoc["public"]);
        return $return;
    }

    /**
     *   GET the privacy form
    */
    public function getEditPrivacyForm()
    {
        return $this->getForm();
    }


    /**
     *   GET the privacy name
    */
    public function getPrivacyName($string)
    {
        return (isset($this->assocReversed[$string])) ? $this->assocTranslate[$this->assocReversed[$string]] : false;
    } 

    /**
     *   GET the privacy of content for an user
     * usually used for a querybuilder request to set where closes
    */
    public function getUserRightOnContent(BaseUser $owner)
    {
        $arrayCanAccess = array();
        $arrayCanAccess[] = "_0_";


        #communities
        if($this->isInCommunity($owner))
        {
            $arrayCanAccess[] = "_1_";
        }

        if($this->isInFriends($owner))
        {
            $arrayCanAccess[] = "_2_";
        }
    

        return $arrayCanAccess;
    }

    public function isInCommunity(BaseUser $owner)
    {
        //@TODO: add condition
        return true;
    }

    public function isInFriends(BaseUser $owner)
    {
        //@TODO: add condition
        return true;
    }

    /**
     *   Check if user can see something
    */
    public function canISee(PrivacyInterface $object)
    {
        $privacyConf = $object->getPrivacy();
        if(!is_array($privacyConf)) return true;
        if($this->connectedUser == $object->getUser()) return true;
        //IF only me; so return false
        if(in_array($this->assoc["only_me"], $privacyConf)) return false;
        //if public so, return true
        if(in_array($this->assoc["public"], $privacyConf)) return true;

    }

    private function getForm()
    {
        $return = Array();
        $return["name"] = "privacy";
        $return["type"] = "choice";
        $return["params"] = array(
                "choices" => array(
                        $this->assoc["public"]         => $this->assocTranslate["public"],
                        //$this->assoc["my_communities"] => $this->translator->trans("choice.my_communities",array(), "choices"),
                        $this->assoc["friends"]        => $this->translator->trans("choice.friends",array(), "choices"),
                        $this->assoc["only_me"]        => $this->assocTranslate["only_me"]
                    ),
                "required" => true,
                "multiple" =>true,
                "expanded" => true
            );
        return $return;
    }

}