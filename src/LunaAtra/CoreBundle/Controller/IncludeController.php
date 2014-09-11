<?php

namespace LunaAtra\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine;

class IncludeController extends Controller
{
    /**
     * @Route("/include/activities/{id}", name="include-activities")
     * @Template("ProfileBundle:Include:activities.html.twig")
     */
    public function indexAction($id)
    {
        $cache = array();
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if( (!is_object($user) || !$user instanceof UserInterface) || !($user->getId() == $id))
        {
            $user = $em ->getRepository('ProfileBundle:User')
                         ->findOneById($id);
        }
        $cache[] = array("entity" => 'ProfileBundle:User', "id" => $user->getId());
        $entityCache = array();
        $entityCache[] = $user;
        $activities = array();
        $i = 0;
        foreach($user->getActivities()  as $act)
        {
            if($i > 10) break;
            $parameters = array();

            $codedParam = $act->getData();
            
            //loop to get the value of the key
            foreach( $codedParam as $key => $data)
            {   
                $arraySearch = array("entity" => $data["entity"], "id" =>$data["id"]);

                $isInCache = array_search($arraySearch, $cache);

                //check if entity is in cache
                if($isInCache != null)
                {
                    $result = $entityCache[$isInCache];
                }else
                {
                     $result= $em ->getRepository($data["entity"])
                            ->findOneById($data["id"]);
                    //fil in the cache
                    $cache[] = array("entity" => $data["entity"], "id" => $result->getId());
                    $entityCache[] = $result;
                }
               //set parameters
                if(isset($data["isUrl"]) && $data["isUrl"] == true )
                {
                    $uri = $this->get('router')->generate($result->getUrlName(), array($data["urlKey"] => $result->get($data["urlData"])) );
                    $parameters["%".$key."%"] = $uri;
                }else{
                    $parameters["%".$key."%"] = $result->get($data["column"]);
                }
            }
            //fill in the returned array
            $activities[]  = array("text" =>$this->get('translator')->trans($act->getTranslation().".text", $parameters, 'activities'),
                                        "type" => $act->getTranslation().".type",
                                        "date" => $act->getDate()
                                        );
        $i++;

        }
        return array("activities" => $activities, "user" => $user);
    }

}
