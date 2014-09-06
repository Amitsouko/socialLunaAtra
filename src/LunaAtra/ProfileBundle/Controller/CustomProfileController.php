<?php

namespace LunaAtra\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class CustomProfileController extends Controller
{
    /**
     * @Route("/profile/add-character", name="add-character")
     * @Template("ProfileBundle:Default:add-character.html.twig")
     */
    public function addCharacterAction()
    {
        return array();
    }



}

