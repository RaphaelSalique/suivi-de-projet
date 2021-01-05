<?php

namespace App\Form;

use App\Entity\ModuleFonctionnaliteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class EntreeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('assigne', EntityType::class, array(
//                'class' => 'RSUserBundle:User',
//                'property' => 'nomAffichage',
//                'label' => 'assigne',
//                ))
            ->add('titre', null, array(
                'label' => 'titre',
                'required' => false,
                ))
            ->add('reference', null, array(
                'label' => 'reference',
                'required' => false,
                ))
            ->add('module', EntityType::class, array(
                'class' => ModuleFonctionnaliteType::class,
                'property' => 'breadcrumb',
                'label' => 'module.label_entree',
                'expanded' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->qbModulesPourTous();
                },
                ))
            ->add('description', TextareaType::class, array(
                'label' => 'description',
                ))
            ->add('type', ChoiceType::class, array(
                'label' => 'type',
                'choices' => \App\DBAL\Types\TypeEntreeType::getChoices(),
                ))
            ->add('severite', ChoiceType::class, array(
                'label' => 'severite',
                'choices' => \App\DBAL\Types\SeveriteType::getChoices(),
                ))
            ->add('statut', ChoiceType::class, array(
                'label' => 'statut',
                'choices' => \App\DBAL\Types\StatutType::getChoices(),
                ))
            ->add('commentaires', CollectionType::class, array(
                'label' => 'commentaires',
                'type' => CommentaireType::class,
                'allow_add' => true,
                'allow_delete' => false,
                ))
            ->add('testable', CheckboxType::class, array(
                'label' => 'testable',
                'required' => false,
                ))
            ->add('duree', NumberType::class, array(
                'label' => 'duree',
                'precision' => 2,
                'required' => false,
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
            'data_class' => 'App\Entity\Entree',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'entree';
    }
}
