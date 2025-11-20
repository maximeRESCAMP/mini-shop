<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'attr' => ['placeholder' => 'processeur ryzen 7 5600x']
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug du produit',
                'attr' => ['slug' => 'processeur-ryzen-']
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'processeur concu pour le gaming']
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix',
                'attr' => ['placeholder' => '10.50' ],
            ])
            ->add('stock', TextType::class, [
                'label' => 'Stock',
                'attr' => ['placeholder' => '10']
            ])
            ->add('image', TextType::class, [
                'label' => 'Image',
                'required' => false

            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider', 'attr' => ['class' => 'btn btn-primary w-100']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
