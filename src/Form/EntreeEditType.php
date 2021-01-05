<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EntreeEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('commentaires')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rs_suivideprojetbundle_entreeedit';
    }

    public function getParent()
    {
        return new EntreeType();
    }
}
