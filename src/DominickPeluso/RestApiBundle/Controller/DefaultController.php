<?php

namespace DominickPeluso\RestApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DominickPelusoRestApiBundle:Default:index.html.twig', array('name' => $name));
    }
}
