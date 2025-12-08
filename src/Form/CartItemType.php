<?php

namespace App\Form;

use App\Entity\CartItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartItemType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class,
                [
                    'label'=> $this->translator->trans('cart_item.form.quantity'),
                    'attr' => [
                        'class' => 'form-control w-auto mx-3 ',
                        'min'=>1,
                        'step'=>1,
                        'inputmode' => 'numeric'
                    ]
                ])
            ->add('save', SubmitType::class, ['label'=>$this->translator->trans('general.form.validate'),'attr'=> ['class'=>'btn btn-primary w-100']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CartItem::class,
        ]);
    }
}
