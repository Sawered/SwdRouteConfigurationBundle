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
                if(!isset($this->config["common_confs"][$route]))
                    $this->config["common_confs"][$route] = array();

                $this->config["common_confs"][$route]["params"] = $config["params"];
                $this->config["common_confs"][$route]["match"] = $type;
            }else{
                if(!isset($this->config["bundled_confs"][$route]))
                    $this->config["bundled_confs"][$route] = array();
                foreach($config["bundles"] as $bundle){
                    if(!isset($this->config["bundled_confs"][$route][$bundle]))
                        $this->config["bundled_confs"][$route][$bundle] = array();


                    $this->config["bundled_confs"][$route][$bundle]["params"] = $config["params"];
                    $this->config["bundled_confs"][$route][$bundle]["match"] = $type;

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
            "common_confs"  => array(),
            "bundled_confs" => array(),
        );
    }
}
