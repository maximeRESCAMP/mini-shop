<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'placeholder' => 'processeur ryzen 7 5600x',
                    'class' => 'form-control',
                    'inputmode' => 'text'
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug du produit',
                'attr' => [
                    'placeholder' => 'processeur-ryzen-5600x',
                    'class' => 'form-control',
                    'inputmode' => 'text'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'processeur concu pour le gaming',
                    'class' => 'form-control',
                    'rows' => 5
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'scale' => 2,
                'attr' => [
                    'placeholder' => '10.50',
                    'class' => 'form-control',
                    'inputmode' => 'decimal'
                ],
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock',
                'attr' => [
                    'placeholder' => '10',
                    'class' => 'form-control',
                    'inputmode' => 'numeric',
                    'min' => 0
                ]
            ])
            ->add('picture', TextType::class, [
                'label' => 'Image',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                    'inputmode' => 'text'
                ]
            ])
            ->add('save', SubmitType::class,
                [
                    'label' => 'Valider',
                    'attr' => [
                        'class' => 'btn btn-primary w-100',
                    ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
