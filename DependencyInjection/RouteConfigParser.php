<?php

namespace Swd\Bundle\RouteConfigurationBundle\DependencyInjection;

class RouteConfigParser
{
    public function __construct()
    {

    }

    public function parseByRoute($config)
    {
        $this->reset();

        foreach($config as $route =>$route_params){
            $this->addRouteConfig($route_params["match"],$route,$route_params["config"]);
        }
    }

    public function addRouteConfig($type,$route,$route_configs)
    {
        foreach($route_configs as $config){
            if(empty($config["bundles"])){
                if(isset($this->config["route_".$type][$route])){
                    $this->config["route_".$type] =  array_merge($this->config["route_".$type][$route],$config["params"]);
                }else{
                    $this->config["route_".$type][$route] =  $config["params"];
                }
            }else{
                foreach($config["bundles"] as $bundle){
                    if(isset($this->config["bundle_route_".$type][$bundle]) &&
                       isset($this->config["bundle_route_".$type][$bundle][$route])
                      ){
                        $this->config["bundle_route_".$type][$bundle][$route]
                        =  array_merge( $this->config["bundle_route_".$type][$bundle][$route],$config["params"]);
                    }else{
                        $this->config["bundle_route_".$type][$bundle][$route] = $config["params"];
                    }
                }

            }
        }
    }

    public function getResult()
    {
        return $this->config;
    }

    public function reset()
    {
        $this->config = array(
            "bundle_route_full"   => array(),
            "bundle_route_prefix" => array(),
            "route_full"          => array(),
            "route_prefix"        => array(),
        );
    }
}
