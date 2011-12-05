<?php

namespace Swd\Bundle\RouteConfigurationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('SwdRouteConfigurationBundle:Default:index.html.twig', array('name' => $name));
    }
}
