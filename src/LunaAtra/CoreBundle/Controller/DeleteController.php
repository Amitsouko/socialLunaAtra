<?php

namespace LunaAtra\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use LunaAtra\CoreBundle\Entity\Activity;
/**
 * @Route("/delete")
 * @Template()
 */
class DeleteController extends Controller
{
    /**
     * @Route("/character/{id}", name="delete-character")
     * @Template()
     */
    public function indexAction($id, Request $request)
    {
        $securityContext = $this->container->get('security.context');
        $em = $this->getDoctrine()->getManager();
        $character = $em->getRepository('ProfileBundle:Charact')->findOneById($id);
        $user = $securityContext->getToken()->getUser();

        if(!is_object($character)){
            throw $this->createNotFoundException('Character doesn\'t exist.');
        }

        if( $user != $character->getUser()){
            throw $this->createNotFoundException('You don\'t own the character.');
        }

        $dataForm = array();
        $form = $this->createFormBuilder($dataForm)
                ->add('id', 'hidden', array("data" => $character->getId()))
                ->add('type', 'hidden', array("data" => "charact"))
                ->add('owner', 'hidden', array("data" => $user->getId()))
        ->getForm();

        if($request->isMethod('POST')) {
            $form->bind($request);
            $data = $form->getData();
            if($character->getId() == $data["id"] && $user->getId() == $data["owner"] && $character->getUser()->getId() == $user->getId() )
            {
                foreach($character->getActivities() as $activity){
                    $activity->setCharacterFallback($character);
                    $em->persist($activity);
                }
                $act = new Activity();
                $act->DeleteCharacter($user,$character);
                $em->persist($act);
                $em->remove($character);
                $em->flush();
                return $this->redirect($this->generateUrl('user', array("username" => $user->getUsername())));
            }
        }

        $data = array(
                "name" => $character->getName(),
                "urlOfDelete" => $uri = $this->get("router")->generate('single-character', array('id' => $character->getId())) 
            );

        return $this->render(
                'CoreBundle:Delete:confirm.html.twig',
                array("character"=> $character, "data" => $data, "form" => $form->createView()));
        
    }

}
