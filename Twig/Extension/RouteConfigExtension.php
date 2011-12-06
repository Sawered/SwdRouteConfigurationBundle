<?php

/*
 *
 * (c) sawered@gmail.com
 *
 */

namespace Swd\Bundle\RouteConfigurationBundle\Twig\Extension;


use Swd\Bundle\RouteConfigurationBundle\Configurator\RouteConfigurator;

class RouteConfigExtension extends \Twig_Extension
{
    private $route_conf;

    public function __construct(RouteConfigurator $route_config)
    {
        $this->route_conf = $route_config;
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
        return $this->route_conf->getParameter($param,$default);
    }

    public function getName()
    {
        return "route-config";
    }
}

