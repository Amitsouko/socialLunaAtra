<?php
namespace LunaAtra\PrivacyBundle\Twig;

use LunaAtra\PrivacyBundle\Model\PrivacyInterface;

class PrivacyExtension extends \Twig_Extension
{
    private $securityContext;
    private $connectedUser;
    private $privacyManager;

    public function __construct($securityContext, $privacyManager)
    {
        $this->securityContext  = $securityContext;
        $this->privacyManager  = $privacyManager;
        // if($securityContext->getToken())
        // {
        //    $this->connectedUser = $securityContext->getToken()->getUser();    
        // }
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('isPrivate', array($this, 'isPrivate')),
            new \Twig_SimpleFilter('canISee', array($this, 'canISee')),
        );
    }

    public function isPrivate(PrivacyInterface $object)
    {   
        $privacy = $object->getPrivacy();

        if(is_array($privacy) && in_array("_100_", $privacy)){
            return true;
        } else {
            return false;
        }
        
    }

    public function canISee(PrivacyInterface $object)
    {
        return $this->privacyManager->canISee($object);
    }

    public function getName()
    {
        return 'privacy_extension';
    }
}