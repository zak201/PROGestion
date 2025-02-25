<?php

namespace App\Form;

use App\Entity\Lot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LotEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $monday = new \DateTime('monday this week');
        $friday = new \DateTime('friday this week');

        $builder
            ->add('dateExpedition', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date d\'expédition',
                'attr' => [
                    'min' => $monday->format('Y-m-d'),
                    'max' => $friday->format('Y-m-d'),
                    'class' => 'form-control'
                ]
            ])
            ->add('vehiculesInput', TextareaType::class, [
                'mapped' => false,
                'label' => 'Numéros de véhicules',
                'attr' => [
                    'placeholder' => 'Collez ou saisissez les numéros de véhicules (séparés par des virgules ou des espaces)',
                    'rows' => 3,
                    'class' => 'form-control'
                ],
                'data' => implode(', ', $options['vehicules_numeros'])
            ])
            ->add('camionInput', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Identifiant du camion (optionnel)',
                'attr' => [
                    'placeholder' => 'Saisissez l\'identifiant du camion',
                    'class' => 'form-control'
                ],
                'data' => $options['camion_immatriculation']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lot::class,
            'validation_groups' => ['Default'],
            'vehicules_numeros' => [],
            'camion_immatriculation' => null
        ]);
    }
} 