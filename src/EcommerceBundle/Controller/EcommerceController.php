<?php

namespace EcommerceBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use EcommerceBundle\Entity\Commande;
use EcommerceBundle\Entity\Panier;
use EcommerceBundle\Form\CommandeType;
use EcommerceBundle\Form\PanierType;
use EcommerceBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Mukadi\Chart\Utils\RandomColorFactory;
use Mukadi\Chart\Chart;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EcommerceController extends Controller
{


    public function addProductToCartAction(Request $request){


            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $user_id = $user->getId();


            $em= $this->getDoctrine()->getManager();

            $product=   $em->getRepository("EcommerceBundle:Produit")->find($request->get('produit_id'));

            $panier = $product->getIdPanier();
            $panier->setNom($product->getTitre());

            $panier->setIdClient($user_id);

              return $this->AffichePanierAction();
    }

    public function validerCommandeAction(Request $request)
    {

        $commande = new Commande();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $user_id = $user->getId();

        $panier_id= $this->getDoctrine()->getManager()
            ->getRepository("EcommerceBundle:Panier")->findOneBy(["idClient"=>$user_id]);

        $products = $this->getDoctrine()->getManager()
            ->getRepository("EcommerceBundle:Produit")->findBy(["idPanier"=>$panier_id]);

        $commande->setIdClient($user);

        $commande->setIdPanier($panier_id);
            $prixTotal = 0;
        try {
            $commande->setDateCommande(new \DateTime('now'));
        } catch (\Exception $e) {
        }

        $commande->setEtat("validée");

        foreach($products as $p)
        {
           $commande->addProduct($p);
           $commande->addQuantite($p->getQuantite());
           $prixTotal += $p->getQuantite()*$p->getPrix();
        }

        $commande->setPrixTotal($prixTotal);
        $commande->setPrixCommande($prixTotal);
        $em= $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

        //var_dump($commande);

        return $this->AffichePanierAction();
    }

    public function checkoutCommandeAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $user_id = $user->getId();

        $commande =$this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Commande")
            ->findOneBy(["etat"=>"validée","idClient"=>$user_id]);

       $products = $commande->getProducts();

        // var_dump($products);
        $subTotal =0;
        foreach ($products as $p){
            $subTotal +=$p->getPrix();

        }
        $prixTotal = $subTotal+10;



        return $this->render("@Ecommerce\Panier\checkout.html.twig",array("products"=>$products,
            "subTotal"=>$subTotal,"prixTotal"=>$prixTotal,"commandID"=>$commande->getId()));
    }


    public function generatePDFAction(Request $request)
    {


        $commandeID = $request->get("commandID");
        $produits = $this->getDoctrine()->getRepository("EcommerceBundle:Produit")->findBy(["idCommande"=>$commandeID]);

        $prixTotal = $request->get("prixTotal");
        $shippingCost = 10;
        $quantite = 2;
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

      $html=
            $this->renderView(
                '@Ecommerce\Panier\Facture.html.twig',
                array(
                    //'panierID'  => $panierId,
                    'commandeId'=>$commandeID,
                    'produits'=>$produits,
                    'prixTotal'=>$prixTotal,
                    'shipping'=>$shippingCost,
                    'quantite'=>$quantite,
                    'user' =>$user
                )
            );


        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),

            'file.pdf'
        );


    }


    public function SupprimerPanierAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('EcommerceBundle:Panier')
            ->find($id);
        $m->remove($mod);
        $m->flush();
        return $this->redirect($this->generateUrl('ecommerce_homepage'));

    }
    public function AffichePanierAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $panier = $em->getRepository('EcommerceBundle:Panier')->findOneBy(['idClient' => $id]);
        $id_panier = $panier->getId();

        $products = $em->getRepository("EcommerceBundle:Produit")->findBy(['idPanier' => $id_panier]);

        $subTotal = 0;
      foreach ($products as $p){
          $subTotal += $p->getQuantite()* $p->getPrix();

      }
        $prixTotal = $subTotal+10;

        return $this->render('@Ecommerce\Panier\PanierDetail.html.twig'
            , array(
                 'products' => $products,'subTotal'=>$subTotal,'prixTotal'=>$prixTotal
            ));



    }
    public function DetailsPanierAction() {
            $mod = $this->getDoctrine()
                ->getRepository('EcommerceBundle:Panier')
                ->findAll();
            //var_dump($mod);
            return $this->render('@Ecommerce/Panier/PanierDetail.html.twig', array('panier' => $mod));
        }



    public function SupprimerPanierDetailsAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('EcommerceBundle:Produit')
            ->find($id);
        $m->remove($mod);
        $m->flush();
        return $this->redirect($this->generateUrl('ecommerce_PanierDetails'));

    }
    public function ModifierPanierDetailsAction(Request $request,$id)
    {

        /*$user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $panier = $em->getRepository('EcommerceBundle:Panier')->findOneBy(['idClient' => $id]);
        $id_panier = $panier->getId();

        $products = $em->getRepository("EcommerceBundle:Produit")->findBy(['idPanier' => $id_panier]);

        $subTotal = 0;
        foreach ($products as $p){
            $subTotal += $p->getQuantite()* $p->getPrix();

        }
        $prixTotal = $subTotal+10;
*/
        $em = $this->getDoctrine()->getManager();


        $produit=$em->getRepository("EcommerceBundle:Produit")->find($id);
        $form=$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em->persist($produit);
            //var_dump($panier);
            $em->flush();
            return $this->redirect($this->generateUrl('ecommerce_PanierDetails'));
        }
        $formview = $form->createView();

        return $this->render('@Ecommerce/Panier/ModifierPanierDetail.html.twig', array('form' => $formview));
    }

    public function AjouterPanierDetailsAction(Request $request)
    {
        $panier= new Panier();
        $form= $this->createForm(PanierType::class,$panier);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($panier);
            $em->flush();
        }
        //var_dump($commande);
        return $this->render("@Ecommerce/Panier/AjoutPanierDetail.html.twig",array("form"=>$form->createView()));
    }



    //backoffice Panier et Commande
    public function AjouterCommandeBackAction(Request $request)
    {
        $commande= new Commande();
        $form= $this->createForm(CommandeType::class,$commande);
        $form->handleRequest($request);
        $errors=null;
        if ($form->isSubmitted() && $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
        }else{
            $errors = $form->getErrors(true,false);
        }

        return $this->render("@Ecommerce/CommandeBack/AjoutCommandeBack.html.twig",array("form"=>$form->createView(),"errors"=>$errors));
    }
    public function AfficherCommandeBackAction() {
        $mod = $this->getDoctrine()
            ->getRepository(Commande::class)
            ->findAll();
        //var_dump($mod);
        return $this->render("@Ecommerce/CommandeBack/AfficherCommandeBack.html.twig", array('data' => $mod));
    }
    public function SupprimerCommandeBackAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('EcommerceBundle:Commande')
            ->find($id);
        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('ecommerce_homepageadminAfficherCommande'));
    }
    public function ModifierCommandeBackAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $commande=$m->getRepository("EcommerceBundle:Commande")->find($id);
        $form=$this->createForm(CommandeType::class,$commande);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $m->persist($commande);
            $m->flush();
            return $this->redirect($this->generateUrl('ecommerce_homepageadminAfficherCommande'));
        }
        $formview = $form->createView();

        return $this->render('@Ecommerce/CommandeBack/ModifierCommandeBack.html.twig', array('form' => $formview));
    }

    public function AfficherPanierBackAction() {
        $mod = $this->getDoctrine()
            ->getRepository('EcommerceBundle:Panier')
            ->findAll();
        //var_dump($mod);
        return $this->render("@Ecommerce/PanierBack/AfficherPanierBack.html.twig", array('data' => $mod));
    }
    public function SupprimerPanierBackAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('EcommerceBundle:Panier')
            ->find($id);
        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('ecommerce_homepageadminAfficherPanier'));
    }
    public function RechercherPanierBackAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $panier=$em->getRepository('EcommerceBundle:Panier')->findAll();
        if($request->isMethod('POST') )
        {
            $nom=$request->get('nom');
            $panier=$em->getRepository('EcommerceBundle:Panier')->findBy(array("nom"=>$nom));


        }
        return $this->render('@Ecommerce/PanierBack/RechercherPanierBack.html.twig',array('data'=>$panier));


    }
    //gerer csv
    public function exportAction()
    {
        $m=$this->getDoctrine()->getManager();
        $commande=$m->getRepository('EcommerceBundle:Commande')->findAll();
        $writer=$this->container->get('egyg33k.csv.writer');
        $csv=$writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['id', 'quantite' ,'prixTotal','prixCommande']);
        foreach ($commande as $commandes)
        {
            $csv->insertOne([$commandes->getId(),$commandes->getQuantite(),$commandes->getPrixTotal(),$commandes->getPrixCommande()]);
        }
        $csv->output('commande.csv');
        die();
    }
    public function RechercherCommandeBackAction(Request $request)
    {
      $em=$this->getDoctrine()->getManager();
        $commande=$em->getRepository('EcommerceBundle:Commande')->findAll();
        if($request->isMethod('POST') )
        {
            $id=$request->get('id');
            $commande=$em->getRepository('EcommerceBundle:Commande')->findBy(array("id"=>$id));


        }
        return $this->render('@Ecommerce/CommandeBack/rechercherCommandeBack.html.twig',array('commande'=>$commande));

    }
     public function StatistiqueAction()
     {


        $commandesVal = $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Commande")->findBy([
            "etat"=>"validée"
        ]);
        $nombreCommandesValidé = count($commandesVal);

         $commandesEnCours = $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Commande")->findBy([
             "etat"=>"en cours"
         ]);
         $nombreCommandesEnCours = count($commandesEnCours);

       /* $nombreclients= $this->getDoctrine()->getManager()->getRepository("AppBundle:User")->findAll();
         $produits= $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Produit")->findAll();
         $paniers= $this->getDoctrine()->getManager()->getRepository("EcommerceBundle:Panier")->findAll();*/


         $pieChart = new PieChart();
         $pieChart->getData()->setArrayToDataTable(
             [['Task', 'Hours per Day'],
                 ['commandes validées', $nombreCommandesValidé],
                 ['commandes en cours',      $nombreCommandesEnCours],

             ]
         );

         $pieChart->getOptions()->setTitle('Statistique Du site ECO-SYSTEM ');
         $pieChart->getOptions()->setHeight(500);
         $pieChart->getOptions()->setWidth(900);
         $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
         $pieChart->getOptions()->getTitleTextStyle()->setColor('#00998e');
         $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
         $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
         $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

         return $this->render('@Ecommerce\CommandeBack\chart.html.twig', array('piechart' => $pieChart));


     }


     public function afficherHistoriqueAction(Request $request)
     {


         $historiqueCommandes = $this->getDoctrine()
             ->getRepository(Commande::class)
             ->findAll();
           $em = $this->getDoctrine()->getManager();
           $query  = $em->createQuery("
            SELECT c
            FROM EcommerceBundle:Commande c
            ORDER BY c.dateCommande ASC 
             ");
         $commandes = $query->getResult();


         return $this->render('@Ecommerce\CommandeBack\historique.html.twig', array('commandes' => $commandes));

     }
     public function TopCommandeBackAction()
     {
         $historiqueCommandes = $this->getDoctrine()
             ->getRepository(Commande::class)
             ->findAll();
         $em = $this->getDoctrine()->getManager();
         $query  = $em->createQuery("
            SELECT c
            FROM EcommerceBundle:Commande c
            ORDER BY c.quantite DESC
           
            
           
             ")->setMaxResults(5);
         $commandes = $query->getResult();
         return $this->render('@Ecommerce\CommandeBack\TopCommande.html.twig', array('commandes' => $commandes));



     }






}
