<?php

/*
 *
 * (c) sawered@gmail.com
 *
 */

namespace Swd\Bundle\RouteConfigurationBundle\EventListener;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
//use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Exception\MethodNotAllowedException;
//use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;
//use Symfony\Component\Routing\RequestContext;
use Swd\Bundle\RouteConfigurationBundle\Configurator\RouteConfigurator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Initializes config based on a matching route.
 *
 */
class RouterListener implements ContainerAwareInterface
{
    private $logger;
    //private $rc;
    private $container;

    public function __construct(LoggerInterface $logger,ContainerInterface $container = null)
    {
        $this->logger = $logger;

        $this->setContainer($container);

    }
    function setContainer(ContainerInterface $container = null)
    {

        if(null != $container){
            $this->container = $container;
            /*
            if($container->has("route_configurator")){
               // var_dump("loaded");
                $this->rc = $container->get("route_configurator");
            }
            */
        }
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            //if(null == $this->rc) return;

            if ($request->attributes->has('_controller')) {
                // routing is already done
                $route = $request->attributes->get('_route');


                $url = $request->getBaseUrl().$request->getPathInfo();
                //$this->rc->setRoute($route);
                //$this->rc->setUrl($url);
                $this->container->get("route_configurator")->setRoute($route);
                $this->container->get("route_configurator")->setUrl($url);

                //$this->container->setParameter("swd_route_configuration.configurator.options",array("route"=>$route,"url"=>$url));

                if(null != $this->logger){
                    $this->logger->info(sprintf("Set route_configuration for route [%s]",$route));
                }
                return;
            }
        }
        return;
    }
}
