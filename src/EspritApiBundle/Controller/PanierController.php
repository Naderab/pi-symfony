<?php

namespace EspritApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PanierController extends Controller
{

    public function afficherpanierAction()
    {
        $mod = $this->getDoctrine()
            ->getRepository('EcommerceBundle:Panier')
            ->findAll();

        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($mod);
        return new JsonResponse($formatted);
    }

}
