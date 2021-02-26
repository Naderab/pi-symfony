<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\CategorieBlog;
use BlogBundle\Form\CategorieBlogType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{



    public function AffichecategorieadminAction(Request $request) {

        $categories  = new CategorieBlog();

        $form=$this->createForm(CategorieBlogType::class,$categories);

        $form->handleRequest($request);
        if ($form->isValid())
        {
            $this->getDoctrine()->getManager()->persist($categories);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('blog_affichecategorieadminn');
        }


        $categonries = $this->getDoctrine()
            ->getRepository('BlogBundle:CategorieBlog')->findAll();


        return $this->render('@Blog/BackBlog/affichecategorie.html.twig',array('categorie'=>$categonries,'form'=>$form->createView() ));

    }


    public function deletecategorieadminAction($id) {
        $m=$this->getDoctrine()->getManager();
        $mod = $this->getDoctrine()
            ->getRepository('BlogBundle:CategorieBlog')->find($id);

        $m->remove($mod);
        $m->flush();

        return $this->redirect($this->generateUrl('blog_affichecategorieadminn'));
    }




}
