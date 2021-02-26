<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Post;
use BlogBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class BlogController extends Controller
{


    public function AffichePostAction(Request $request) {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->findBy(array('publier'=>1));
        $categonries = $this->getDoctrine()
            ->getRepository('BlogBundle:CategorieBlog')->findAll();
        $postpops = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->OrderByMeilleur();


        return $this->render('@Blog/frontBlog/affichepost.html.twig',array('post'=>$posts,'categorie'=>$categonries,'postpop'=>$postpops ));

    }

    public function AffichePost2Action(Request $request,$id) {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->findBy(array('CategorieBlog' => $id,'publier'=>1));



        $template = $this->render('@Blog/frontBlog/post.html.twig', array('post'=>$posts))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);




    }



    public function detailPostAction(Request $request,$id) {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->findBySlug($id);
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

        return $this->render('@Blog/frontBlog/detailpost.html.twig',array('post'=>$posts,'categorie'=>$categonries,'postpop'=>$postpops,'comments' => $comments,
            'thread' => $thread));

    }

    public function AffichePostadminAction() {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->findAll();


        return $this->render('@Blog/BackBlog/affichepostadmin.html.twig', array('post' => $posts));

    }



    public function deletePostAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->findBySlug($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('event_afficheeventadmin'));
    }



    public function deletePostadminAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->findBySlug($id);
        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('blog_affichepostadminn'));
    }

    public function traiterPostadminAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->update($id);

        $m->flush();
        return $this->redirect($this->generateUrl('blog_affichepostATadminn'));

    }



    public function AddPostAction(Request $request)
    {
        $posts=new Post();
        $form=$this->createForm(PostType::class,$posts);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {


            $posts->setNombrelike(0);

            $posts->setPublier(0);
            $posts->setDatepost(new \DateTime('now'));
            $posts->setNombrevue(0);
            $posts->setSlug(-18);
            $file = $posts->getImage();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $posts->setImage($fileName);


            $m=$this->getDoctrine()->getManager();
            $m->persist($posts);
            $m->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', "Votre post est ajouté ! attend de confirmation d'administrateur")
            ;
            return $this->redirect($this->generateUrl('blog_ajoutpost'));

        }
        else{
            $request->getSession()
                ->getFlashBag()
                ->add('error', "Vérifier votre information !")
            ;
        }

        $formview=$form->createView();

        return $this->render('@Blog/frontBlog/ajoutpost.html.twig', array('form' => $formview));

    }



    public function AddPostadminAction(Request $request)
    {
        $posts=new Post();
        $form=$this->createForm(PostType::class,$posts);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {


            $posts->setNombrelike(0);

            $posts->setPublier(0);
            $posts->setDatepost(new \DateTime('now'));
            $posts->setNombrevue(0);
            $file = $posts->getImage();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $posts->setImage($fileName);


            $m=$this->getDoctrine()->getManager();
            $m->persist($posts);
            $m->flush();

            return $this->redirect($this->generateUrl('blog_ajoutpostadminn'));

        }

        $formview=$form->createView();

        return $this->render('@Blog/BackBlog/ajoutpostadmin.html.twig', array('form' => $formview));

    }


    public function getPostAction(Request $request)
    {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->findBy(array('publier'=>1));



        $template = $this->render('@Blog/frontBlog/post.html.twig', array('post'=>$posts))->getContent();


        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);

    }





    public function showing18Action(Request $request)
    {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->findBy(array('publier'=>1));
        $postss  = $this->get('knp_paginator')->paginate(
            $posts,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',18));

        $template = $this->render('@Blog/frontBlog/post.html.twig', array('post'=>$postss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);



    }



    public function showing10Action(Request $request)
    {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->findBy(array('publier'=>1));
        $postss  = $this->get('knp_paginator')->paginate(
            $posts,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',10));
        $template = $this->render('@Blog/frontBlog/post.html.twig', array('post'=>$postss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);



    }

    public function showing14Action(Request $request)
    {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->findBy(array('publier'=>1));
        $postss  = $this->get('knp_paginator')->paginate(
            $posts,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',14));
        $template = $this->render('@Blog/frontBlog/post.html.twig', array('post'=>$postss))->getContent();

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));
        $json = $serializer->serialize($template, 'json');
        return new Response($json);



    }





    public function UpdatepostadminAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $posts=$m->getRepository("BlogBundle:Post")->findBySlug($id);
        $form=$this->createForm(PostType::class,$posts);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $m->persist($posts);
            $m->flush();
            return $this->redirect($this->generateUrl('blog_affichepostadminn'));
        }
        $formview = $form->createView();

        return $this->render('@Blog/BackBlog/updatepostadmin.html.twig', array('form' => $formview));
    }



    public function detailPostadminAction(Request $request,$id) {
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')->findBySlug($id);


        $m=$this->getDoctrine()->getManager();
        $m->persist($posts);
        $m->flush();

        return $this->render('@Blog/BackBlog/detailpostadmin.html.twig',array('post'=>$posts));

    }





    public function traiterEvenementpublieAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->updatepublie($id);

        $m->flush();
        return new JsonResponse();


    }

    public function traiterEvenementnonpublieAction(Request $request,$id)
    {
        $m=$this->getDoctrine()->getManager();
        $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->updatenonpublie($id);

        $m->flush();
        return new JsonResponse();


    }




    public function LikepostAction(Request $request)
    {


        $postss=$request->request->get('post');
        $posts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->find($postss);

        $posts->setNombrelike($posts->getNombrelike()+1);

        $m=$this->getDoctrine()->getManager();
        $m->persist($posts);
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
