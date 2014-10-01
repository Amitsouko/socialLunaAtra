<?php

namespace LunaAtra\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LunaAtra\ProfileBundle\Entity\Blog;
use LunaAtra\CoreBundle\Entity\Game;
use LunaAtra\CoreBundle\Entity\GameRepository;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/entry-{id}", name="single-post")
     * @Template("ProfileBundle:Blog:show.html.twig")
     */
    public function showEntryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('ProfileBundle:Blog')->findOneById($id);
        if(!is_object($post)){
            throw $this->createNotFoundException('The post does not exist !');
        }
        return array("post" => $post);
    }

    /**
     * @Route("/admin/", name="admin-posts")
     * @Template("ProfileBundle:Blog:show.html.twig")
     */
    public function adminEntriesAction()
    {
        return array();
    }

    /**
     * @Route("/admin/edit/{id}", name="edit-post")
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
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) { 
                $post->setUpdateDate(new \Datetime("now"));
                $em->persist($post);
                $em->flush();
                // $activity = new Activity();
                // $activity->CreateCharacter($user,$character);
                // $isExists =  $em->getRepository('CoreBundle:Activity')->findLastSameActivity($activity);
                // if(count($isExists) > 0)
                // {
                //     $existingActivity = $isExists[0];
                //     $existingActivity->setDate(new \Datetime("now"));
                //     $em->persist($existingActivity);
                // }else{
                //     $em->persist($activity);
                // }
                // $em->persist($activity);
                // $em->flush();
                return $this->redirect($this->generateUrl('single-post', array("id"=> $post->getId() )));
            }
        }

        return array("form" => $form->createview());
        return array();
    }

    /**
     * @Route("/admin/create", name="create-post")
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
            ->add($privacyForm["name"], $privacyForm["type"], $privacyForm["params"])   
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) { 
                $user = $this->get('security.context')->getToken()->getUser();
                $post->setUser($user);
                $post->setPublishedDate(new \Datetime("now"));
                $em->persist($post);
                $em->flush();
                // $activity = new Activity();
                // $activity->CreateCharacter($user,$character);
                // $isExists =  $em->getRepository('CoreBundle:Activity')->findLastSameActivity($activity);
                // if(count($isExists) > 0)
                // {
                //     $existingActivity = $isExists[0];
                //     $existingActivity->setDate(new \Datetime("now"));
                //     $em->persist($existingActivity);
                // }else{
                //     $em->persist($activity);
                // }
                // $em->persist($activity);
                // $em->flush();
                return $this->redirect($this->generateUrl('single-post', array("id"=> $post->getId() )));
            }
        }

        return array("form" => $form->createview());
    }

    /**
     * @Route("/admin/delete/{id}", name="delete-post")
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
                // foreach($character->getActivities() as $activity){
                //     $activity->setCharacterFallback($character);
                //     $em->persist($activity);
                // }
                // $act = new Activity();
                // $act->DeleteCharacter($user,$character);
                // $em->persist($act);
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
}

