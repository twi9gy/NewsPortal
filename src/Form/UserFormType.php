<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'label'=> 'Email',
                'attr' => [
                    'class' => 'form-control-private_office',
                    'placeholder' => 'Введите email'
                ],
                ])
            ->add('full_name', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[а-я-А-Я]+ +[а-я-А-Я]+ +[а-я-А-Я]+/',
                        'message' => 'ФИО может состоять только из букв русского алфавита.',
                    ]),
                ],
                'label'=> 'ФИО',
                'attr' => [
                    'class' => 'form-control-private_office',
                    'placeholder' => 'Введите ФИО'
                ],
            ])
            ->add('phone', null, [
                'constraints' => [
                    new Length([
                        'max' => '12',
                        'maxMessage' => 'Телефон состоит из 12 симаолов +7**********',
                    ]),
                    new Regex([
                        'pattern' => '/^\+7[0-9]+/',
                        'message' => 'Телефон имеет формат: +7**********',
                    ]),
                ],
                'label'=> 'Телефон',
                'attr' => [
                    'class' => 'form-control-private_office',
                    'placeholder' => 'Введите телефон'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
