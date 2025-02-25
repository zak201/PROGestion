<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class VehiculeSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'numeroChassis',
                'placeholder' => 'Sélectionnez un véhicule',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('v')
                        ->where('v.lot IS NULL')
                        ->orderBy('v.numeroChassis', 'ASC');
                },
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
} 