<?php

namespace AppBundle\Controller;

use NewsletterBundle\Entity\Abonnement;
use NewsletterBundle\Form\AbonnementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $abonn =  new Abonnement();
        $form=$this->createForm(AbonnementType::class,$abonn);
        $form->handleRequest($request);
        if($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($abonn);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('default/index.html.twig',array('form'=>$form->createView()));
    }

    /**
     * @Route("/admin", name="homepageadmin")
     */
    public function adminAction(Request $request)
    {
        $users= $this->getDoctrine()->getManager()->getRepository("AppBundle:User")->findAll();
        $produits= $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Produit")->findAll();
        $paniers= $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Panier")->findAll();
        $commandes= $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Commande")->findAll();

        // replace this example code with whatever you need
        return $this->render('Default/backoffice.html.twig',
            array("nombreCommandes"=>count($commandes),
                "nombreProduits"=>count($produits),
                "paniers"=>count($paniers),
                "Users"=>count($users)

            ));
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/contact.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/aboutUs.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
