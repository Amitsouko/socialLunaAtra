<?php

namespace LunaAtra\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->render(
                'CoreBundle:Default:index-connected.html.twig',
                array());
        }else{
            return $this->render(
                'CoreBundle:Default:index.html.twig',
                array());
        }

        
    }

    /**
     * @Route("/embbed/header-profile", name="embbed-profile")
     * @Template()
     */
    public function embbedProfileAction()
    {
       $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;

        return $this->render(
            'CoreBundle:include:top-bar.html.twig',
            array('csrf_token' => $csrfToken ));
    }
}
