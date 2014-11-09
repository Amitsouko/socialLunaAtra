<?php

namespace LunaAtra\ProfileBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LunaAtra\ProfileBundle\Entity\Blog;
use LunaAtra\CoreBundle\Entity\Game;
use LunaAtra\CoreBundle\Entity\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use LunaAtra\ProfileBundle\Entity\Friends;
/**
 * @Route("/ajax/friends")
 */
class FriendsController extends Controller
{
    /**
     * @Route("/add/{id}", name="ajax-friend-add")
     * @Template("ProfileBundle:Blog:show.html.twig")
     */
    public function showEntryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ProfileBundle:User')->findOneById($id);
        $connectedUser = $this->get('security.context')->getToken()->getUser();

        // Verifications
        $friendLink = $em->getRepository('ProfileBundle:Friends')->getFriendStatus($connectedUser,$user);

        if(count($friendLink) != null)
        {
            $friendLink = $friendLink[0];
            //they are already friends or request sent
            switch($this->fnFriendStatus($friendLink, $connectedUser))
            {
                case "samePersonne":
                    return "you cant be your own friend";
                    break;
                case "friends":
                    return "you are already friend";
                    break;
                case "sender":
                    return "you already sent a rquest";
                    break;
                case "receiver":
                    $friendLink->setStatus(1);
                    $friendLink->setDate(new \DateTime("now"));
                    $em->persist($friendLink);
                    $em->flush();
                    break;
            }
        }else{
            $friendLink = new Friends();
            $friendLink->setStatus(0);
            $friendLink->setRequester($connectedUser);
            $friendLink->setRequested($user);
            $em->persist($friendLink);
            $em->flush();
        }
        return array();

    }



    public function fnFriendStatus($friendLink, $connectedUser)
    {
        if($friendLink == false){
            return "samePersonne";
        } 
        if(count($friendLink) > 0 )
        {
            if($friendLink->getStatus() == 1)
            {
                return "friends";
            }else if($friendLink->getRequester() == $connectedUser)
            {
                return "sender";
            }else{
                return "receiver";
            }
        }else{
            return "noFriend";
        }
    }


 
}

