<?php

namespace App\Form;

use App\Entity\Vehicule;
use App\Entity\Lot;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroChassis', TextType::class, [
                'label' => 'Numéro de chassis',
                'constraints' => [
                    new NotBlank(['message' => 'Le numéro de chassis est obligatoire']),
                    new Length([
                        'min' => 17,
                        'max' => 17,
                        'exactMessage' => 'Le numéro de chassis doit contenir exactement {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'constraints' => [
                    new NotBlank(['message' => 'La marque est obligatoire'])
                ]
            ])
            ->add('couleur', TextType::class, [
                'label' => 'Couleur',
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Disponible' => 'disponible',
                    'Bloqué' => 'bloque', 
                    'En maintenance' => 'en_maintenance'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le statut est obligatoire'])
                ]
            ])
            ->add('lot', EntityType::class, [
                'class' => Lot::class,
                'choice_label' => 'numeroLot',
                'required' => false,
                'placeholder' => 'Sélectionnez un lot'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'vehicule_form'
        ]);
    }
} 