<?php

namespace EcommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('quantite')
                 ->add('prixTotal')
                 ->add('prixCommande')
                 ->add('idPanier', EntityType::class, array(
                'class' => 'EcommerceBundle\Entity\Panier',
                'choice_label' => 'id'
            ))
                 ->add('idClient', EntityType::class, array(
                'class' => 'AppBundle\Entity\User',
                'choice_label' => 'id'
            ))
                ->add('dateCommande')
                ->add('etat',ChoiceType::class , [

                     'choices'=>[
                         'en cours '=>'en cours',
                         'valide'=>'valide'

                     ]

                    ]


                )



                 ->add('ajout',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EcommerceBundle\Entity\Commande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ecommercebundle_commande';
    }
    public function __toString()
    {
        return (string) $this->getIdProduit();
    }



}
