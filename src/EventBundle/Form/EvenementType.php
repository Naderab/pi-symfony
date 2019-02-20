<?php

namespace EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom',TextType::class, array(
            'label' => '',
            'attr' => array('placeholder' => "Nom de l'évenement")))
            ->add('dateDebut',DateTimeType::class)

            ->add('dateFin',DateTimeType::class)


            ->add('adresse',TextareaType::class ,  array(
                'label' => '',
                'attr' => array('placeholder' => "Adresse ")))
            ->add('description',TextareaType::class ,  array(
                'label' => '',
                'attr' => array('placeholder' => "Description ")))

            ->add('minParticipants' ,TextType::class,  array(
                'label' => '',
                'attr' => array('placeholder' => "Minimum de participants ")))
            ->add('maxParticipants',TextType::class ,  array(
                'label' => '',
                'attr' => array('placeholder' => "Maximum de participants ")))
            ->add('image', FileType::class, array('label' => 'Image(JPG)'))
            ->add('submit',SubmitType::class)

        ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventBundle\Entity\Evenement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eventbundle_evenement';
    }


}
