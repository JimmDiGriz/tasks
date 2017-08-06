<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Components\DI;

class Container
{
    private $instances = []; 
    private $config = [];
    
    public function get($name)
    {
        if (!isset($this->config[$name])) {
            throw new \Exception($name . ' Is Not Provided');
        }
        
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = $this->config[$name]();
        }
        
        return $this->instances[$name];
    }
    
    public function set($name, $callback)
    {
        if (!isset($this->config[$name])) {
            if (!is_callable($callback)) {
                throw new \Exception('Container::set Expected Second Parameter Being Callable');
            }
            
            $this->config[$name] = $callback;
        }
    }
    
    public function has($name): bool
    {
        return isset($this->instances[$name]) || isset($this->config[$name]);
    }
}