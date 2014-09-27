<?php

namespace LunaAtra\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/characters")
 */
class CharacterController extends Controller
{


    /**
     * @Route("/{id}", name="single-character")
     * @Template("ProfileBundle:Default:character.html.twig")
     */
    public function CharacterAction($id)
    {
        $privacyManager = $this->get("privacy.manager");
        $em = $this->getDoctrine()->getManager();
        $character = $em->getRepository('ProfileBundle:Charact')->findOneById($id);
        if(!is_object($character)){
            throw $this->createNotFoundException('Character doesn\'t exist.');
        }

        if(!$privacyManager->canISee($character)){
            throw $this->createNotFoundException('Character is private.');
        }
        return array('user' =>$character->getUser(), "character" => $character);
    }

}