<?php

namespace LunaAtra\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine;

class IncludeController extends Controller
{
    /**
     * @Route("/include/ownNotifications/{id}", name="include-ownNotifications")
     * @Template("ProfileBundle:Include:ownNotifications.html.twig")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!($user->getId() == $id))
        {

            $user = $em ->getRepository('Profilebundle:User')
                         ->getOneById($id);
        }

        $notifications = array();
        $i = 0;
        foreach($user->getOwnNotifications()  as $notif)
        {
            if($i > 10) break;
            $parameters = array();

            $codedParam = $notif->getData();
            
            //loop to get the value of the key
            foreach( $codedParam as $key => $data)
            {   

                $result= $em ->getRepository($data["entity"])
                            ->findOneById($data["id"]);
                $parameters["%".$key."%"] = $result->get($data["column"]);
            }

            $notifications[]  = array("text" =>$this->get('translator')->trans($notif->getTranslation().".text", $parameters, 'notifications'),
                                        "type" => $notif->getTranslation().".type",
                                        "date" => $notif->getDate()
                                        );
        $i++;

        }
        return array("notifications" => $notifications);
    }

}
