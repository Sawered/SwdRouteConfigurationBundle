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


    public function getName()
    {
        return "route-config";
    }
}

