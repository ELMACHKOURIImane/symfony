<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Cathegorie ;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name' , TextType::class)
            ->add('price' , NumberType::class)
            ->add('description',TextareaType::class)
            ->add('image', FileType::class, [
                'label' => 'Votre image (image file)',
                'mapped' => false, // Tell that there is no Entity to link
                'required' => true,
                'constraints' => [
                  new File([ 
                    'mimeTypes' => [ 
                      'image/jpeg', 
                      'image/gif', 
                      'image/jpg'
                    ],
                    'mimeTypesMessage' => "This document isn't valid.",
                  ])
                ],
              ])
              ->add('category', EntityType::class ,[
                'class' => Cathegorie::class 
              ])
              ->add('Add', SubmitType::class); 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
