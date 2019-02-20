<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Evenement;
use EventBundle\EventBundle;
use EventBundle\Form\EvenementType;
use EventBundle\ImageUpload;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class EventController extends Controller
{

    public function AfficheEvenementAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('publie'=>1));
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));

      return $this->render('@Event/Default/listEvenements.html.twig',array('evenement'=>$evenementss));

    }
    public function getEvenementsAction(Request $request)
    {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('publie'=>1));
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Event/Default/evenements.html.twig', array('evenements'=>$evenementss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);



    }
    public function AfficheEvenement2Action() {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('publie'=>1));


        return $this->render('@Event/gestionadmin/listEvenement.html.twig', array('evenements' => $evenements));

    }

    public function detailEvenementAction($id) {
        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);


        return $this->render('@Event/Default/Evenementdetail.html.twig',array('evenement'=>$evenement));

    }

    public function AfficheEvenementNonTraiterAction() {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('publie'=>0));


        return $this->render('@Event/gestionadmin/listEvenementNontraiter.html.twig', array('evenements' => $evenements));

    }

    public function deleteEvenementAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('event_afficheeventadmin'));
    }
    public function deleteEvenement2Action($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('event_afficheeventadmin'));
    }

    public function AjoutEvenementAction(Request $request)
    {
        $evenement=new Evenement();
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {



            $evenement->setPublie(0);
            $evenement->setNombretickets(0);
            $evenement->setNombrevu(0);
            $file = $evenement->getImage();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $evenement->setImage($fileName);
            $evenement->setCategorie($request->get('grat'));
            if($request->get('grat') == 'gratuit')
            {
                $evenement->setPrix(0);

            }
            else{
                $evenement->setPrix($request->get('prix'));

            }

            $m=$this->getDoctrine()->getManager();
            $m->persist($evenement);
            $m->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', "Votre evenement est ajouté ! attend de confirmation d'administrateur")
            ;
            return $this->redirect($this->generateUrl('event_homepage'));

        }
        else{
            $request->getSession()
                ->getFlashBag()
                ->add('error', "Vérifier votre information !")
            ;
        }

        $formview=$form->createView();

        return $this->render('@Event/Default/ajoutEvenement.html.twig', array('form' => $formview));

    }

    public function traiterEvenementAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->update($id);

        $m->flush();
        return $this->redirect($this->generateUrl('event_afficheeventNtadmin'));

    }

    public function OrderprixascAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->OrderByPrixAsc();
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Event/Default/evenements.html.twig', array('evenements'=>$evenementss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);

    }

    public function OrderprixdescAction(Request $request) {


        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->OrderByPrixDesc();
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Event/Default/evenements.html.twig', array('evenements'=>$evenementss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);


    }

    public function OrderdateAction(Request $request) {


        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->OrderByDate();
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Event/Default/evenements.html.twig', array('evenements'=>$evenementss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);


    }

    public function OrdermeuilleurAction(Request $request) {


        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->OrderByMeilleur();
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
        $template = $this->render('@Event/Default/evenements.html.twig', array('evenements'=>$evenementss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);


    }




    public function showing18Action(Request $request)
    {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('publie'=>1));
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',18));
        $template = $this->render('@Event/Default/evenements.html.twig', array('evenements'=>$evenementss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);



    }



    public function showing10Action(Request $request)
    {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('publie'=>1));
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',10));
        $template = $this->render('@Event/Default/evenements.html.twig', array('evenements'=>$evenementss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);



    }

    public function showing14Action(Request $request)
    {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('publie'=>1));
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',14));
        $template = $this->render('@Event/Default/evenements.html.twig', array('evenements'=>$evenementss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);



    }




    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }


}
