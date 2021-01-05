<?php

namespace App\Form;

use App\Entity\ModuleFonctionnaliteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleFonctionnaliteTypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', null, array('label' => 'libelle'))
            ->add('save', SubmitType::class, array('label' => 'envoyer'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ModuleFonctionnaliteType::class,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modulefonctionnalitetypetype';
    }
}
