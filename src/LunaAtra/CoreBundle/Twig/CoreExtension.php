<?php
namespace LunaAtra\CoreBundle\Twig;


class CoreExtension  extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('getFirstSrc', array($this, 'getFirstSrc')),
        );
    }

    public function getFirstSrc($text)
    {
        preg_match('/<img.+src=[\'"](?P<src>.+)[\'"].*>/i', $text, $image);
        return (isset($image['src'])) ? $image['src'] : "";
    }

    public function getName()
    {
        return 'core_extension';
    }
}