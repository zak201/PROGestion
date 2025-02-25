<?php

namespace App\Form;

use App\DTO\LotFormData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class LotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $monday = new \DateTime('monday this week');
        $friday = new \DateTime('friday this week');

        $builder
<<<<<<< HEAD
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'en_attente',
                    'En cours' => 'en_cours',
                    'Terminé' => 'termine'
                ],
                'label' => 'Statut du lot'
=======
            ->add('numeroLot', TextType::class, [
                'label' => 'Numéro de lot',
                'attr' => [
                    'placeholder' => 'Saisissez le numéro de lot',
                    'class' => 'form-control'
                ]
>>>>>>> a41ffa60622e7aed453f3d4e9d5deadd3dd2711b
            ])
            ->add('vehiculesInput', TextareaType::class, [
                'label' => 'Numéros de véhicules',
                'attr' => [
                    'placeholder' => 'Collez ou saisissez les numéros de véhicules (séparés par des virgules ou des espaces)',
                    'rows' => 3,
                    'class' => 'form-control'
                ]
            ])
            ->add('camionInput', TextType::class, [
                'label' => 'Identifiant du camion (optionnel)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisissez l\'identifiant du camion (optionnel)',
                    'class' => 'form-control'
                ]
            ])
            ->add('dateExpedition', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date d\'expédition',
                'attr' => [
                    'min' => $monday->format('Y-m-d'),
                    'max' => $friday->format('Y-m-d'),
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LotFormData::class,
        ]);
    }
} 