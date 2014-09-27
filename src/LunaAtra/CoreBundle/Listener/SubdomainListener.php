<?php 

namespace LunaAtra\CoreBundle\Listener; 

use Symfony\Component\EventDispatcher\EventDispatcher; 
use Symfony\Component\EventDispatcher\Event; 

class SubdomainListener 
{ 
    public function onDomainParse(Event $event) 
    { 
        $request = $event->getRequest(); 

        /* 
         * get subdomain name to set locale 
         */ 

        $explode = explode('.',$_SERVER['HTTP_HOST'],2); 
        // Default lang 
        $lang = 'en'; 

        if (count($explode) == 2) { 
            if($explode[0] == 'fr') 
                $lang = 'fr'; 
            elseif($explode[0] == 'de') 
                $lang = 'de'; 
        } 

        $request->setLocale($lang); 
        unset($explode, $lang); 
    } 
}