<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Repository\CategoryRepository;
use Doctrine\DBAL\Types\DecimalType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\Mapping as ORM;

class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Име',
                'required'=> false
            ))
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Описание',
                'required'=> false
            ))
            ->add('price', NumberType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Цена',
                'required'=> false
            ))
            ->add('qty', IntegerType::class, array(
                'attr' => array(
                    'class'=>'',
                ),
                'label' => 'Налично количество',
                'required'=> false
            ))
            ->add('category', ChoiceType::class, array(
                'choices'       => array_flip($options['categories']),
                'placeholder'   => 'Изберете категории',
                'label'         => 'Категория',
                'required'=> false
            ))
            ->add('image_form', FileType::class, array(
                'label'   => 'Изображение',
                'required' =>false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => Product::class,
                'categories' => null
            ]
        );

    }


    public function getName()
    {
        return 'app_bundle_product_type';
    }
}
