<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Evenement;
use EventBundle\Entity\Participation;
use EventBundle\Entity\Wishlist;
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
//Partie : Front
    public function AfficheEvenementAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findByPublie();
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));

      return $this->render('@Event/Default/listEvenements.html.twig',array('evenement'=>$evenementss));

    }


    public function getEvenementsAction(Request $request)
    {

                $thread = $this->container->get('fos_comment.manager.thread')->findAllThreads();

        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findByPublie();
        $evenementss  = $this->get('knp_paginator')->paginate(
            $evenements,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6));
         $template = $this->render('@Event/Default/evenements.html.twig', array('evenements'=>$evenementss,'Threads'=>$thread))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);



    }
    public function detailEvenementAction(Request $request, $id) {
        $idthread = $id;
        $thread = $this->container->get('fos_comment.manager.thread')->findThreadById($idthread);
        if (null === $thread) {
            $thread = $this->container->get('fos_comment.manager.thread')->createThread();
            $thread->setId($idthread);
            $thread->setPermalink($request->getUri());

            // Add the thread
            $this->container->get('fos_comment.manager.thread')->saveThread($thread);
        }

        $comments = $this->container->get('fos_comment.manager.comment')->findCommentTreeByThread($thread);

        $wishlists = $this->getDoctrine()
            ->getRepository('EventBundle:Wishlist')
            ->findBy(array('User'=>$this->getUser()));
        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findCat($evenement->getCategorie());


        $evenement->setNombrevu($evenement->getNombrevu()+1);

        $m=$this->getDoctrine()->getManager();
        $m->persist($evenement);
        $m->flush();


        return $this->render('@Event/Default/Evenementdetail.html.twig',array('evenement'=>$evenement,'evenements'=>$evenements,'comments' => $comments,
            'thread' => $thread ,'wishlist'=>$wishlists));

    }

    public function AjoutEvenementAction(Request $request)
    {
        $evenement=new Evenement();
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $currentdate= new \DateTime('now');
            if($evenement->getDateDebut()<$currentdate)
            {
                $request->getSession()
                    ->getFlashBag()
                    ->add('errorDatedebut', "Date début ne peut pas etre inférieur a la date courrante")
                ;
            }
            elseif ($evenement->getDateDebut()>$evenement->getDateFin())
            {
                $request->getSession()
                    ->getFlashBag()
                    ->add('errorDate', "Date début ne peut pas etre Supérieur a la date Fin")
                ;
            }
            else{



                $user = $this->getUser();
                $evenement->setOwnerUser($user);
                $evenement->setPublie(0);
                $evenement->setRating(0);
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
                return $this->redirect($this->generateUrl('event_ajoutevenement'));
            }
        }




        $formview=$form->createView();

        return $this->render('@Event/Default/ajoutEvenement.html.twig', array('form' => $formview));

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

    public function EventGratuitAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('categorie'=>'gratuit'));
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

    public function EventPayantAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('categorie'=>'payant'));
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

    public function OrdernoteAction(Request $request) {


        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->OrderByNote();
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
            ->findByPublie();
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
            ->findByPublie();
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
            ->findByPublie();
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

    public function particperAction(Request $request)
    {
        $particpation=new Participation();

        $nbplace=intval($request->request->get('nbPlace'));

        $event=$request->request->get('event');
        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($event);

        $user = $this->getUser();

        $particpation->setEvent($evenement);

        $particpation->setUser($user);

        $particpation->setNbPlace($nbplace);



        $evenement->setnombretickets($evenement->getnombretickets()+$nbplace);

        $m=$this->getDoctrine()->getManager();
        $m->persist($evenement);
        $m->persist($particpation);
        $m->flush();
        return new JsonResponse();







    }

    public function RatingAction(Request $request)
    {


        $nbrating=intval($request->request->get('rating'));

        $event=$request->request->get('event');
        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($event);

        $evenement->setRating($evenement->getRating()+$nbrating);

        $m=$this->getDoctrine()->getManager();
        $m->persist($evenement);
        $m->flush();
        return new JsonResponse();







    }

    public function wishlistAction(Request $request)
    {
        $event=$request->request->get('event');
        $user = $this->getUser();



        $wishlist=new Wishlist();


        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($event);


        $wishlist->setEvent($evenement);

        $wishlist->setUser($user);





        $m=$this->getDoctrine()->getManager();
        $m->persist($wishlist);
        $m->flush();
        return new JsonResponse();







    }

    public function affichewishlistAction(Request $request) {
        $wishlists = $this->getDoctrine()
            ->getRepository('EventBundle:Wishlist')
            ->findBy(array('User'=>$this->getUser()));


        return $this->render('@Event/Default/wishlist.html.twig',array('wishlists'=>$wishlists));

    }

    public function deleteWishlistAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('EventBundle:Wishlist')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('afficheWishlist'));
    }


    public function AfficheMesEvenementsAction() {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('OwnerUser'=>$this->getUser()));



        return $this->render('@Event/Default/Mesevenements.html.twig', array('evenements' => $evenements));

    }

    public function deleteEvenementUserAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('affichemesevenements'));
    }

    public function UpdateEvenementAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $event=$m->getRepository("EventBundle:Evenement")->find($id);
        $form=$this->createForm(EvenementType::class,$event);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $currentdate = new \DateTime('now');
            if ($event->getDateDebut() < $currentdate) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('errorDatedebut', "Date début ne peut pas etre inférieur a la date courrante");
            } elseif ($event->getDateDebut() > $event->getDateFin()) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('errorDate', "Date début ne peut pas etre Supérieur a la date Fin");
            } else {
                $file = $event->getImage();

                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $event->setImage($fileName);
                $m->persist($event);
                $m->flush();
                return $this->redirect($this->generateUrl('affichemesevenements'));
            }
        }
        $formview = $form->createView();

        return $this->render('@Event/Default/updateevent.html.twig', array('form' => $formview,'event'=>$event));
    }







  //Partie Back end :

    public function AfficheEvenement2Action() {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findAll();


        return $this->render('@Event/gestionadmin/listEvenement.html.twig', array('evenements' => $evenements));

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







    public function AjoutEvenementAdminAction(Request $request)
    {
        $evenement=new Evenement();
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $currentdate = new \DateTime('now');
            if ($evenement->getDateDebut() < $currentdate) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('errorDatedebut', "Date début ne peut pas etre inférieur a la date courrante");
            } elseif ($evenement->getDateDebut() > $evenement->getDateFin()) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('errorDate', "Date début ne peut pas etre Supérieur a la date Fin");
            } else {


                $user = $this->getUser();
                $evenement->setOwnerUser($user);

                $evenement->setPublie(1);
                $evenement->setRating(0);

                $evenement->setNombretickets(0);
                $evenement->setNombrevu(0);
                $file = $evenement->getImage();

                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
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
                if ($request->get('grat') == 'gratuit') {
                    $evenement->setPrix(0);

                } else {
                    $evenement->setPrix($request->get('prix'));

                }

                $m = $this->getDoctrine()->getManager();
                $m->persist($evenement);
                $m->flush();
                $request->getSession()
                    ->getFlashBag()
                    ->add('success', "Votre evenement est ajouté ! attend de confirmation d'administrateur");
                return $this->redirect($this->generateUrl('event_afficheeventadmin'));

            }
        }
        else{
            $request->getSession()
                ->getFlashBag()
                ->add('error', "Vérifier votre information !")
            ;
        }

        $formview=$form->createView();

        return $this->render('@Event/gestionadmin/ajoutEventAdmin.html.twig', array('form' => $formview));

    }

    public function UpdateEvenementAdminAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $event=$m->getRepository("EventBundle:Evenement")->find($id);
        $form=$this->createForm(EvenementType::class,$event);
        $form->handleRequest($request);
        if($form->isSubmitted()) {

            $currentdate = new \DateTime('now');
            if ($event->getDateDebut() < $currentdate) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('errorDatedebut', "Date début ne peut pas etre inférieur a la date courrante");
            } elseif ($event->getDateDebut() > $event->getDateFin()) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('errorDate', "Date début ne peut pas etre Supérieur a la date Fin");
            } else {
                $file = $event->getImage();

                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $event->setImage($fileName);
                $m->persist($event);
                $m->flush();
                return $this->redirect($this->generateUrl('event_afficheeventadmin'));
            }
        }
        $formview = $form->createView();

        return $this->render('@Event/gestionadmin/UpdateEventAdmin.html.twig', array('form' => $formview));
    }

    public function traiteruserEnableAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->updateenable($id);

        $m->flush();
        return new JsonResponse();

    }

    public function traiteruserdisableAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->updatedisable($id);

        $m->flush();
        return new JsonResponse();

    }

    public function AfficheuserAction() {
        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();


        return $this->render('@Event/gestionadmin/listeuser.html.twig', array('users' => $users));

    }

    public function AfficheparticipationAction() {
        $participation = $this->getDoctrine()
            ->getRepository('EventBundle:Participation')
            ->findAll();


        return $this->render('@Event/gestionadmin/particpation.html.twig', array('participation' => $participation));

    }
    public function DetailEventAction($id) {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($id);


        return $this->render('@Event/gestionadmin/detailevent.html.twig', array('evenement' => $evenements));

    }

    public function deleteuserAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('afficheuser'));
    }

    public function traiterEvenementpublieAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->updatepublie($id);

        $m->flush();
        return new JsonResponse();


    }

    public function traiterEvenementnonpublieAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->updatenonpublie($id);

        $m->flush();
        return new JsonResponse();


    }


    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }


}
