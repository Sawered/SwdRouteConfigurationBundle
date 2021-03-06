<?php

namespace Swd\Bundle\RouteConfigurationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SwdRouteConfigurationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        //var_export($config["by_route"]);exit();
        $parser  = new RouteConfigParser();
        $parsed_config = array();
        if(isset($config["by_route"])){
            $parser->parseByRoute($config["by_route"]);
            $parsed_config =  $parser->getResult();
        }
        //var_export($parsed_config);
        $container->setParameter("swd_route_configuration.configurator.route_config",$parsed_config);
    }
}
