<?php

namespace Remg\UserBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('current_password', PasswordType::class, array(
                'attr' => array(
                    'placeholder' => 'change_password.current_password.label',
                ),
                'label' => false,
                'mapped' => false,
                'constraints' => new UserPassword()
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'attr' => array(
                        'placeholder' => 'change_password.password.label',
                    ),
                    'label' => false
                ),
                'second_options' => array(
                    'attr' => array(
                        'placeholder' => 'change_password.password_confirmation.label',
                    ),
                    'label' => false
                ),
                'invalid_message' => 'change_password.password.constraints.mismatch'
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Remg\UserBundle\Entity\User',
            'csrf_token_id' => 'change_password',
            'translation_domain' => 'user'
        ));
    }
}
