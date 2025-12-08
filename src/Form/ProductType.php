<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('product.form.product_name'),
                'attr' => [
                    'placeholder' => 'processeur ryzen 7 5600x',
                    'class' => 'form-control',
                    'inputmode' => 'text'
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => $this->translator->trans('product.form.slug'),
                'attr' => [
                    'placeholder' => 'processeur-ryzen-5600x',
                    'class' => 'form-control',
                    'inputmode' => 'text'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => $this->translator->trans('product.form.description'),
                'attr' => [
                    'placeholder' => 'processeur concu pour le gaming',
                    'class' => 'form-control',
                    'rows' => 5
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => $this->translator->trans('product.form.price'),
                'scale' => 2,
                'attr' => [
                    'placeholder' => '10.50',
                    'class' => 'form-control',
                    'inputmode' => 'decimal'
                ],
            ])
            ->add('stock', IntegerType::class, [
                'label' => $this->translator->trans('product.form.stock'),
                'attr' => [
                    'placeholder' => '10',
                    'class' => 'form-control',
                    'inputmode' => 'numeric',
                    'min' => 0
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => $this->translator->trans('product.form.picture'),
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                    'inputmode' => 'text'
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
