<?php

namespace Remg\UserBundle\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Remg\UserBundle\Entity\ProfilePicture;

class ProfilePictureType extends AbstractType
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $this->entityManager;

        $builder
            ->add('file', FileType::class, [
                'label' => 'profilePicture.file.label',
                'help' => 'profilePicture.file.help',
                'required' => false,
            ])
            ->add('remove', CheckboxType::class, [
                'label' => 'profilePicture.remove.label',
                'help' => 'profilePicture.remove.help',
                'required' => false,
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProfilePicture::class,
            'translation_domain' => 'admin'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'profile_picture';
    }
}
