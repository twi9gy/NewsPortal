<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-z]+/i',
                        'message' => 'Имя пользователя может состоять только из букв латинского алфавита.',
                    ]),
                ],
                'label'=> 'Имя пользователя',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Введите имя пользователя'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Вы должны согласиться с обработкой персональных данных.',
                    ]),
                ],
                'label'=> 'Согласие с обработкой персональных данных.',
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'required' => true,
                'type' => PasswordType::class,
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите пароль',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ваш пароль должен состоять из {{ limit }} символов.',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                ],
                'first_options'  => [
                    'label' => 'Пароль',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Введите пароль'
                    ]
                ],
                'second_options' => [
                    'label' => 'Повторный пароль',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Введите повторный пароль.'
                    ]
                ],
                'invalid_message' => 'Пароли не совпадают.',
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите Email.',
                    ]),
                    new Email([
                        'message' => 'Неверно указан Email.'
                    ])
                ],
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Введите email'
                ]
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
