<?php

namespace EspritApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use EcommerceBundle\Entity\Produit;


use Symfony\Component\HttpFoundation\Request;

class ProduitController extends Controller
{


    public function afficherproduitAction(Request $request)
    {

        /*
              $mod = $this->getDoctrine()
                  ->getRepository('EcommerceBundle:Produit')
                  ->findProduitBy();

           $serializer = SerializerBuilder::create()->build();
              $jsonObject = $serializer->serialize($mod, 'json');
              return new JsonResponse($jsonObject);
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($mod);
        return new JsonResponse($formatted);
            */
    }

    public function afficherProduitsJsonAction()
    {
        $produits=$this->getDoctrine()->getManager()
            ->getRepository('EcommerceBundle:Produit')
            ->findAll();
        $serializer =  new Serializer([new ObjectNormalizer()]);
        $formatted =$serializer->normalize($produits);
        return new JsonResponse($formatted);

    }
    public function RechercheProduitJsonAction($id)
    {
        $produits=$this->getDoctrine()->getManager()
            ->getRepository('EcommerceBundle:Produit')
            ->find($id);
        $serializer =  new Serializer([new ObjectNormalizer()]);
        $formatted =$serializer->normalize($produits);
        return new JsonResponse($formatted);
    }
    public function ajouterProduitJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $produit->setTitre($request->get('titre'));
        $produit->setCategorie($request->get('categorie'));
        $produit->setDescription($request->get("description"));
        $produit->setPrix($request->get("prix"));
        $produit->setQuantite($request->get("quantite"));
        $produit->setSousCategorie($request->get("sous_categorie"));
        $produit->setImage($request->get("image"));
        $em->persist($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);
    }
}
