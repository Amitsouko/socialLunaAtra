<?php

namespace LunaAtra\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LunaAtra\ProfileBundle\Entity\Charact;
use LunaAtra\CoreBundle\Entity\Game;
use LunaAtra\CoreBundle\Entity\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use LunaAtra\CoreBundle\Entity\Activity;
use LunaAtra\ProfileBundle\Entity\ProfileCover;
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
        $privacyManager = $this->container->get("privacy.manager");
        $privacyForm = $privacyManager->getPrivacyForm();

        $form = $this->createFormBuilder($character)
            ->add("name")
            ->add("birthday")
            ->add("file")
            ->add("class")
            ->add($privacyForm["name"], $privacyForm["type"], $privacyForm["params"])
            ->add("level")
            ->add('game','entity', array(
                "multiple"=>false,
                'class'=>'LunaAtra\CoreBundle\Entity\Game', 
                'property'=>'name', 
                "empty_value" => "-- Choose a game --" ,
                "empty_data" => false ,
                'query_builder' => function(GameRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    }
                ))
            ->add("server")
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
                $activity = new Activity();
                $activity->CreateCharacter($user,$character);
                // $isExists =  $em->getRepository('CoreBundle:Activity')->findLastSameActivity($activity);
                // if(count($isExists) > 0)
                // {
                //     $existingActivity = $isExists[0];
                //     $existingActivity->setDate(new \Datetime("now"));
                //     $em->persist($existingActivity);
                // }else{
                //     $em->persist($activity);
                // }
                $em->persist($activity);
                $em->flush();
                return $this->redirect($this->generateUrl('user-characters', array("username"=> $user->getUsername() )));
            }
        }

        return array("form"=>$form->createView());
    }


    /**
     * @Route("/profile/edit-cover", name="edit-cover")
     * @Template("ProfileBundle:Default:edit-cover.html.twig")
     */
    public function editCoverAction(Request $request)
    {
        $cover = new ProfileCover();
         $em = $this->get('doctrine')->getManager();

        $form = $this->createFormBuilder($cover)
            ->add("lastImageUpdate", "hidden",array("data" => date('Y-m-d H:i:s') ))
            ->add("file")
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) { 
                $user = $this->get('security.context')->getToken()->getUser();
                if($cc = $user->getCover())
                {
                    $em->remove($cc);
                    $em->flush();
                }

                $cover->setUser($user);
                $em->persist($cover);
                $em->flush();
                $activity = new Activity();
                $activity->updateCover($user);
                $isExists =  $em->getRepository('CoreBundle:Activity')->findLastSameActivity($activity);
                if(count($isExists) > 0)
                {
                    $existingActivity = $isExists[0];
                    $existingActivity->setDate(new \Datetime("now"));
                    $em->persist($existingActivity);
                }else{
                    $em->persist($activity);
                }
                $em->flush();
                return $this->redirect($this->generateUrl('fos_user_profile_show'));
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
        $privacyManager = $this->container->get("privacy.manager");
        $privacyForm = $privacyManager->getPrivacyForm();

        $privacyManager->canISee($character,$user);

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
            ->add('game','entity', array(
                "multiple"=>false,
                'class'=>'LunaAtra\CoreBundle\Entity\Game', 
                'property'=>'name', 
                "empty_value" => "-- Choose a game --" ,
                "empty_data" => false ,
                'query_builder' => function(GameRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    }
                ))
            ->add("server")
            ->add("bio")
            ->add("lastImageUpdate", "hidden",array("data" => date('Y-m-d H:i:s') ))
            ->add($privacyForm["name"], $privacyForm["type"], $privacyForm["params"])
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) { 
                $user = $this->get('security.context')->getToken()->getUser();
                $character->setUser($user);
                $em->persist($character);
                $em->flush();
                $activity = new Activity();
                $activity->updateCharacter($user,$character);
                //Check if already exists
                //findLastSameActivity
                $isExists =  $em->getRepository('CoreBundle:Activity')->findLastSameActivity($activity);
                if(count($isExists) > 0)
                {
                    $existingActivity = $isExists[0];
                    $existingActivity->setDate(new \Datetime("now"));
                    $em->persist($existingActivity);
                }else{
                    $em->persist($activity);
                }
                $em->flush();
                return $this->redirect($this->generateUrl('single-character', array("id"=> $character->getId() )));
            }
        }

        return array("form"=>$form->createView(),"character" => $character);
    }


}

