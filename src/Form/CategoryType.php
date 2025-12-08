<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => $this->translator->trans('category.form.name'), 'attr' => ['placeholder' => 'Informatique', 'class' => 'form-control', 'inputmode' => 'text','autocomplete' => 'off']])
            ->add('slug', TextType::class, ['label' => $this->translator->trans('category.form.slug'), 'attr' => ['placeholder' => 'carte-mere', 'class' => 'form-control', 'inputmode' => 'text']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
