<?php

namespace LunaAtra\PrivacyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use LunaAtra\PrivacyBundle\Model\PrivacyInterface;
use LunaAtra\ProfileBundle\Entity\User;

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

    public function getPrivacyForm()
    {
        $return = Array();
        $return["name"] = "privacy";
        $return["type"] = "choice";
        $return["params"] = array(
                "choices" => array(
                        $this->assoc["public"]         => $this->assocTranslate["public"],
                        //$this->assoc["my_communities"] => $this->translator->trans("choice.my_communities",array(), "choices"),
                        //$this->assoc["friends"]        => $this->translator->trans("choice.friends",array(), "choices"),
                        $this->assoc["only_me"]        => $this->assocTranslate["only_me"]
                    ),
                "required" => true,
                "multiple" =>true,
                "expanded" => true,
                "data" => array($this->assoc["public"])
            );
        return $return;
    }

    public function getPrivacyName($string)
    {
        return (isset($this->assocReversed[$string])) ? $this->assocTranslate[$this->assocReversed[$string]] : false;
    }

    public function canISee(PrivacyInterface $object)
    {
        $privacyConf = $object->getPrivacy();
        if($this->connectedUser == $object->getUser()) return true;
        //IF only me; so return false
        if(in_array($this->assoc["only_me"], $privacyConf)) return false;
        //if public so, return true
        if(in_array($this->assoc["public"], $privacyConf)) return true;

    }

}