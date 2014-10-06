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
use LunaAtra\ProfileBundle\Entity\Blog;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * @Route("/profile/backoffice")
 */
class BackofficeController extends Controller
{
    /**
     * @Route("/", name="show-backoffice")
     * @Template("ProfileBundle:Backoffice:show.html.twig")
     */
    public function profileAction()
    {
       
        return array();
    }



   /**
     * @Route("/edit-cover", name="edit-cover")
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


















    /*


        BLOG



    */

       /**
     * @Route("/blog/", name="admin-blog")
     * @Template("ProfileBundle:Blog:admin.html.twig")
     */
    public function adminEntriesAction()
    {
        return array();
    }

    /**
     * @Route("/blog/edit/{id}", name="edit-post")
     * @Template("ProfileBundle:Blog:create.html.twig")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('ProfileBundle:Blog')->findOneById($id);
        $privacyManager = $this->container->get("privacy.manager");
        $privacyForm = $privacyManager->getEditPrivacyForm();
        $user = $this->get('security.context')->getToken()->getUser();

        if( $post->getUser() != $user)
        {
            return $this->redirect($this->generateUrl('single-post', array("id"=> $post->getId() )));
        }

        $form = $this->createFormBuilder($post);
        $form = $form->add("title")
        ->add("content")
                    ->add('game','entity', array(
                "multiple"=>false,
                'class'=>'LunaAtra\CoreBundle\Entity\Game', 
                'property'=>'name', 
                "empty_value" => "-- Choose a game --" ,
                "required" => false,
                "empty_data" => null ,
                'query_builder' => function(GameRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    }
                ))
        ->add($privacyForm["name"], $privacyForm["type"], $privacyForm["params"])  
        ->add('save', 'submit', array(
            "label" => "Sauvegarder",
            'attr' => array('class' => 'green-button'),
        ));

        if($post->getDraft() == true)
        {
            $form = $form->add('draft', 'submit', array(
            "label" => "enregistrer les modification du brouillon",
            'attr' => array('class' => 'blue-button'),
        ));
        }
        
         $form = $form->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) { 
                if($form->get('save')->isClicked()){
                    $post->setDraft(false);
                    $activity = new Activity();
                    if($post->getPublishedDate() == null){
                        $post->setPublishedDate(new \Datetime("now"));
                        $em->flush();
                        $activity->createPost($user,$post);
                    }else {
                        $post->setUpdateDate(new \Datetime("now"));
                        $activity->updatePost($user,$post);
                    }
                    
                    
                    
                    $isExists =  $em->getRepository('CoreBundle:Activity')->findLastSameActivity($activity);
                    if(count($isExists) > 0)
                    {
                        $existingActivity = $isExists[0];
                        $existingActivity->setDate(new \Datetime("now"));
                        $em->persist($existingActivity);
                    }else{
                        $em->persist($activity);
                    }
                }else {
                    $post->setDraft(true);
                }
                $em->persist($post);
                $em->flush();
                
                $em->flush();
                return $this->redirect($this->generateUrl('single-post', array("id"=> $post->getId() )));
            }
        }

        return array("form" => $form->createview(), "post" => $post);
        return array();
    }

    /**
     * @Route("/blog/create", name="create-post")
     * @Template("ProfileBundle:Blog:create.html.twig")
     */
    public function createEntryAction(Request $request)
    {
        $post = new Blog();
        $em = $this->get('doctrine')->getManager();
        $privacyManager = $this->container->get("privacy.manager");
        $privacyForm = $privacyManager->getPrivacyForm();

        $form = $this->createFormBuilder($post);
        $form = $form->add("title")
        ->add("content")
                    ->add('game','entity', array(
                "multiple"=>false,
                'class'=>'LunaAtra\CoreBundle\Entity\Game', 
                'property'=>'name', 
                "empty_value" => "-- Choose a game --" ,
                "required" => false,
                "empty_data" => null ,
                'query_builder' => function(GameRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    }
                ))
            ->add('save', 'submit', array(
                "label" => "Publier",
                'attr' => array('class' => 'green-button'),
            ))
            ->add('draft', 'submit', array(
                "label" => "Créer un brouillon",
                'attr' => array('class' => 'blue-button'),
            ))
            ->add($privacyForm["name"], $privacyForm["type"], $privacyForm["params"])   
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) { 
                $user = $this->get('security.context')->getToken()->getUser();
                
                $post->setUser($user);
                
                //Set if is draft or not
                if($form->get('save')->isClicked()){
                    $post->setDraft(false);
                    $post->setPublishedDate(new \Datetime("now"));
                    $em->persist($post);
                    $em->flush();
                    $activity = new Activity();
                    $activity->createPost($user,$post);
                    $em->persist($activity);
                }else {
                    $post->setDraft(true);
                    $em->persist($post);
                }

                
                
                $em->flush();
                return $this->redirect($this->generateUrl('single-post', array("id"=> $post->getId() )));
            }
        }

        return array("form" => $form->createview());
    }

    /**
     * @Route("/blog/delete/{id}", name="delete-post")
     * @Template("CoreBundle:Delete:confirm.html.twig")
     */
    public function deleteEntryAction($id, Request $request)
    {
        $securityContext = $this->container->get('security.context');
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('ProfileBundle:Blog')->findOneById($id);
        $user = $securityContext->getToken()->getUser();

        if(!is_object($post)){
            throw $this->createNotFoundException('Post doesn\'t exist.');
        }

        if( $user != $post->getUser()){
            throw $this->createNotFoundException('You don\'t own the post.');
        }

        $dataForm = array();
        $form = $this->createFormBuilder($dataForm)
                ->add('id', 'hidden', array("data" => $post->getId()))
                ->add('type', 'hidden', array("data" => "blog"))
                ->add('owner', 'hidden', array("data" => $user->getId()))
        ->getForm();

        if($request->isMethod('POST')) {
            $form->bind($request);
            $data = $form->getData();
            if($post->getId() == $data["id"] && $user->getId() == $data["owner"] && $post->getUser()->getId() == $user->getId() )
            {
                if(!$post->getDraft()){
                    foreach($post->getActivities() as $activity){
                        $activity->setPostFallback($post);
                        $em->persist($activity);
                    }
                    $act = new Activity();
                    $act->deletePost($user,$post);
                    $em->persist($act);
                }
                $em->remove($post);
                $em->flush();
                return $this->redirect($this->generateUrl('user-blog', array("username" => $user->getUsername())));
            }
        }

        $data = array(
                "name" => $post->getTitle(),
                "urlOfDelete" => $uri = $this->get("router")->generate('single-post', array('id' => $post->getId())) 
            );

        return $this->render(
                'CoreBundle:Delete:confirm.html.twig',
                array( "data" => $data, "form" => $form->createView()));
    }




















    /*

    CHARACTER

    */


    /**
     * @Route("/character", name="backoffice-characters")
     * @Template("ProfileBundle:Backoffice:characters.html.twig")
     */
    public function showCharacterAction()
    {
        return array();
    }

    /**
     * @Route("/character/reorder", name="reorder-characters")
     * @Template()
     */
    public function reorderCharacterAction(Request $request)
    {
        $order = $request->request->get("order");
        $em = $this->get('doctrine')->getManager();
        $order = explode(",",$order);
        $response = new JsonResponse();
        //select charcters
        $characters =  $em->getRepository('ProfileBundle:Charact')->findBy(array("id" => $order, "user" => $this->getUser() ));
        if(count($order) != count($characters) ){
            return $response->setData(array(
                'error' => "Problem with characters id (you don't own all characters)"
            ));
        }

        foreach($order as $position => $id)
        {
            foreach($characters as $charact)
            {
                if($charact->getId() == $id)
                {
                    $charact->setOrdre($position );
                    $em->persist($charact);
                }
            }
        }


        $em->flush();
        return $response->setData(array(
            'success' => "ok"
        ));
    }


    /**
     * @Route("/character/add", name="add-character")
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
            ->add("birthday", null,array(
                        'years' => range("1990", date('Y')),
                    ))
            ->add("file")
            ->add("class")
            ->add($privacyForm["name"], $privacyForm["type"], $privacyForm["params"])
            ->add("level")
            ->add('game','entity', array(
                "multiple"=>false,
                'class'=>'LunaAtra\CoreBundle\Entity\Game', 
                'property'=>'name', 
                "empty_value" => "-- Choose a game --" ,
                "required" => false,
                "empty_data" => null ,
                'query_builder' => function(GameRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    }
                ))
            ->add("server")
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
                $em->persist($activity);
                $em->flush();
                return $this->redirect($this->generateUrl('user-characters', array("username"=> $user->getUsername() )));
            }
        }

        return array("form"=>$form->createView());
    }


 
    /**
     * @Route("/character/edit/{id}", name="edit-character")
     * @Template("ProfileBundle:Default:add-character.html.twig")
     */
    public function editCharacterAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $character = $em->getRepository('ProfileBundle:Charact')->findOneById($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $privacyManager = $this->container->get("privacy.manager");
        $privacyForm = $privacyManager->getEditPrivacyForm();

        $privacyManager->canISee($character,$user);

        if($character->getUser() != $user)
        {
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        $form = $this->createFormBuilder($character)
            ->add("name")
            ->add("birthday", null,array(
                        'years' => range("1990", date('Y')),
                    ))
            ->add("file")
            ->add("class")
            ->add("level")
            ->add('game','entity', array(
                "multiple"=>false,
                'class'=>'LunaAtra\CoreBundle\Entity\Game', 
                'property'=>'name', 
                "empty_value" => "-- Choose a game --" ,
                "empty_data" => null ,
                "required" => false,
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
                //Check if already e
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
