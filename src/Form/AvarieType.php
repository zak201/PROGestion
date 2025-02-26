<?php

namespace App\Form;

use App\Entity\Avarie;
use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AvarieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'numeroChassis',
                'label' => 'Véhicule',
                'placeholder' => 'Sélectionnez un véhicule'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['rows' => 4]
            ])
            ->add('responsabilite', TextType::class, [
                'label' => 'Responsabilité'
            ])
            ->add('traitement', TextType::class, [
                'label' => 'Traitement'
            ])
            ->add('bloquage', CheckboxType::class, [
                'label' => 'Bloquage',
                'required' => false
            ])
            ->add('zone_stock', TextType::class, [
                'label' => 'Zone de stock'
            ])
            ->add('lien_compound', TextType::class, [
                'label' => 'Lien compound',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avarie::class,
        ]);
    }
} 