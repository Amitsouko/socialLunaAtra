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

 
}

