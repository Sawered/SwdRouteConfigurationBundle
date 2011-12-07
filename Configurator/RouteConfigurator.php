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

    public function findConfigFor($rc_config,$route /*deprecated (unused)*/,$url = null,$bundle = null)
    {
        $config = array();
        $founded_route = false;

        $founded_routes =  $this->findRoutesByPath($url);

        //if(!$resolved_route) $resolved_route = $this->current_route;
        if(!empty($founded_routes)){
            $last = array_pop($founded_routes);

            foreach($founded_routes as $route){
                $config = array_merge($config,$this->findRouteConfig($route,"prefix"));
            }
            //last always merging
            $config = array_merge($config,$this->findRouteConfig($last));

            if(null != $bundle){

                $config = array_merge($config,$this->findBundleConfig($bundle,$route,"prefix"));
            }
            //last always merging
            $config = array_merge($config,$this->findBundleConfig($bundle,$last));

        }

        return $config;

    }

    public function findRouteConfig($route,$type = false)
    {
        $result = array();
        $config = $this->rc_config["common_confs"];
        if(array_key_exists($route,$config)){
            if(false === $type || $config[$route]["match"] == $type)
                $result = $config[$route]["params"];
        }
        return $result;
    }

    public function findBundleConfig($bundle,$route,$type = false)
    {
        $result = array();
        $config = $this->rc_config["bundled_confs"];
        if(array_key_exists($route,$config)){
            if(array_key_exists($bundle,$config[$route])){
                if(false === $type || $config[$route][$bundle]["match"] == $type)
                    $result = $config[$route][$bundle]["params"];
            }
        }
        return $result;
    }

    public function findRoutesByPath($path)
    {
        $result = array();
        foreach($this->resolved_routes as $route => $prefix){
            if(strpos($path,$prefix) === 0){
                $result[] = $route;
            }
        }
        //var_dump("findede_route");
        //var_dump($prefix);
        //var_dump($result);
        return $result;
    }


    protected function resolvePrefixRoutes()
    {
        $routes = array();

        $routes = array_merge($routes,array_keys($this->rc_config["common_confs"]));
        $routes = array_merge($routes,array_keys($this->rc_config["bundled_confs"]));


        foreach($routes as $route) {
            $this->resolved_routes[$route] = $this->container->get("router")->generate($route);
        }

        asort($this->resolved_routes,SORT_STRING);
        //var_dump($this->resolved_routes);
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

    public function setRouteConfig($route_configuration)
    {
        $this->rc_config = $route_configuration;
    }

    public function setResolvedRoutes($resolved_routes)
    {
        $this->resolved_routes = $resolved_routes;
    }

}
