<?php

namespace Remg\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Remg\UserBundle\Entity\Group;

class GroupType extends AbstractType
{
    /**
     * @var array
     */
    private $roleHierarchy;

    /**
     * Constructor
     */
    public function __construct(array $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = array();

        foreach ($this->roleHierarchy as $role => $children) {
            $roles[sprintf('security.role.%s', strtolower($role))] = $role;
        }

        return $roles;    
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'group.name.label',
                'help' => 'group.name.help',
            ))
            ->add('roles', ChoiceType::class, array(
                'label' => 'group.roles.label',
                'help' => 'group.roles.help',
                'choices' => $this->getRoles(),
                'multiple'  =>  true,
                'expanded'  => true,
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Group::class,
            'translation_domain' => 'admin'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'group';
    }
}
