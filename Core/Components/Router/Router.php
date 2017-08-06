<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Components\Router;
use Core\Application;

/**
 * Maximum route /controller/action/ for task requirements.
 */
class Router
{
    /**@var \Core\Components\Request\Request $request*/
    private $request = null;
    private $config =[];
    
    public function __construct($config)
    {
        $this->config = $config;
    }
    
    /**
     * @param \Core\Components\Request\Request $request
     */
    public function run($request)
    {
        $this->request = $request;
        
        $controller = $this->getController();
        
        $action = $this->getAction();
        
        $result = $controller->runAction($action);
        
        /**@var \Core\Components\response\Response $response*/
        $response = Application::$app->container->get('response');
        
        $response->setData($result);
    }
    
    /**
     * @return \Core\Base\WebController
     */
    private function getController()
    {
        if (!$this->request) {
            return $this->getDefaultController();
        }
        
        $parts = explode('/', $this->request->getRoute());
        
        if (!isset($parts[1]) || !$parts[1]) {
            return $this->getDefaultController();
        }
        
        $controllerName = $this->config['controllerNamespace'] . ucfirst(mb_strtolower($parts[1])) . 'Controller';

        $controller = new $controllerName();

        return $controller;
    }

    /**
     * @return \Core\Base\WebController
     */
    private function getDefaultController()
    {
        $controllerName = $this->config['controllerNamespace'] . $this->config['defaultController'];

        $controller = new $controllerName();

        return $controller;
    }
    
    /**
     * @return \Core\Base\Action
     */
    private function getAction()
    {
        if (!$this->request) {
            return null;
        }

        $parts = explode('/', $this->request->getRoute());

        if (!isset($parts[2]) || !$parts[2]) {
            return null;
        }
        
        return mb_strtolower($parts[2]);
    }
}