<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['label'=>'Nom catégorie','attr'=> ['placeholder' => 'Informatique']])
            ->add('slug',TextType::class,['label'=>'Slug','attr'=> ['placeholder' => 'carte-mere']])
            ->add('save', SubmitType::class, ['label'=>'Valider','attr'=> ['class'=>'btn btn-primary w-100']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
