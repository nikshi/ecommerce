<?php

namespace AppBundle\Form;

use AppBundle\Entity\Promotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('percent', NumberType::class, array(
                'label' => 'Процен на намалението'
            ))
            ->add('startDate', DateTimeType::class, array(
                'label' => 'Начало на промоцията'
            ))->add('endDate', DateTimeType::class, array(
                'label' => 'Край на промоцията'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => Promotion::class,
            ]
        );
    }

    public function getName()
    {
        return 'app_bundle_product_promotion_type';
    }
}
