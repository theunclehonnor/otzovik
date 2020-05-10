<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('agreeTerms', RadioType::class, [
                'mapped' => false,
                'label' => 'Согласине на обработку персональных данных',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Регистрация без согласия невозможна.'
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'constraints' => [
                        new Length([
                            'min' => 3,
                            'minMessage' => 'Пароль должен состоять не менее чем из {{ limit }} символов.',
                            'max' => 4096,
                        ])
                    ],
                    'label' => 'Пароль',
                ),
                'second_options'=> array(
                    'label'=> 'Повторо пароля',
                ),
                'invalid_message' => 'Пароли не совпадают.'
            ))
            ->add('name', TextType::class, array(
                'label' => 'Имя'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
