<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email', 'attr' => ['placeholder' => 'prenom.nom@hotmail.fr']])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe','attr' => ['placeholder' => 'U4u0FvpnFPc!93TiG']])
            ->add('firstName', TextType::class,['label'=>'Prénom', 'attr'=> ['placeholder' => 'Jean']])
            ->add('lastName', TextType::class,['label'=>'Nom', 'attr'=> ['placeholder' => 'DUBOIS']])
            ->add('phone', TelType::class,['label'=> 'Télephone','attr'=> ['placeholder' => '0620458545']])
            ->add('deliveryAddresses', CollectionType::class, [
                'entry_type' => AddressType::class,
                'by_reference' => false,
                'entry_options' => ['label' => false],
            ])
            ->add('save', SubmitType::class, ['label'=>'Valider','attr'=> ['class'=>'btn btn-primary w-100']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
