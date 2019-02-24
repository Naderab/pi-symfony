<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    public function indexadminAction()
    {
        return $this->render('default/backoffice.html.twig');
    }
}
