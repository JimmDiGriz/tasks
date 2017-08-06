<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Base;

/**
 * @property \Core\Base\Controller $controller
  */
abstract class Action 
{
    public $controller;
    
    public function __construct($controller) 
    {
        $this->controller = $controller;
    }
    
    public abstract function run();
}