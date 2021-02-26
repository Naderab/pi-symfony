<?php

namespace NewsletterBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre',TextType::class)
            ->add('description',CKEditorType::class,array('label' => '',
                'attr' => array('placeholder' => "description "),'config' => array(
                    'uiColor' => '#ffffff',
                    'autoload' => false,
                    'async'    => true,
                    'enable' => false,
                    'toolbar' => 'basic',
                    'required' =>'true'
                )))


            ->add('contenu',CKEditorType::class,array('label' => '',
                'attr' => array('placeholder' => "contenu "),'config' => array(
                    'uiColor' => '#ffffff',
                    'autoload' => false,
                    'async'    => true,
                    'enable' => false,
                    'toolbar' => 'basic',
                    'required' =>'true'
                )))


            ->add('lienAbonnement',TextType::class)
            ->add('Ajouter',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewsletterBundle\Entity\Newsletter'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'newsletterbundle_newsletter';
    }


}
