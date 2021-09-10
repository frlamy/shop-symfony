<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'Nom du produit',
                'attr' => [
                    'placeholder' => 'Veuillez renseigner le nom du produit'
                ]
            ]
        )
            ->add(
                'shortDescription',
                TextareaType::class,
                [
                    'label' => 'Description du produit',
                    'attr' => [
                        'placeholder' => 'Tapez une description parlante du produit'
                    ]
                ]
            )
            ->add(
                'price',
                MoneyType::class,
                [
                    'label' => 'Prix du produit',
                    'attr' => [
                        'placeholder' => 'Tapez une URL d\'image'
                    ]
                ]
            )
            ->add(
                'mainPicture',
                UrlType::class,
                [
                    'label' => 'Image du produit',
                    'attr' => [
                        'placeholder' => 'Image du produit'
                    ]
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'label' => 'Catégories',
                    'placeholder' => '--Choisir une catégorie--',
                    'class' => Category::class,
                    'choice_label' => function (Category $category) {
                        return strtoupper($category->getName());
                    }
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
