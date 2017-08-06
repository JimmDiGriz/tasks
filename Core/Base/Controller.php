<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Base;


abstract class Controller
{
    const INDEX_ACTION = 'index';

    public abstract function actions();
    /**
     * @param string $action
     */
    public abstract function runAction($action);
    
    public function beforeAction()  
    {
        
    }
    
    public function afterAction()
    {
        
    }
    
    /**
     * @param \Core\Base\Action|string $action
     * 
     * @return mixed
     */
    protected function execute($action)
    {
        $this->beforeAction();
        
        $result = '';
        
        if (is_string($action)) {
            $result = $this->{$action}();
        } else {
            $result = $action->run();
        }
        
        $this->afterAction();
        
        return $result;
    }
}