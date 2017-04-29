<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('ordering', TextType::class, array(
                'attr' => array(
                    'class' => '',
                ),
                'label' => 'Подредба',
                'required' => false
            ))
            ->add('image_form', FileType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Изображение',
                'required' => false
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
