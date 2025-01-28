<?php

namespace App\Form;

use App\Entity\Lot;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'en_attente',
                    'En cours' => 'en_cours',
                    'Terminé' => 'termine'
                ],
                'label' => 'Statut du lot'
            ])
            ->add('vehicules', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'numeroChassis',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('v')
                        ->where('v.lot IS NULL')
                        ->andWhere('v.statut = :statut')
                        ->setParameter('statut', 'disponible');
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lot::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'lot_form'
        ]);
    }
} 