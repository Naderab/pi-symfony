<?php

namespace ModuleDevisBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class offreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')
            ->add('type',ChoiceType::class, array(
                'choices' => array(
                    'Offre de recyclage' => 'Offre de recyclage',
                    'Demande des produits' => 'Demande des produits',

                )))
            ->add('description',CKEditorType::class,array('label' => '',
                'attr' => array('placeholder' => "description "),'config' => array(
                    'uiColor' => '#ffffff',
                    'autoload' => false,
                    'async'    => true,
                    'enable' => false,
                    'toolbar' => 'basic',
                    'required' =>'true'
                )))
            ->add('dateoffre')
            ->add('datevalidite')
            ->add('Enregistrer',SubmitType::class)
            ->add('Annuler',ResetType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ModuleDevisBundle\Entity\offre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'moduledevisbundle_offre';
    }


}
