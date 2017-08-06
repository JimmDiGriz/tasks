<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Components\Request;

use Core\Helpers\ArrayHelper;

class Request
{
    public $params = [];
    public $allParams = [];
    private $route = '';
    private $host = '';
    
    public function __construct()
    {
        $this->params['get'] = [];
        
        foreach ($_GET as $name => $value) {
            $this->params['get'][$name] = trim($value);
        }
        
        $this->params['post'] = [];

        foreach ($_POST as $name => $value) {
            $this->params['post'][$name] = trim($value); 
        }
        
        $this->allParams = ArrayHelper::merge($this->params['get'], $this->params['post']);
        
        $this->route = $_SERVER['REQUEST_URI'];
        $this->host = $_SERVER['HTTP_HOST'];
    }

    /**
     * @param string $name
     * @param mixed $default = null
     *
     * @return mixed
     */
    public function getQueryParam($name, $default = null)
    {
        if (!isset($this->params['get'][$name])) {
            return $default;
        }
        
        return $this->params['get'][$name];
    }

    /**
     * @param string $name
     * @param mixed $default = null
     *
     * @return mixed
     */
    public function getPostParam($name, $default = null)
    {
        if (!isset($this->params['post'][$name])) {
            return $default;
        }

        return $this->params['post'][$name];
    }
    
    /**
     * @param string $name
     * @param mixed $default = null
     * 
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (!isset($this->allParams[$name])) {
            return $default;
        }

        return $this->allParams[$name];
    }
    
    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }
    
    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }
}