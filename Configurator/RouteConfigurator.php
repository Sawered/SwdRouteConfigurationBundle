<?php

/*
 *
 * (c) sawered@gmail.com
 *
 */


namespace Swd\Bundle\RouteConfigurationBundle\Configurator;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class RouteConfigurator
{
    private $rc_config;
    private $container;
    private $current_route;
    private $current_url;
    private $resolved_routes = array();

    public function __construct(ContainerInterface $container,$route_configuration = array(),$options = array())
    {
        $this->container = $container;
        $this->rc_config = $route_configuration;
        if(isset($options["url"])) $this->setUrl($options["url"]);
        if(isset($options["route"])) $this->setRoute($options["route"]);

        $this->resolvePrefixRoutes();
    }


    public function getParameter($param,$default = null)
    {
        $result = null;
        list($bundle,$parameter) = $this->parseParam($param);

        $config =  $this->getConfig($bundle);
        if(array_key_exists($parameter,$config)){
            $result = $config[$parameter];
        }

        return (null == $result)?$default:$result;
    }

    public function getConfig($bundle = null)
    {
        static $conf_cache;
        $key = md5($this->current_route.$this->current_url.$bundle);

        if(!isset($conf_cache[$key])){
            $conf_cache[$key] =  $this->findConfigFor($this->rc_config,$this->current_route,$this->current_url,$bundle) ;
        }
        return $conf_cache[$key];
    }

    public function findConfigFor($rc_config,$route,$url = null,$bundle = null)
    {
        $config = array();
        $resolved_route = false;

        if(null != $url){
            $resolved_route =  $this->findRouteByPath($url);
        }
        //if(!$resolved_route) $resolved_route = $this->current_route;
        if($resolved_route){
            $config = array_merge($config, $this->findRouteConfig($resolved_route,$rc_config["route_prefix"]));
        }
        var_dump($config);
        $config = array_merge($config, $this->findRouteConfig($route,$rc_config["route_full"]));

        if(null != $bundle){
            if($resolved_route){
                $config = array_merge($config,$this->findBundleConfig($bundle,$resolved_route,$rc_config["bundle_route_prefix"]));
            }
            $config = array_merge($config,$this->findBundleConfig($bundle,$route,$rc_config["bundle_route_full"]));
        }
        return $config;

    }

    public function findRouteConfig($route,$config)
    {
        $result = array();

        if(array_key_exists($route,$config)){
            $result = $config[$route];
        }
        return $result;
    }

    public function findBundleConfig($bundle,$route,$config)
    {
        $result = array();
            if(array_key_exists($bundle,$config)){
                if(array_key_exists($route,$config[$bundle])){
                    $result = $config[$bundle][$route];
                }
            }
        return $result;
    }

    public function findRouteByPath($path)
    {
        $result = false;
        foreach($this->resolved_routes as $route => $prefix){
            if(strpos($path,$prefix) === 0){
                $result = $route;
                break;
            }
        }
        var_dump("findede_route");
        var_dump($prefix);
        var_dump($result);
        return $result;
    }


    protected function resolvePrefixRoutes()
    {
        $routes = array();
        foreach($this->rc_config["bundle_route_prefix"] as $bundle =>$route_confs) {
            $routes =  array_merge($routes, array_keys($route_confs));
        }
        foreach($this->rc_config["bundle_route_full"] as $bundle =>$route_confs) {
            $routes =  array_merge($routes, array_keys($route_confs));
        }

        $routes = array_merge($routes,array_keys($this->rc_config["route_prefix"]));
        $routes = array_merge($routes,array_keys($this->rc_config["route_full"]));


        foreach($routes as $route) {
            $this->resolved_routes[$route] = $this->container->get("router")->generate($route);
        }

        asort($this->resolved_routes,SORT_STRING);
        var_dump($this->resolved_routes);
    }


    public function parseParam($param)
    {
        if(strpos($param,":") === false)
            return array(null,$param);
        else
            return array_slice(explode(":",$param),0,2);
    }

    public function setRoute($route)
    {
        $this->current_route = $route;
    }

    public function setUrl($url)
    {
        $this->current_url = $url;
    }


}
