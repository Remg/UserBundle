<?php

namespace Remg\UserBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Remg\UserBundle\Constraints\ResettingUsername;
use Remg\UserBundle\Form\DataTransformer\UserToUsernameTransformer;
use Remg\UserBundle\Repository\UserRepository;

class UserPasswordRequestType extends AbstractType
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', TextType::class, [
                'label' => 'user.username.label',
                'help' => 'user.username.help',
                'icon' => 'user',
                'required' => true,
                'mapped' => false,
                'invalid_message' => 'resetting.request.username.invalid',
            ]);

        $builder
            ->get('user')
            ->addModelTransformer(new UserToUsernameTransformer($this->repository));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'user'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'password_request';
    }
}
