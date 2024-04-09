<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * @var Product $product
         */
        $product = $builder->getData();

        $builder
            ->add('name')
            ->add('category', EntityType::class, [
                'class'         => Category::class,
                'query_builder' => function (CategoryRepository $repository) {
                    return $repository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'required'      => false,
                'empty_data'    => null
            ])
            ->add('image', FileType::class, [
                'label'       => 'Preview pictures',
                'mapped'      => false,
                'required'    => false,
                'constraints' =>
                    new File([
                        'maxSize'          => '1024k',
                        'mimeTypes'        => [
                            'image/jpeg',
                            'image/jpg',
                            'image/jpe',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (jpeg, jpg, jpe, png, webp)',
                    ])
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
