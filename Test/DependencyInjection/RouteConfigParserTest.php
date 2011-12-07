<?php

namespace Swd\Bundle\RouteConfigurationBundle\Test\DependencyInjection;
use Swd\Bundle\RouteConfigurationBundle\DependencyInjection\RouteConfigParser;


class RouteConfigParserTest extends \PHPUnit_Framework_TestCase
{

    static function getTestConfig()
    {
        $config = array();
        $home_config = array(
            array(
                 "params"  => array(
                    "param_a" => "home-pref"
                 ),
                 "bundles" => array(),
            ),
            array(
                 "params"  => array(
                    "param_b" => "bundle1-home-pref"
                 ),
                 "bundles" => array("bundle1"),
            ),
        );
        $about_config = array(
            array(
                 "params"  => array(
                    "param_a" => "about-pref"
                 ),
            ),
            array(
                 "params"  => array(
                    "param_a" => "bundle1-about-pref"
                 ),
                 "bundles" => array("bundle1"),
            ),
        );

        $feat_conf = array(
            array(
                 "params"  => array(
                    "param_a" => "feat-full"
                 ),
                 "bundles" => array("bundle1"),
            ),
            array(
                 "params"  => array(
                    "param_a" => "bundle2-feat-full"
                 ),
                 "bundles" => array("bundle2"),
            ),

            array(
                 "params"  => array(
                    "param_b" => "b-feat-full"
                 ),
            ),
        );

        $config["home"][] = array(
            "match"  => "prefix",
            "config" => $home_config,
        );

        $config["about"][] = array(
            "match"  => "prefix",
            "config" => $about_config,
        );
    }

    function testParseByRoute()
    {
         $parser =   new RouteConfigParser();
    }
}
