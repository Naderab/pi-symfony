<?php

namespace ProductBundle\Form;

use blackknight467\StarRatingBundle\Form\RatingType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use ProductBundle\Entity\Produit2;
use ProductBundle\ProductBundle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Produit2Type extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var TYPE_NAME $builder */
        $builder
            ->add('titre',TextType::class)
            ->add('description',CKEditorType::class,array('label' => '',
                'attr' => array('placeholder' => "Description "),'config' => array(
                    'uiColor' => '#ffffff',
                    'autoload' => false,
                    'async'    => true,
                    'enable' => false,
                    'toolbar' => 'basic',
                    'required' =>'true'
                )))




            ->add('Categorie',EntityType::class,
                array('class'=>'ProductBundle:Categorie','choice_label'=>'libelle','multiple'=>false))


            ->add('SousCategorie',EntityType::class,
                array('class'=>'ProductBundle:SousCategorie','choice_label'=>'lib','multiple'=>false))

            ->add('Prix', IntegerType::class)
            ->add('image', FileType::class, array('data_class' => null));


    } /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProductBundle\Entity\Produit2'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'productbundle_produit2';
    }


}
