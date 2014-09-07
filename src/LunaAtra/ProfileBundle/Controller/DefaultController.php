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
        return array('user' =>$user);
    }

    /**
     * @Route("/{username}/bio", name="user-bio")
     * @Template("ProfileBundle:Default:bio.html.twig")
     */
    public function bioAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ProfileBundle:User')->findOneByUsername($username);
        return array('user' =>$user);
    }

    /**
     * @Route("/{username}/characters", name="user-characters")
     * @Template("ProfileBundle:Default:characters.html.twig")
     */
    public function CharactersAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ProfileBundle:User')->findOneByUsername($username);
        return array('user' =>$user);
    }

    /**
     * @Route("/character/{id}", name="user-character")
     * @Template("ProfileBundle:Default:character.html.twig")
     */
    public function CharacterAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $character = $em->getRepository('ProfileBundle:Charact')->findOneById($id);
        return array('user' =>$character->getUser(), "character" => $character);
    }
}
