<?php

namespace LunaAtra\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LunaAtra\ProfileBundle\Entity\Charact;
use Symfony\Component\HttpFoundation\Request;
use LunaAtra\CoreBundle\Entity\Notification;
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
                $notification = new Notification();
                $notification->CreateCharacter($user,$character);
                $em->persist($notification);
                $em->flush();
                return $this->redirect($this->generateUrl('user-characters', array("username"=> $user->getUsername() )));
            }
        }

        return array("form"=>$form->createView());
    }

    /**
     * @Route("/profile/edit-character/{id}", name="edit-character")
     * @Template("ProfileBundle:Default:add-character.html.twig")
     */
    public function editCharacterAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $character = $em->getRepository('ProfileBundle:Charact')->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        if($character->getUser() != $user)
        {
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        $form = $this->createFormBuilder($character)
            ->add("name")
            ->add("birthday")
            ->add("file")
            ->add("class")
            ->add("level")
            ->add("announce")
            ->add("bio")
            ->add("lastImageUpdate", "hidden",array("data" => date('Y-m-d H:i:s') ))
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) { 
                $user = $this->get('security.context')->getToken()->getUser();
                $character->setUser($user);
                $em->persist($character);
                $em->flush();
                $notification = new Notification();
                $notification->CreateCharacter($user,$character);
                $em->persist($notification);
                $em->flush();
                return $this->redirect($this->generateUrl('user-character', array("id"=> $character->getId() )));
            }
        }

        return array("form"=>$form->createView(),"character" => $character);
    }


}

