<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Име',
            ))
            ->add('description', TextType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Описание',
            ))
            ->add('image', TextType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Изображение',
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => Category::class,
            ]
        );
    }

    public function getName()
    {
        return 'app_bundle_category_type';
    }
}
