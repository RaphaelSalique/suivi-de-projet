<?php

namespace App\Form;

use App\DBAL\Types\SeveriteType;
use App\DBAL\Types\StatutType;
use App\DBAL\Types\TypeEntreeType;
use App\Entity\ModuleFonctionnaliteType;
use App\Entity\User;
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
use App\Entity\Entree;

class EntreeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('assigne', EntityType::class, array(
                'class' => User::class,
                'choice_label' => 'nomAffichage',
                'label' => 'assigne',
                ))
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
                'choice_label' => 'breadcrumb',
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
                'choices' => TypeEntreeType::getChoices(),
                ))
            ->add('severite', ChoiceType::class, array(
                'label' => 'severite',
                'choices' => SeveriteType::getChoices(),
                ))
            ->add('statut', ChoiceType::class, array(
                'label' => 'statut',
                'choices' => StatutType::getChoices(),
                ))
            ->add('commentaires', CollectionType::class, array(
                'label' => 'commentaires',
                'entry_type' => CommentaireType::class,
                'allow_add' => true,
                'allow_delete' => false,
                ))
            ->add('testable', CheckboxType::class, array(
                'label' => 'testable',
                'required' => false,
                ))
            ->add('duree', NumberType::class, array(
                'label' => 'duree',
                'scale' => 2,
                'required' => false,
                ))
            ->add('save', SubmitType::class, array('label' => 'envoyer', 'attr' => ['class' => 'button is-link']))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Entree::class,
        ));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'entree';
    }
}
