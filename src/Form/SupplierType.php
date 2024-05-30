<?php 

namespace App\Form;

use App\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Name',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('numero', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Numero',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('adress', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Address',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('CIN', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'CIN',
                'label_attr' => ['class' => 'form-label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Supplier::class,
        ]);
    }
}
