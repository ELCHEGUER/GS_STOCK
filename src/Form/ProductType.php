<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Name',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('stock', IntegerType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Stock',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('price', MoneyType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Price',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('imageFile', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Image',
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
            ])
            ->add('Supplier', EntityType::class, [
                'class' => Supplier::class,
                'choice_label' => 'name',  // Assuming 'name' is the field you want to display
                'attr' => ['class' => 'form-control'],
                'label' => 'Supplier',
                'label_attr' => ['class' => 'form-label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
