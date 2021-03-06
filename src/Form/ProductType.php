<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Nom du produit',
            'required' => false,
            'attr' => ['placeholder' => 'Veuillez renseigner le nom du produit']
            // 'constraints' => new Assert\NotBlank(['message' => "Validation du formulaire : le nom du produit ne peut pas être vide"])
        ])
            ->add(
                'shortDescription',
                TextareaType::class,
                [
                    'label' => 'Description du produit',
                    'required' => false,
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
                    'required' => false,
                    'divisor' => 100,
                    'attr' => ['placeholder' => 'Renseignez le prix du produit'],
                ]
            )
            ->add(
                'mainPicture',
                UrlType::class,
                [
                    'label' => 'Image du produit',
                    'required' => false,
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
