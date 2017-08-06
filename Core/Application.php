<?php

namespace Core;

use Core\Components\DI\Container;

class Application extends BaseApplication
{
    public $container;
    /**@var static $app*/
    public static $app;
    
    public function __construct() 
    {
        $this->container = new Container();
        
        self::$app = $this;
    }
    
    public function get($name)
    {
        return $this->container->get($name);
    }
    
    public function set($name, $callback)
    {
        $this->container->set($name, $callback);
    }
    
    public function has($name)
    {
        return $this->container->has($name);
    }
}