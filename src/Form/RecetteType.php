<?php

namespace App\Form;

use App\Entity\Recettes;
use App\Entity\Ingredients;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=> '5',
                    'maxlength'=> '50'
                ],
                'label'=> 'Nom',
                'label_attr'=> [
                    'class'=> 'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(['min' => 5,'max'=> 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('temps', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1440, // 24 heures en minutes
                ],
                'label' => 'Temps (minutes)',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(['min' => 1, 'max' => 1440]),
                ]
            ])

            ->add('nombrePersonnes', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 50, 
                ],
                'label' => 'nombrePersonnes',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(['min' => 1, 'max' => 50]),
                ]
            ])
            ->add('difficulte', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 5,
                ],
                'label' => 'Difficulté',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(['min' => 1, 'max' => 5]),
                ]
            ])
            ->add('etapes', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Étapes',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('prix',  MoneyType::class,[
                'attr'=>[
                    'class'=> 'form-control'
                ],
                'label'=> 'Prix',
                'label_attr'=> [
                    'class'=> 'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(200)
                    
                ]
            ])
            ->add('favori', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Favori',
                'label_attr' => [
                    'class' => 'form-check-label mt-4'
                ],
            ])
            // ->add('createdAt', DateTimeType::class, [
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            //     'label' => 'Date de création',
            //     'label_attr' => [
            //         'class' => 'form-label mt-4'
            //     ],
            //     'constraints' => [
            //         new Assert\NotBlank(),
            //     ]
            // ])
            ->add('listIngredients', EntityType::class, [
                'class' => Ingredients::class,
                'choice_label' => 'nom', // Remplacez 'nom' par le nom de la propriété que vous souhaitez afficher dans la liste déroulante
                'multiple' => true, // Permet la sélection multiple si nécessaire
                'expanded' => false, // Utilisez 'true' si vous souhaitez une boîte de sélection plutôt qu'une liste déroulante
                'by_reference' => false, // Pour éviter les problèmes avec les collections
                'required' => false, // Si vous souhaitez autoriser la sélection d'aucun ingrédient
            ])

            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class'=> 'btn btn-primary mt-4'
                ],
                'label'=> 'Créer ma recette'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recettes::class,
        ]);
    }
}
