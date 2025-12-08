<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType
{

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',
                EmailType::class, [
                    'label' => $this->translator->trans('user.form.email'),
                    'attr' => [
                        'placeholder' => 'prenom.nom@hotmail.fr',
                        'class' => 'form-control',
                        'autocomplete' => 'email',
                        'inputmode' => 'email',

                    ]])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'first_options' => [
                    'label' => $this->translator->trans('user.form.password'),
                    'attr' => ['class' => 'form-control'],
                ],
                'second_options' => [
                    'label' => $this->translator->trans('user.form.confirm_password'),
                    'attr' => ['class' => 'form-control'],
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => $this->translator->trans('user.form.first_name'),
                'attr' => [
                    'placeholder' => 'Jean',
                    'class' => 'form-control',
                    'inputmode' => 'text',
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => $this->translator->trans('user.form.last_name'),
                'attr' => [
                    'placeholder' => 'DUBOIS',
                    'class' => 'form-control',
                    'inputmode' => 'text',
                ]])
            ->add('phone', TelType::class, [
                'label' => $this->translator->trans('user.form.phone'),
                'attr' => [
                    'placeholder' => '0620458545',
                    'class' => 'form-control',
                    'autocomplete' => 'tel',
                ]])
            ->add('addresses', CollectionType::class, [
                'entry_type' => AddressType::class,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false,
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('user.form.save'),
                'attr' => [
                    'class' => 'btn btn-primary w-100'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
