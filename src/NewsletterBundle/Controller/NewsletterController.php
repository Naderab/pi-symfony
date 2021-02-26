<?php

namespace NewsletterBundle\Controller;

use NewsletterBundle\Entity\Abonnement;
use NewsletterBundle\Entity\Newsletter;
use NewsletterBundle\Form\AbonnementType;
use NewsletterBundle\Form\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NewsletterController extends Controller
{

    public function ajoutAbonnementAction(Request $request)
    {
        $abonn =  new Abonnement();
        $form=$this->createForm(AbonnementType::class,$abonn);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $this->getDoctrine()->getManager()->persist($abonn);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('ajoutAbonner');}
        return $this->render('@Newsletter/Default/subscribe.html.twig',array('form'=>$form->createView()));
    }


    public function ajoutNewsAction(Request $request)
    {
        $news  = new Newsletter();

        $form=$this->createForm(NewsletterType::class,$news);

        $form->handleRequest($request);
        $news->setDateCreation(new \DateTime());
        if ($form->isValid())
        {
            $this->getDoctrine()->getManager()->persist($news);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('listnews');

        }


        return $this->render('@Newsletter/Default/ajoutNewsletter.html.twig',array('form'=>$form->createView()));
    }

    public function updateNewsAction(Request $request,$id)
    {
        {
            $news = $this->getDoctrine()->getRepository(Newsletter::class)->find($id);
            $form = $this->createForm(NewsletterType::class, $news);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $m->flush();
                return $this->redirect($this->generateUrl('listnews'));
            }

            $formview = $form->createView();

            return $this->render('@Newsletter/Default/ajoutNewsletter.html.twig', array('form' => $formview));

        }
    }


    public function deleteNewsAction($id){
        $em=$this->getDoctrine()->getManager();
        $news=$this->getDoctrine()->getRepository(Newsletter::class)->find($id);
        $em->remove($news);
        $em->flush();
        return $this->redirectToRoute("listnews");

    }

    public function listNewsAction()
    {
        $news = $this->getDoctrine()->getRepository(Newsletter::class)->findAll();
        return $this->render('@Newsletter/Default/listNews.html.twig', array(
            'news' => $news
        ));

    }
    public function listNewsletterAction($id)
    { $em=$this->getDoctrine()->getManager();
        $post=$em->getRepository("NewsletterBundle:Newsletter")->findNEWSdql($id);
        return $this->render('@Newsletter/Default/listNewsENV.html.twig', array(
            'news' => $post
        ));

    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function  sendmailAction($id)
    {$em=$this->getDoctrine()->getManager();
        $post=$em->getRepository("NewsletterBundle:Newsletter")->findNEWSdql($id);
        $html= $this->render('@Newsletter/Default/news.html.twig', array(
            'news' => $post
        ));
        $forward=$em->getRepository("NewsletterBundle:Abonnement")->findemail();
        for($i=0;$i<count($forward);$i++)
        {
            $to =$forward[$i];

            $subject = "NEWSLETTER";

            $htmlContent = $html;

// Set content-type header for sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
            $headers .= 'From: koukiziedd711@gmail.com' ."\r\n";
            $headers .= 'Cc: welcome@example.com' . "\r\n";
            $headers .= 'Bcc: welcome2@example.com' . "\r\n";



// Send email
            mail($to,$subject,$htmlContent,$headers);




            return $this->redirectToRoute("listnews");
        }
    }

















}
