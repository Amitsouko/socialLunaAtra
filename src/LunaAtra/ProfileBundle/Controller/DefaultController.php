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
        if(!is_object($user)){
            throw $this->createNotFoundException('The user does not exist !');
        }
        return array('user' =>$user, "pagename" => "Profil");
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
    public function BlogAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $owner = $em->getRepository('ProfileBundle:User')->findOneByUsername($username);
        $privacyManager = $this->container->get("privacy.manager");

        $connectedUser = $this->get('security.context')->getToken()->getUser();

        $postNumber = null;
        //get custom post
        if(!is_object($connectedUser))
        {
            $posts = $em->getRepository('ProfileBundle:Blog')->getPublicPosts($owner);
        }else if($connectedUser == $owner)
        {
            $posts = $owner->getPosts();
            $postNumber = $em->getRepository('ProfileBundle:Blog')->getPostPostNumber($owner);
        }else{
            $array = $privacyManager->getUserRightOnContent($owner);
            $posts = $em->getRepository('ProfileBundle:Blog')->getPostByPrivacy($array, $owner);
        }
        return array('user' =>$owner,"pagename" => "Blog", "posts" => $posts, "postNumber" => $postNumber);
    }
}
