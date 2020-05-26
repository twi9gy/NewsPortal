<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null, [
                'label'=> 'Название новости',
            ])
            ->add('date_added',null, [
                'label'=> 'Дата добавления',
            ])
            ->add('annotation',null, [
                'label'=> 'Аннотация',
            ])
            ->add('text',null, [
                'label'=> 'Текст',
            ])
            ->add('count_views',null, [
                'label'=> 'Количество просмотров новости',
            ])
            ->add('src_img',FileType::class, [
                'mapped' => false,
                'label'=> 'Фоновое изображение',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Загруите изображение в формате .jpeg или .png',
                    ])
                ],
            ])
            ->add('class',null, [
                'label'=> 'Класс',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}