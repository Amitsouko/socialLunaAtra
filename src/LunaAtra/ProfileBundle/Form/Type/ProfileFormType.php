<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LunaAtra\ProfileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('file')
        ->add("bio", null, array("attr" => array("class" => "tinymce") ))
        ->add("lastImageUpdate", "hidden",array("data" => date('Y-m-d H:i:s') ));
    }

    public function getName()
    {
        return 'custom_profile';
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }
}
