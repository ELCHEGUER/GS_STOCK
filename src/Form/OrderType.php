<?php 

namespace App\Form;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // Import the ChoiceType field type

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('order_date', DateType::class, [
                'widget' => 'single_text',
                'disabled' => true,
                'attr' => ['class' => 'form-control'],
                'label' => 'Order Date',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('total_amount', MoneyType::class, [
                'currency' => 'USD',
                'disabled' => true,
                'attr' => ['class' => 'form-control'],
                'label' => 'Total Price',
                'label_attr' => ['class' => 'form-label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
