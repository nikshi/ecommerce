<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Текуща парола',
                'required' => true,
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
        return 'app_bundle_change_password';
    }
}
