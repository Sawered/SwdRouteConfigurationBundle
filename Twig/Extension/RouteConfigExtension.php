<?php

/*
 *
 * (c) sawered@gmail.com
 *
 */

namespace Swd\Bundle\RouteConfigurationBundle\Twig\Extension;


//use Swd\Bundle\RouteConfigurationBundle\Configurator\RouteConfigurator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RouteConfigExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'rc_param' => new \Twig_Function_Method($this, 'getRouteParam'),
        );
    }

    public function getRouteParam($param,$default = null)
    {
        return $this->container->get("route_configurator")->getParameter($param,$default);
    }

    public function getName()
    {
        return "route-config";
    }
}

