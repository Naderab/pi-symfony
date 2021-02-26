<?php

namespace EspritApiBundle\Controller;

use EcommerceBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CommandeController extends Controller
{
    public function ajouterCommandeJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commande = new Commande();
        $commande->setDateCommande(new \DateTime());
        $commande->setQuantite($request->get('quantite'));
        $commande->setEtat($request->get("etat"));
        $commande->setPrixCommande($request->get("prixCommande"));
        $commande->setPrixTotal($request->get("prixTotal"));

        $em->persist($commande);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($commande);
        return new JsonResponse($formatted);
    }
}
