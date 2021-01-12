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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('commentaires')
        ;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'rs_suivideprojetbundle_entreeedit';
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return EntreeType::class;
    }
}
