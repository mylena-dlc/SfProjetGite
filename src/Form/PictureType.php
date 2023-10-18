<?php

namespace App\Form;

use App\Entity\Gite;
use App\Entity\Picture;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('file', FileType::class)

            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('gite', EntityType::class, [
                'label' => 'Gîte',
                'class' => Gite::class,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn submit'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
