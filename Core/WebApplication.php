<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core;

use Core\Components\DB\DBConnection;
use Core\Components\Request\Request;
use Core\Components\Response\Response;
use Core\Components\Router\Router;
use Core\Components\Session\Session;
use Core\Components\Upload\Upload;
use Core\Components\User\User;
use Core\Components\View\View;

class WebApplication extends Application
{
    private $defaultComponents = array();
    private $config = array();
    
    /**
     * @param array $config
     * 
     * @throws \Exception
     */
    public function __construct($config)
    {
        parent::__construct();
        
        if (!isset($config['db'])) {
            throw new \Exception('DBComponent Config Missing');
        }

        if (!isset($config['router'])) {
            throw new \Exception('RouterComponent Config Missing');
        }
        
        $this->config = $config;
    }
    
    private function getDefaultComponents()
    {
        if (count($this->defaultComponents) == 0) {
            $this->defaultComponents['request'] = function() {
                return new Request();
            };
            
            $this->defaultComponents['response'] = function() {
                return new Response();
            };
            
            $viewConfig = $this->config['view'];
            
            $this->defaultComponents['view'] = function() use ($viewConfig) {
                return new View($viewConfig);
            };
            
            $routerConfig = $this->config['router'];
            
            $this->defaultComponents['router'] = function() use ($routerConfig) {
                return new Router($routerConfig);
            };
            
            $dbConfig = $this->config['db'];
            
            $this->defaultComponents['db'] = function() use ($dbConfig) {
                return new DBConnection($dbConfig);
            };
            
            $this->defaultComponents['session'] = function() {
                return new Session();
            };
            
            $this->defaultComponents['user'] = function() {
                return new User();
            };
            
            $uploadConfig = $this->config['upload']; 
            
            $this->defaultComponents['upload'] = function() use ($uploadConfig) {
                return new Upload($uploadConfig['uploadPath'], $uploadConfig['allowedTypes'], $uploadConfig['maxSize']);
            };
        }
        
        return $this->defaultComponents;
    }
    
    public function init()
    {
        foreach ($this->getDefaultComponents() as $name => $component) {
            if (!$this->container->has($name)) {
                $this->container->set($name, $component);
            }
        }
    }
    
    public function run()
    {
        $this->init();
        
        /**
         * @var \Core\Components\Router\Router $router
         */
        $router = $this->container->get('router');

        /**@var \Core\Components\Response\Response $response*/
        $response = $this->container->get('response');

        $response->setHeaders();
        
        $router->run($this->container->get('request'));
        
        echo $response;
    }
    
    /**
     * @return \Core\Components\User\User
     */
    public function getUser() 
    {
        return $this->container->get('user');
    }
    
    public function errorHandler($code, $message, $file, $line, $context)
    {
        
    }
}