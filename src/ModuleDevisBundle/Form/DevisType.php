<?php

namespace ModuleDevisBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\NodeVisitor\SafeAnalysisNodeVisitor;

class DevisType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('datevalidite')
            ->add('message',CKEditorType::class,array('label' => '',
                'attr' => array('placeholder' => "message "),'config' => array(
                    'uiColor' => '#ffffff',
                    'autoload' => false,
                    'async'    => true,
                    'enable' => false,
                    'toolbar' => 'basic',
                    'required' =>'true'
                )))
            ->add('libelle')
            ->add('prixunit',TextType::class)
            ->add('qte',TextType::class)
            ->add('totalHT',TextType::class)
            ->add('tVA',ChoiceType::class, array(
                'choices' => array(
                    '0%' => '0',
                    '9%' => '9',
                    '12%' => '12',
                    '19%' => '19',
                )))
            ->add('totalTTC')
        ->add('Sauvgarder',SubmitType::class)
        ->add('Annuler',ResetType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ModuleDevisBundle\Entity\Devis'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'moduledevisbundle_devis';
    }


}
