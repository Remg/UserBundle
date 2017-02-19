<?php

namespace Remg\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\BooleanFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateTimeRangeFilterType;

class UserFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextFilterType::class, array(
                'label' => 'user.username.label',
                'condition_pattern' => FilterOperands::STRING_BOTH,
                'icon' => 'edit'
            ))
            ->add('email', TextFilterType::class, array(
                'label' => 'user.email.label',
                'condition_pattern' => FilterOperands::STRING_BOTH,
                'icon' => 'edit'
            ))
            ->add('enabled', BooleanFilterType::class, array(
                'label' => 'user.enabled.label',
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'validation_groups' => array('filtering'),
            'translation_domain' => 'admin'
        ));
    }

    public function getBlockPrefix()
    {
        return;
    }
}
