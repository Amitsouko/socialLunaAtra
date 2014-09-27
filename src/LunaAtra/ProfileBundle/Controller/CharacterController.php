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

        $em = $this->getDoctrine()->getManager();
        $character = $em->getRepository('ProfileBundle:Charact')->findOneById($id);
        if(!is_object($character)){
            throw $this->createNotFoundException('Character doesn\'t exist.');
        }
        // $user = $this->get('security.context')->getToken()->getUser();
        // if($character->getPrivate() && $user != $character->getUser() ){
        //     throw $this->createNotFoundException('Character is private exists.');
        // }
        return array('user' =>$character->getUser(), "character" => $character);
    }

}