<?php

namespace App\Form;

use App\Entity\Avarie;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvarieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'numeroChassis',
                'placeholder' => 'Sélectionnez un véhicule'
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('responsabilite', ChoiceType::class, [
                'choices' => [
                    'Interne' => 'interne',
                    'Client' => 'client',
                    'Transporteur' => 'transporteur'
                ]
            ])
            ->add('traitement', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'en_attente',
                    'En cours' => 'en_cours',
                    'Terminé' => 'termine'
                ]
            ])
            ->add('bloquage', CheckboxType::class, [
                'required' => false
            ])
            ->add('zone_stock', TextType::class)
            ->add('lien_compound', TextType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avarie::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'avarie_form'
        ]);
    }
} 