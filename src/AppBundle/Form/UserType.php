<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, array(
                'attr' => array(
                    'class'=>'',
                    'placeholder' => 'Въведете Вашия емайл адрес',

                ),
                 'label' => 'Емайл',
            ))
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class'=>'',
                    'placeholder' => 'Въведете Име и Фамилия',

                ),
                'label' => 'Име',
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Двете пароли не съвпадат',
                'options' => array(
                    'attr' => array(
                        'class' => 'password-field'
                    )
                ),
                'required' => true,
                'first_options'  => array(
                    'label' => 'Парола'
                ),
                'second_options' => array(
                    'label' => 'Потвърди паролата'
                ),
            ))
            ->add('area', TextType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Област',
                'required' => false,
            ))
            ->add('municipality', TextType::class, array(
                'attr' => array(
                    'class'=>'',

                ),
                'label' => 'Община',
            ))
            ->add('city', TextType::class, array(
                'attr' => array(
                    'class'=>'',

                ),
                'label' => 'Населено място',
                'required' => false,
            ))
            ->add('post_code', NumberType::class, array(
                'attr' => array(
                    'class'=>'',

                ),
                'label' => 'Пощенски код',
                'required' => false,
            ))
            ->add('address', TextType::class, array(
                'attr' => array(
                    'class'=>'',

                ),
                'label' => 'Адрес',
                'required' => false,
            ))
            ->add('phone', TextType::class, array(
                'attr' => array(
                    'class'=>'',

                ),
                'label' => 'Телефон',
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            ['data_class' => User::class]
        );

    }

    public function getName()
    {
        return 'app_bundle_user_type';
    }
}
