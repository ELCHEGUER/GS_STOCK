<?php
namespace App\Form;

use App\Entity\Model;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Name',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('path', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Path',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('icon', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Icon',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('roles', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Roles',
                'label_attr' => ['class' => 'form-label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Model::class,
        ]);
    }
}
