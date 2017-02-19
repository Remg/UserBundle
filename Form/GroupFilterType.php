<?php

namespace Remg\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;

class GroupFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextFilterType::class, [
                'label' => 'group.name.label',
                'condition_pattern' => FilterOperands::STRING_BOTH,
                'icon' => 'edit'
            ])
            ->add('roles', TextFilterType::class, [
                'label' => 'group.roles.label',
                'condition_pattern' => FilterOperands::STRING_BOTH,
                'icon' => 'edit'
            ])
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
}
