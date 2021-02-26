<?php

namespace EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $produits =$this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Produit")->findAll();



        return $this->render('@Ecommerce/Default/index.html.twig',array('produits' => $produits));
    }
    public function indexadminAction()
    {


        $users= $this->getDoctrine()->getManager()->getRepository("AppBundle:User")->findAll();
        $produits= $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Produit")->findAll();
        $paniers= $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Panier")->findAll();
        $commandes= $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Commande")->findAll();




        return $this->render('Default/backoffice.html.twig',
            array("nombreCommandes"=>count($commandes),
                    "nombreProduits"=>count($produits),
                "paniers"=>count($paniers),
                "Users"=>count($users)

                ));
    }

}
