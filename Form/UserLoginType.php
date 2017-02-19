<?php

namespace Remg\UserBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserLoginType extends AbstractType
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
                'required' => true,
                'trim' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'user.password.label',
                'help' => 'user.password.help',
                'icon' => 'barcode',
                'required' => true,
                'trim' => true,
                'constraints' => new NotBlank(),
            ])
            ->add('remember_me', CheckboxType::class, [
                'label' => 'login.remember_me.label',
                'help' => 'login.remember_me.help',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'user',
            'csrf_token_id' => 'authenticate',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }
}
