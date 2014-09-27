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
    }

    public function getPrivacyForm()
    {
        $return = Array();
        $return["name"] = "privacy";
        $return["type"] = "choice";
        $return["params"] = array(
                "choices" => array(
                        $this->assoc["public"]         => $this->translator->trans("choice.public",array(), "choices"),
                        //$this->assoc["my_communities"] => $this->translator->trans("choice.my_communities",array(), "choices"),
                        //$this->assoc["friends"]        => $this->translator->trans("choice.friends",array(), "choices"),
                        $this->assoc["only_me"]        => $this->translator->trans("choice.only_me",array(), "choices")
                    ),
                "required" => true,
                "multiple" =>true,
                "expanded" => true
            );
        return $return;
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