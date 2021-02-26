<?php

namespace BlogBundle\Form;

use Doctrine\DBAL\Types\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre',\Symfony\Component\Form\Extension\Core\Type\TextType::class,array(
            'label' => '', 'attr' => array('placeholder' => "Titre ")))


            ->add('description',CKEditorType::class,array('label' => '',
                'attr' => array('placeholder' => "Description "),'config' => array(
        'uiColor' => '#ffffff',
        'autoload' => false,
        'async'    => true,
        'enable' => false,
        'toolbar' => 'basic',
        'required' =>'true'
    )))



            ->add('contenu',CKEditorType::class,array('label' => '',
                'attr' => array('placeholder' => "Contenu "),'config' => array(
                    'uiColor' => '#ffffff',
                    'autoload' => false,
                    'async'    => true,
                    'enable' => false,
                    'toolbar' => 'basic',
                    'required' =>'true'
                )))




            ->add('CategorieBlog',EntityType::class,array(
                'class'=>'BlogBundle:CategorieBlog', 'choice_label'=>'nom', 'multiple'=>false))
            ->add('image', FileType::class, array('label' => 'Image(JPG)','data_class'=> null))
            ->add('ajouter',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_post';
    }


}
