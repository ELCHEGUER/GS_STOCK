<?php

namespace App\Form;

use App\Entity\Payement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // Import the ChoiceType field type


class PayementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FullName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Full Name',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('Email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('address', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Address',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('payementDate', DateType::class, [
                'widget' => 'single_text',
                'disabled' => true,
                'attr' => ['class' => 'form-control'],
                'label' => 'Payment Date',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('amount', MoneyType::class, [
                'currency' => 'USD',
                'attr' => ['class' => 'form-control'],
                'label' => 'Amount',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('CVV', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'CVV',
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 3,
                        'exactMessage' => 'Le champ CVV doit contenir exactement 3 caractères.',
                    ]),
                    new NotBlank([
                        'message' => 'Le champ CVV ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('acceptedCard', ChoiceType::class, [
                'choices' => [
                    'PayPal' => 'paypal',
                    'Credit Card' => 'credit_card',
                ],
                'expanded' => true,
                'label' => 'Accepted Card',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('creditNumber', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Credit Card Number',
                'label_attr' => ['class' => 'form-label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payement::class,
        ]);
    }

}
