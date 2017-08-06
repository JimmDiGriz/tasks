<?php

namespace Site\Controllers;
use Core\Base\WebController;

/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */
class ErrorController extends WebController
{
    public function actions()
    {
        return [
            
        ];
    }

    public function beforeAction()
    {
        parent::beforeAction();

        $this->layout = 'main';
    }
    
    public function actionIndex()
    {
        
    }
}