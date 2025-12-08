<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddressType extends AbstractType
{
    public function __construct(private readonly RequestStack $requestStack, private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale() ?? 'en';
        $countries = array_flip(Countries::getNames($locale));
        $builder
            ->add(
                'country', ChoiceType::class, [
                    'choices' => $countries,
                    'label' => $this->translator->trans('delivery.address.form.country'), 'attr' => [
                        'class' => 'form-control',
                    ]
                ]
            )
            ->add('zipCode', TextType::class, ['label' => $this->translator->trans('delivery.address.form.zip_code'), 'attr' => ['placeholder' => '44000', 'class' => 'form-control', 'autocomplete' => 'postal-code', 'inputmode' => 'numeric']])
            ->add('city', TextType::class, ['label' => $this->translator->trans('delivery.address.form.city'), 'attr' => ['placeholder' => 'Nantes', 'class' => 'form-control', 'autocomplete' => 'address-level2', 'inputmode' => 'text']])
            ->add('street', TextType::class, ['label' => $this->translator->trans('delivery.address.form.street'), 'attr' => ['placeholder' => '16 rue des dockers', 'autocomplete' => 'street-address', 'inputmode' => 'text']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
