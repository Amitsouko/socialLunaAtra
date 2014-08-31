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
        return array('user' =>$user);
    }
}
