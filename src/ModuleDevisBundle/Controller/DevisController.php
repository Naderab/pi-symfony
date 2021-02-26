<?php

namespace ModuleDevisBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use ModuleDevisBundle\Entity\Devis;
use ModuleDevisBundle\Entity\offre;
use ModuleDevisBundle\Form\DevisType;
use ModuleDevisBundle\Form\offreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DevisController extends Controller
{
    public function indexAction()
    {
        return $this->render('ModuleDevisBundle:Default:index.html.twig');
    }

    /*****************CRUD OFFRE*************************/
    /****************************************************/
    public function AjouterOffreAction(Request $request)
    {
        $offre=new offre();
        $form=$this->createForm(offreType::class,$offre);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
              /*$date=new \DateTime('now');
              $offre->setDateoffre($date);*/
              $offre->setEtat("non");
              $offre->setDemande("non");
              $offre->setArchiver("non");
            $m=$this->getDoctrine()->getManager();
            $m->persist($offre);
            $m->flush();
            return $this->redirect($this->generateUrl('afficheroffre'));
        }

        $formview=$form->createView();

        return $this->render('@ModuleDevis/Offre/AjoutOffre.html.twig', array('form' => $formview));

    }

    public function AfficherOffreAdminAction() {
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:offre')
            ->findAll();
        return $this->render('@ModuleDevis/Offre/Afficheoffreadmin.html.twig', array('data' => $mod));
    }

    public function AfficherOffreAction() {
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:offre')
            ->findAll();
        return $this->render('@ModuleDevis/Offre/AfficheOffre.html.twig', array('data' => $mod));
    }



    public function ModifierOffreAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $offre=$m->getRepository("ModuleDevisBundle:offre")->find($id);
        $form=$this->createForm(offreType::class,$offre);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $m->persist($offre);
            $m->flush();
            return $this->redirect($this->generateUrl('afficheroffre'));
        }
        $formview = $form->createView();

        return $this->render('@ModuleDevis/Offre/AjoutOffre.html.twig', array('form' => $formview));
    }

    public function DeleteOffreAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:offre')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('afficheroffre'));
    }

    public function DemanderDevisAction($id) {

        $m=$this->getDoctrine()->getManager();
        $offre=$m->getRepository("ModuleDevisBundle:offre")->find($id);
        $offre->setDemande("oui");
        $m->persist($offre);
        $m->flush();
        return $this->redirect($this->generateUrl('afficheroffreadmin'));


    }


    public function AfficherDemandeDevisAction() {
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:offre')
            ->findAll();
        return $this->render('@ModuleDevis/Devis/ListeDemandeDevis.html.twig', array('data' => $mod));
    }


    public function AnnulerDemanderDevisAction($id) {

        $m=$this->getDoctrine()->getManager();
        $offre=$m->getRepository("ModuleDevisBundle:offre")->find($id);
        $offre->setDemande("non");
        $m->persist($offre);
        $m->flush();
        return $this->redirect($this->generateUrl('afficheroffreadmin'));
    }



    /*****************CRUD DEVIS*************************/
    /****************************************************/

    public function AjouterDevisAction(Request $request,$id)
    {
        $devis=new Devis();
        $form=$this->createForm(DevisType::class,$devis);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
           $date=new \DateTime('now');
           $devis->setDatedevis($date);

           $devis->setConfirmer("non");
            $m1=$this->getDoctrine()->getManager();
            $offre=$m1->getRepository("ModuleDevisBundle:offre")->find($id);
            $offre->setEtat("oui");
            $devis->setDescription($offre->getDescription());
            $m1->persist($offre);
            $m1->flush();
            $devis->setOffre($offre);
            $m1->persist($devis);
            $m1->flush();
            return $this->redirect($this->generateUrl('afficherdevis'));
        }

        $formview=$form->createView();

        return $this->render('@ModuleDevis/Devis/AjoutDevis.html.twig', array('form' => $formview));

    }

    public function AfficherDevisAction() {
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:Devis')
            ->findAll();
        return $this->render('@ModuleDevis/Devis/AfficheDevis.html.twig', array('data' => $mod));
    }

    public function AfficherDevisAdminAction() {
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:Devis')
            ->findAll();
        return $this->render('@ModuleDevis/Devis/AfficheDevisAdmin.html.twig', array('data' => $mod));
    }

    public function ModifierDevisAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $devis=$m->getRepository("ModuleDevisBundle:Devis")->find($id);
        $form=$this->createForm(DevisType::class,$devis);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $m->persist($devis);
            $m->flush();
            return $this->redirect($this->generateUrl('afficherdevis'));
        }
        $formview = $form->createView();

        return $this->render('@ModuleDevis/Devis/AjoutDevis.html.twig', array('form' => $formview));
    }


    public function DeleteDevisAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:Devis')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('afficherdevis'));
    }


    public function ConfirmerDevisAction($id) {

        $m=$this->getDoctrine()->getManager();
        $devis=$m->getRepository("ModuleDevisBundle:Devis")->find($id);
        $devis->setConfirmer("oui");
        $m->persist($devis);
        $m->flush();
        return $this->redirect($this->generateUrl('afficherdevisadmin'));
    }

    public function AnnulerConfirmerDevisAction($id) {

        $m=$this->getDoctrine()->getManager();
        $devis=$m->getRepository("ModuleDevisBundle:Devis")->find($id);
        $devis->setConfirmer("non");
        $m->persist($devis);
        $m->flush();
        return $this->redirect($this->generateUrl('afficherdevisadmin'));
    }

    public function GenererDevisAction(Request $request) {


        $datedevis = $request->get("datedevis");
        $datevalidite = $request->get("datevalidite");
        $description = $request->get("description");
        $libelle = $request->get("libelle");
        $prixunit = $request->get("prixunit");
        $qte = $request->get("qte");
        $totalHT = $request->get("totalHT");
        $TVA = $request->get("TVA");
        $totalTTC = $request->get("totalTTC");
        $message = $request->get("message");

        $html =
            $this->renderView('@ModuleDevis/Devis/devis.html.twig',
                array(
                    'datedevis' =>$datedevis,
                    'datevalidite' =>$datevalidite,
                    'description'=>$description,
                    'libelle'=>$libelle,
                    'prixunit'=>$prixunit,
                    'qte'=>$qte,
                    'totalHT'=>$totalHT,
                    'TVA'=>$TVA,
                    'totalTTC'=>$totalTTC,
                    'message'=>$message,
                ));
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'file.pdf'
        );
    }
}
