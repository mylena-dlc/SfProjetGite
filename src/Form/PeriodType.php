<?php

namespace App\Form;

use App\Entity\Period;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PeriodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('startDate', DateType::class, [
            'label' => 'Date d\'arrivée',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd', 
            'required' => true,
        ])
            ->add('endDate', DateType::class, [
                'label' => 'Date d\'arrivée',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd', 
                'required' => true,
            ])
            ->add('supplement', MoneyType::class, [
                'label' => 'Supplément en ',
                'required' => true
            ])

            // ->add('gite')

            ->add('Ajouter', SubmitType::class, [
                'attr' => [
                    'class' => 'btn submit'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Period::class,
        ]);
    }
}
