<?php

namespace LunaAtra\ProfileBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ProfileBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
