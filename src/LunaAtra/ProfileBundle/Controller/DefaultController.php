<?php

namespace LunaAtra\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/user")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/{username}", name="user")
     * @Template("ProfileBundle:Profile:show.html.twig")
     */
    public function profileAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ProfileBundle:User')->findOneByUsername($username);
        if(!is_object($user)){
            throw $this->createNotFoundException('The user does not exist !');
        }
        return array('user' =>$user, "pagename" => "Profil");
    }

    /**
     * @Route("/{username}/bio", name="user-bio")
     * @Template("ProfileBundle:Default:bio.html.twig")
     */
    public function bioAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ProfileBundle:User')->findOneByUsername($username);
        return array('user' =>$user, "pagename" => "Biographie");
    }

    /**
     * @Route("/{username}/characters", name="user-characters")
     * @Template("ProfileBundle:Default:characters.html.twig")
     */
    public function CharactersAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ProfileBundle:User')->findOneByUsername($username);
        return array('user' =>$user, "pagename" => "Personnages");
    }

}
