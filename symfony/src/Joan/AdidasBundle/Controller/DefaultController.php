<?php

namespace Joan\AdidasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JoanAdidasBundle:Default:index.html.twig');
    }
}
