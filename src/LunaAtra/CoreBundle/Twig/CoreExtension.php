<?php
namespace LunaAtra\CoreBundle\Twig;


class CoreExtension  extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('getFirstSrc', array($this, 'getFirstSrc')),
            new \Twig_SimpleFilter('friendButton', array($this, 'friendButton')),
        );
    }

    public function getFirstSrc($text)
    {
        preg_match('/<img.+src=[\'"](?P<src>.+)[\'"].*>/i', $text, $image);
        return (isset($image['src'])) ? $image['src'] : "";
    }

    public function friendButton($friendLink, $connectedUser)
    {
        if($friendLink == false){
            return "";
        } 
        if(count($friendLink) != null )
        {
            if($friendLink->getStatus() == 1)
            {
                return "vous êtes amis !";
            }else if($friendLink->getRequester() == $connectedUser)
            {
                return "vous lui avez envoyé une requêtes";
            }else{
                return "IL vous a envoyé unre quête d'amis";
            }
        }else{
            return "not friend !";
        }
    }

    public function getName()
    {
        return 'core_extension';
    }
}