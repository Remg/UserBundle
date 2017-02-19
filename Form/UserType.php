<?php

namespace Remg\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Remg\UserBundle\Entity\User;
use Remg\UserBundle\Entity\Group;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'user.username.label',
                'help' => 'user.username.help',
                'icon' => 'user',
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.email.label',
                'help' => 'user.email.help',
                'icon' => 'envelope',
            ])
            ->add('profilePicture', ProfilePictureType::class, [
                'label' => 'user.profilePicture.label',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'user.enabled.label',
                'help' => 'user.enabled.help',
                'required' => false,
            ])
            ->add('groups', EntityType::class, [
                'label' => 'user.groups.label',
                'help' => 'user.groups.help',
                'class' => Group::class,
                'multiple'  =>  true,
                'expanded'  => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => [
                    'label' => 'user.password.label',
                    'help' => 'user.password.help',
                    'icon' => 'barcode',
                ],
                'second_options' => [
                    'label' => 'user.passwordConfirmation.label',
                    'help' => 'user.passwordConfirmation.help',
                    'icon' => 'barcode',
                ],
                'invalid_message' => 'user.passwordConfirmation.constraint.mismatch'
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'translation_domain' => 'admin'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'user';
    }
}
