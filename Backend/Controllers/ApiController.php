<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Site\Controllers;

use Core\Base\WebController;
use Core\Values\HttpContentType;
use Site\Controllers\Actions\CompleteTaskAction;

class ApiController extends WebController
{
    public function beforeAction()  
    {
        parent::beforeAction();
        
        $this->response->setContentType(HttpContentType::JSON);
    }
    
    public function actions()
    {
        return [
            'complete' => CompleteTaskAction::class,
        ];
    }
}