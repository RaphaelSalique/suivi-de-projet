<?php

namespace App\Form;

use App\Entity\PieceJointe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
              'mapped' => false,
              'constraints' => [
                new File([
                ])
              ],
            ])
            ->add('save', SubmitType::class, array('label' => 'envoyer'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => PieceJointe::class,
        ));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'image';
    }
}
