<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Task;
use EventBundle\Entity\Evenement;
use EventBundle\Entity\Participation;
use EventBundle\Entity\Wishlist;
use ModuleDevisBundle\Entity\offre;
use ProductBundle\Entity\Produit2;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    //OK
    public function GetAllEventAction()
    {
        $event = $this->getDoctrine()->getManager()
            ->getRepository('EventBundle:Evenement')
            ->findByPublie();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);
    }



    public function findEventByIdAction(Request $request)
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('EventBundle:Evenement')
            ->find($request->get('id'));
        $tasks->setNombrevu($tasks->getNombrevu()+1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($tasks);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }

    public function findUserByIdAction(Request $request)
    {
        $user = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);
    }
//OK
    public function AjoutEventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dateAsObjectD= new \DateTime('now');
        $dateAsObjectD->modify('+3 day');
        $dateAsObjectF= new \DateTime('now');
        $dateAsObjectF->modify('+6 day');


        $event= new Evenement();
        $event->setNom($request->get('nom'));
        $event->setDateDebut($dateAsObjectD);
        $event->setDateFin($dateAsObjectF);
        $event->setCategorie($request->get('cat'));
        $event->setMinParticipants($request->get('minP'));
        $event->setMaxParticipants($request->get('maxP'));
        $event->setAdresse($request->get('adr'));
        $event->setPrix($request->get('prix'));
        $event->setDescription($request->get('desc'));
        $event->setRating(0);
        $event->setNombrevu(0);
        $event->setNombretickets(0);
        $event->setPublie(0);
        $event->setImage($request->get('image'));
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($request->get('user'));
        $event->setOwnerUser($user);
        $img=$event->getImage();

        $fileName = $this->generateUniqueFileName() . '.'.substr($img, strlen($img)-3, strlen($img));;


        $event->setImage($fileName);
        copy('file:///C:/Users/Nader/.cn1/'.$img, 'file:///C:/wamp64/www/EcoSystem/web/uploads/images/'.$fileName);



        $em->persist($event);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);
    }

    public function particperAction(Request $request)
    {
        $particpation=new Participation();

        $nbplace=intval($request->get('nbPlcae'));

        $event=$request->get('event');
        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($event);

        $user = $request->get('user');

        $userr = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($user);

        $particpation->setEvent($evenement);

        $particpation->setUser($userr);

        $particpation->setNbPlace($nbplace);



        $evenement->setnombretickets($evenement->getnombretickets()+$nbplace);

        $m=$this->getDoctrine()->getManager();
        $m->persist($evenement);
        $m->persist($particpation);
        $m->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($particpation);
        return new JsonResponse();







    }
//OK
    public function RatingAction(Request $request)
    {


        $nbrating=intval($request->get('rating'));

        $event=$request->get('event');
        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($event);



        $evenement->setRating($evenement->getRating()+$nbrating);

        $m=$this->getDoctrine()->getManager();
        $m->persist($evenement);
        $m->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenement);
        return new JsonResponse($formatted);







    }
//OK
    public function wishlistAction(Request $request)
    {
        $event=$request->get('event');
        $userr = $request->get('user');;



        $wishlist=new Wishlist();


        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($event);

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userr);


        $wishlist->setEvent($evenement);

        $wishlist->setUser($user);

        $m=$this->getDoctrine()->getManager();
        $m->persist($wishlist);
        $m->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($wishlist);
        return new JsonResponse($formatted);

    }
//OK
    public function GetAllwishlistAction(Request $request)
    {
        $wishlists = $this->getDoctrine()
            ->getRepository('EventBundle:Wishlist')
            ->findBy(array('User'=>$request->get('user')));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($wishlists);
        return new JsonResponse($formatted);
    }

    public function findByUserAction(Request $request)
    {
        $event=$request->get('event');
        $userr = $request->get('user');;
        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($event);

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userr);
        $wishlists = $this->getDoctrine()
            ->getRepository('EventBundle:Wishlist')
            ->findByuser($user,$evenement);

        if($wishlists == [])
        {
            return new JsonResponse(array('test' => 'true'));
        }

        return new JsonResponse(array('test' => 'false'));
    }

    public function findByUserParticpationAction(Request $request)
    {
        $event=$request->get('event');
        $userr = $request->get('user');
        $evenement = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($event);

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userr);
        $participation = $this->getDoctrine()
            ->getRepository('EventBundle:Participation')
            ->findByuser($user,$evenement);

        if($participation == [])
        {
            return new JsonResponse(array('test' => 'true'));
        }

            return new JsonResponse(array('test' => 'false'));



    }

    public function GetNbLikeAction(Request $request)
    {
        $nb = $this->getDoctrine()
            ->getRepository('EventBundle:Wishlist')
            ->Number($request->get('event'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($nb);
        return new JsonResponse(array('nblike' => $nb));
    }

    public function GetNbCommentAction(Request $request)
    {
        $nb = $this->getDoctrine()
            ->getRepository('EventBundle:Wishlist')
            ->Number($request->get('event'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($nb);
        return new JsonResponse(array('nblike' => $nb));
    }
//OK
    public function deleteWishlistAction(Request $request) {
        $m=$this->getDoctrine()->getManager();

        $mod = $this->getDoctrine()
            ->getRepository('EventBundle:Wishlist')
            ->find($request->get('id'));

        $m->remove($mod);
        $m->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mod);
        return new JsonResponse($formatted);

    }

    public function deleteEventAction(Request $request) {
        $m=$this->getDoctrine()->getManager();

        $mod = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($request->get('id'));

        $m->remove($mod);
        $m->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mod);
        return new JsonResponse($formatted);

    }

//OK
    public function AfficheMesEvenementsAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->findBy(array('OwnerUser'=>$request->get('user')));

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenements);
        return new JsonResponse($formatted);



    }
//OK
    public function deleteEvenementUserAction(Request $request) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->find($request->get('id'));

        $m->remove($mod);
        $m->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mod);
        return new JsonResponse($formatted);

    }
//OK
    public function OrdernoteAction() {


        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->OrderByNote();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenements);
        return new JsonResponse($formatted);



    }

    public function OrderdateAction() {


        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->OrderByDate();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenements);
        return new JsonResponse($formatted);



    }

    public function OrderprixdescAction(Request $request) {


        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->OrderByPrixDesc();


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenements);
        return new JsonResponse($formatted);


    }

    public function OrderprixascAction(Request $request) {
        $evenements = $this->getDoctrine()
            ->getRepository('EventBundle:Evenement')
            ->OrderByPrixAsc();


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenements);
        return new JsonResponse($formatted);

    }





    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }


    public function profileJsonAction(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getDoctrine()
            ->getRepository("AppBundle:User")
            ->find($id);


        $serializer = $this->container->get('jms_serializer');
        $output = array($user);
        $pubs = $serializer->serialize($output, 'json');

        return new Response($pubs);
    }
//login the user
    public function loginJsonAction(Request $request)
    {
        $userName= $request->get('username');
        $password=$request->get('password');

        $user = $this->getDoctrine()
            ->getRepository("AppBundle:User")
            ->findAll();
        $usr=[];

        foreach ($user as $u){


            if($u->getUsername() == $userName) {
                $encoder_service = $this->get('security.encoder_factory');
                $encoder = $encoder_service->getEncoder($u);
                if ($encoder->isPasswordValid($u->getPassword(), $password, $u->getSalt()))
                {

                    array_push($usr, $u);
                }
            }
        }

        if ($usr == []) {
            return new JsonResponse(array('id' => 0,'info'=>'usernotfound'));
        }
        $output = array($usr[0]);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $pubs = $serializer->normalize($output);
        return new JsonResponse($pubs);


    }

    //Blog

    public function AddPostAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $posts=new Post();


        $posts->setTitre($request->get('titre'));
        $posts->setDescription($request->get('description'));
        $posts->setContenu($request->get('contenu'));
        $posts->setPublier(0);
        $posts->setDatepost(new \DateTime('now'));
        $posts->setNombrevue(0);
        $posts->setNombrelike(0);

        $posts->setImage($request->get('image'));;

        ;


        $img=$posts->getImage();

        $fileName = $this->generateUniqueFileName() . '.'.substr($img, strlen($img)-3, strlen($img));;

        $posts->setImage($fileName);


        $em->persist($posts);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($posts);
        return new JsonResponse($formatted);
    }




    public function getPostAction(Request $request)
    {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->findBy(array('publier'=>1));

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($posts);
        return new JsonResponse($formatted);

    }



    public function LikepostAction(Request $request)
    {


        $postss=$request->get('post');
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->find($postss);

        $posts->setNombrelike($posts->getNombrelike()+1);

        $m=$this->getDoctrine()->getManager();
        $m->persist($posts);
        $m->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($posts);
        return new JsonResponse($formatted);

    }


    public function AffichePostAction(Request $request) {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->findBy(array('publier'=>1));
        $categonries = $this->getDoctrine()
            ->getRepository('BlogBundle:CategorieBlog')->findAll();
        $postpops = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->OrderByMeilleur();


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($postpops);
        return new JsonResponse($formatted);

    }


    public function AffichePost2Action(Request $request,$id) {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->findBy(array('CategorieBlog' => $id,'publier'=>1));



        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($posts);
        return new JsonResponse($formatted);




    }




    public function detailPostAction(Request $request,$id) {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->find($id);
        $categonries = $this->getDoctrine()
            ->getRepository('BlogBundle:CategorieBlog')->findAll();
        $postpops = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->OrderByMeilleur();

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



        $posts->setNombrevue($posts->getNombrevue()+1);

        $m=$this->getDoctrine()->getManager();
        $m->persist($posts);
        $m->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($posts);
        return new JsonResponse($formatted);

    }



















    public function ajoutAbonnementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $abonn =  new Abonnement();

        $abonn->setEmail($request->get('email'));

        $em->persist($abonn);
        $em->flush();


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($abonn);
        return new JsonResponse($formatted);
    }


    //Ramzi

    public function allAction()
    {
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:offre')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mod);
        return new JsonResponse($formatted);
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $offre=new offre();
        $offre->setTitre($request->get('titre'));
        $offre->setType($request->get('type'));
        $offre->setDescription($request->get('description'));
        $date=new \DateTime('now');
        $offre->setDateoffre($date);
        $offre->setDatevalidite($date);
        $offre->setEtat("non");
        $offre->setDemande("non");
        $offre->setArchiver("non");
        $em->persist($offre);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($offre);
        return new JsonResponse($formatted);
    }

    public function ModifierOffreAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $offre=$m->getRepository("ModuleDevisBundle:offre")->find($id);
        $m->persist($offre);
        $m->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($offre);
        return new JsonResponse($formatted);
    }


    public function findAction($id)
    {
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:offre')
            ->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mod);
        return new JsonResponse($formatted);
    }


    public function DeleteOffreAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:offre')
            ->find($id);

        $m->remove($mod);
        $m->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mod);
        return new JsonResponse($formatted);
    }

    public function AfficherDevisAction() {
        $mod = $this->getDoctrine()
            ->getRepository('ModuleDevisBundle:Devis')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mod);
        return new JsonResponse($formatted);
    }


    //ABiiir


    //OK
    public function GetAllProductAction()
    {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->findByPublie();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($produits);
        return new JsonResponse($formatted);
    }


    public function findProductByIdAction(Request $request)
    {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->find($request->get('id'));
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($produits);
        return new JsonResponse($formatted);
    }

//OK
    public function AjoutProductAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $produit = new Produit2();
        $produit->setTitre($request->get('titre'));
        $produit->setDescription($request->get('description'));
        $produit->setPrix($request->get('prix'));
        $produit->setImage($request->get('image'));
        $cate = $this->getDoctrine()
            ->getRepository('ProductBundle:Categorie')
            ->find($request->get('cat'));
        $produit->setCategorie(1);
        $souscate = $this->getDoctrine()
            ->getRepository('ProductBundle:SousCategorie')
            ->find($request->get('souscat'));
        $produit->setSousCategorie(1);
        $produit->setRating(0);
        $produit->setPublie(0);
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($request->get('user'));
        $produit->setOwnerUser($user);
        $img = $produit->getImage();

        $fileName = $this->generateUniqueFileName() . '.' . substr($img, strlen($img) - 3, strlen($img));;
        $produit->setImage($fileName);



        $em->persist($produit);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);
    }


//OK
    public function Rating1Action(Request $request)
    {


        $nbrating = intval($request->get('rating'));

        $produit = $request->get('produit');
        $prod = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->find($produit);


        $prod->setRating($prod->getRating() + $nbrating);

        $m = $this->getDoctrine()->getManager();
        $m->persist($prod);
        $m->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($prod);
        return new JsonResponse($formatted);


    }

//OK
    public function wishlist1Action(Request $request)
    {
        $produit = $request->get('produit');
        $userr = $request->get('user');;


        $wishlist = new WishlistProduit();


        $prod = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->find($produit);

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userr);


        $wishlist->setProduit($prod);


        $wishlist->setUser($user);

        $m = $this->getDoctrine()->getManager();
        $m->persist($wishlist);
        $m->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($wishlist);
        return new JsonResponse($formatted);

    }

    public function ProduitCroissantAction(Request $request)
    {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->OrderByPrixAsc();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($produits);
        return new JsonResponse($formatted);


    }


    public function ProduitDecroissantAction(Request $request)
    {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->OrderByPrixDesc();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($produits);
        return new JsonResponse($formatted);


    }

    public function ProduitRatingAction(Request $request)
    {
        $produits = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit2')
            ->OrderByRating();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($produits);
        return new JsonResponse($formatted);


    }

    public function ALLCategorieAction(Request $request)
    {
        $categorie = $this->getDoctrine()
            ->getRepository('ProductBundle:Categorie')
            ->findAll();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($categorie);
        return new JsonResponse($formatted);


    }


    public function SousCategorieAction(Request $request)
    {
        $cat = $request->get('cat');
        $souscat = $this->getDoctrine()
            ->getRepository('ProductBundle:SousCategorie')
            ->findBy(array('categorie' => $cat));
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($souscat);
        return new JsonResponse($formatted);


    }








    public function deleteProductAction(Request $request)
    {
        $produitId = $request->query->get('id_produit');
        $produit = $this->getDoctrine()->getRepository('ProductBundle:Produit2')->findOneById($produitId);
        if($produit){
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
            $response = "Produit deleted";
        }else{
            $response = "Produit not found";
        }
        return new JsonResponse($response);

    }

}
