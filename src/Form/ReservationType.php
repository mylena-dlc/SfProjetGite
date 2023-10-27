<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('arrivalDate', HiddenType::class, [
            //     'label' => 'Date d\'arrivée',
            //     "required" => true
            // ])
            // ->add('departureDate')
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                "required" => true,
                
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                "required" => true
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                "required" => true
            ])
            ->add('cp', TextType::class, [
                'label' => 'Code Postal',
                "required" => true
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                "required" => true
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                "required" => true
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone',
                "required" => true
            ])
            // ->add('numberAdult')
            // ->add('numberKid')
            // ->add('total_price')
            // ->add('gite')
            // ->add('user')
            
            ->add('payer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn submit'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
