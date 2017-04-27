<?php

namespace AppBundle\Form;

use AppBundle\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'required' => false,
            ))
            ->add('email', EmailType::class, array(
                'required' => false,
            ))
            ->add('area', TextType::class, array(
                'required' => false,
            ))
            ->add('municipality', TextType::class, array(
                'required' => false,
            ))
            ->add('city', TextType::class, array(
                'required' => false,
            ))
            ->add('phone', TextType::class, array(
                'required' => false,
            ))
            ->add('address', TextType::class, array(
                'required' => false,
            ))
            ->add('postCode', NumberType::class, array(
                'required' => false,
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//                'data_class' => Orders::class,
            ]
        );
    }

    public function getName()
    {
        return 'app_bundle_order_type';
    }
}
