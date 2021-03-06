<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentaireEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('save', SubmitType::class, array('label' => 'envoyer'))
        ;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'commentaireedit';
    }

    public function getParent(): string
    {
        return CommentaireType::class;
    }
}
