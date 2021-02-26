<?php

namespace ProductBundle\Controller;
use ProductBundle\Entity\Produit2;
use ProductBundle\Entity\WishlistProduit;
use ProductBundle\Form\Produit2Type;
use ProductBundle\ImageUpload;
use Symfony\Component\BrowserKit\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\DataCollector\RequestDataCollector;
use Symfony\Component\Serializer\Tests\Model;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ProduitController extends Controller
{

    public function ajouterAnnonceAction(Request $request)
    {
        $p = new Produit2();
        $form = $this->createForm(Produit2Type::class,$p);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $p->setPublie(0);
            $file=$p->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directory'), $fileName);
            $p->setImage($fileName);
            $em=$this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', "Votre Annonce est ajouté ! attend de confirmation d'administrateur")
            ;

            return $this->redirectToRoute("afficherAnnonce");
        }
        $request->getSession()
            ->getFlashBag()
            ->add('error', "Vérifier votre information !")
        ;
        return $this->render('@Product/ajouterAnnonce.html.twig', array('form' => $form->createView() ));
    }


    public function afficherAnnonceAction(Request $request) {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findByPublie();
        $produit  = $this->get('knp_paginator')->paginate(
            $produits,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));


        return $this->render('@Product/Default/listAnnonce.html.twig',array('produits'=>$produit));

    }

    public function getProduitAction(Request $request)
    {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findByPublie();

        $produit  = $this->get('knp_paginator')->paginate(
            $produits,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));

        $template = $this->render('@Product/Default/produits.html.twig', array('produits'=>$produit))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new \Symfony\Component\HttpFoundation\Response($json);

    }

    public function detailAnnonceAction($id) {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->find($id);
        return $this->render('@Product/Default/detailAnnonce.html.twig',array('produit'=>$produits));

    }

    public function supprimerAnnonceAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('afficherAnnonce'));
    }

    public function AfficheMesProduitsAction() {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findBy(array('OwnerUser'=>$this->getUser()));



        return $this->render('@Product/Default/Mesproduits.html.twig', array('produits' => $produits));

    }



    public function affichewishlistAction(Request $request) {
        $wishlists = $this->getDoctrine()
            ->getRepository('ProductBundle:WishlistProduit')
            ->findBy(array('User'=>$this->getUser()));


        return $this->render('@Product/Default/Mesproduitsfavoris.html.twig',array('produits'=>$wishlists));

    }

    public function deleteWishlistAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('ProductBundle:WishlistProduit')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('Mesproduitsfavoris'));
    }

    public function modifierAnnonceAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getRepository(Produit2::class)->find($id);
        $form= $this->createForm(Produit2Type::class,$em);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em= $this->getDoctrine()->getManager();

            $em->persist($em);
            $em->flush();
            return $this->redirectToRoute("detailAnnonce");
        }
        return $this->render("@Product/ajouterAnnonce.html.twig",array("form"=>$form->createView()));
    }


    public function validerAnnonceadminAction(Request $request,$id)
    {
        $m = $this->getDoctrine()->getManager();
        $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->update($id);

        $m->flush();
        return $this->redirect($this->generateUrl('afficherAnnonceadmin'));

    }
    public function supprimerAnnonceAdminAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('afficherAnnonceadmin'));
    }

    public function afficherAnnonceadminAction() {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findBy(array('publie'=>1));


        return $this->render('@Product/gestionadmin/listeannonce.html.twig', array('produits' => $produits));

    }
    public function AfficheProduitNonTraiterAction() {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findBy(array('publie'=>0));


        return $this->render('@Product/gestionadmin/listAnnonceNontraiter.html.twig', array('produits' => $produits));

    }



    public function ProduitCroissantAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->OrderByPrixAsc();
        $produit  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Product/Default/produits.html.twig', array('produits'=>$produit))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new \Symfony\Component\HttpFoundation\Response($json);

    }


    public function ProduitDecroissantAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->OrderByPrixDesc();
        $produit  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Product/Default/produits.html.twig', array('produits'=>$produit))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new \Symfony\Component\HttpFoundation\Response($json);

    }


    public function TelephonieAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findBy(array('categorie'=>1));
        $produit  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Product/Default/produits.html.twig', array('produits'=>$produit))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new \Symfony\Component\HttpFoundation\Response($json);

    }

    public function ElectromenagerAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findBy(array('categorie'=>2));
        $produit  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Product/Default/produits.html.twig', array('produits'=>$produit))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new \Symfony\Component\HttpFoundation\Response($json);

    }

    public function MaisonAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findBy(array('categorie'=>3));
        $produit  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Product/Default/produits.html.twig', array('produits'=>$produit))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new \Symfony\Component\HttpFoundation\Response($json);

    }

    public function ElectroniqueAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findBy(array('categorie'=>4));
        $produit  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Product/Default/produits.html.twig', array('produits'=>$produit))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new \Symfony\Component\HttpFoundation\Response($json);

    }


    public function wishlistAction(Request $request)
    {
        $produit=$request->request->get('produit');
        $user = $this->getUser();



        $wishlist=new WishlistProduit();


        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->find($produit);


        $wishlist->setProduit($produits);

        $wishlist->setUser($user);





        $m=$this->getDoctrine()->getManager();
        $m->persist($wishlist);
        $m->flush();
        return new JsonResponse();
    }



}
