<?php

namespace LunaAtra\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LunaAtra\ProfileBundle\Entity\Charact;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class CustomProfileController extends Controller
{
    /**
     * @Route("/profile/add-character", name="add-character")
     * @Template("ProfileBundle:Default:add-character.html.twig")
     */
    public function addCharacterAction(Request $request)
    {
         $character = new Charact();
         $em = $this->get('doctrine')->getManager();

        $form = $this->createFormBuilder($character)
            ->add("name")
            ->add("birthday")
            ->add("file")
            ->add("class")
            ->add("level")
            ->add("announce")
            ->add("bio")
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) { 
                $user = $this->get('security.context')->getToken()->getUser();
                $character->setUser($user);
                $em->persist($character);
                $em->flush();
                return $this->redirect($this->generateUrl('user-characters', array("username"=> $user->getUsername() )));
            }
        }

        return array("form"=>$form->createView());
    }



}

