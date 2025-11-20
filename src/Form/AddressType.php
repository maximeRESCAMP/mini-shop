<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('zipCode', TextType::class, ['label' => 'Code postal', 'attr' => ['placeholder' => '44000']])
            ->add('city', TextType::class, ['label' => 'Ville', 'attr' => ['placeholder' => 'Nantes']])
            ->add('street', TextType::class, ['label' => 'Rue', 'attr' => ['placeholder' => '16 rue des dockers']])
            ->add('country', TextType::class, ['label' => 'Pays', 'attr' => ['placeholder' => 'France']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
