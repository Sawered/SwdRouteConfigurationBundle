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

/**
 * Initializes config based on a matching route.
 *
 */
class RouterListener
{
    private $router;
    private $logger;

    public function __construct(RouterInterface $router, LoggerInterface $logger)
    {
        $this->router = $router;
        $this->logger = $logger;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            if ($request->attributes->has('_controller')) {
                // routing is already done
                $route = $request->attributes->get('_route');
                // get params from config and add to service
                if(null != $this->logger){
                    $this->logger->info(sprintf("Load configuration for route [%s]",$route));
                }
                return;
            }
        }
        return;
    }
}
