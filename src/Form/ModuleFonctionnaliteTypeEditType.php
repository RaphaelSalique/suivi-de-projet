<?php

namespace App\Form;

use App\Entity\ModuleFonctionnaliteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ModuleFonctionnaliteTypeEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', null, array('label' => 'libelle'))
            ->add('parent', EntityType::class, array(
                'class' => ModuleFonctionnaliteType::class,
                'property' => 'breadcrumb',
                'label' => 'parent',
                'expanded' => false,
                'required' => false,
                'empty_value' => 'Pas de parent',
                'query_builder' => function (EntityRepository $er) {
                    return $er->qbModulesPourTous();
                },
                ))
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
        return 'modulefonctionnalitetype';
    }
}
