<?php

namespace LunaAtra\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


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
        $connectedUser = $this->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('ProfileBundle:User')->findOneByUsername($username);
        $em->remove($user);
        if(!is_object($user)){
            throw $this->createNotFoundException('The user does not exist !');
        }
        $friendLink = $em->getRepository("ProfileBundle:Friends")->getFriendStatus($connectedUser, $user);

        return array('user' =>$user, "pagename" => "Profil", "friendLink" => $friendLink);
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

    /**
     * @Route("/{username}/blog", name="user-blog")
     * @Template("ProfileBundle:Default:blog.html.twig")
     */
    public function BlogAction($username, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $owner = $em->getRepository('ProfileBundle:User')->findOneByUsername($username);
        $privacyManager = $this->container->get("privacy.manager");


        $page = $request->get("page");
        $blogEntries = $this->container->getParameter("blog_entries"); 
        if($page == null || is_nan($page) || $page < 1) $page = 1;
        $offset = ($blogEntries * $page)  - $blogEntries;
        
        $connectedUser = $this->get('security.context')->getToken()->getUser();

        $postNumber = 0;
        //get custom post
        if(!is_object($connectedUser))
        {   //NOT CONNECTED USER
            $posts = $em->getRepository('ProfileBundle:Blog')->getPublicPosts($owner, $offset, $blogEntries);
        }else if($connectedUser == $owner)
        {   // USER IS THE OWNER OF THE BLOG
            $posts = $em->getRepository('ProfileBundle:Blog')->getPaginationPage($owner, $offset, $blogEntries);
            $postNumber = $em->getRepository('ProfileBundle:Blog')->getPostPostNumber($owner);
        }else{
            // USER IS CONNECTED BUT NOT THE OWNER
            $array = $privacyManager->getUserRightOnContent($owner);
            $posts = $em->getRepository('ProfileBundle:Blog')->getPostByPrivacy($array, $owner,$offset, $blogEntries);
        }
        return array('user' =>$owner,"pagename" => "Blog", "posts" => $posts, "postNumber" => $postNumber);
    }
}
