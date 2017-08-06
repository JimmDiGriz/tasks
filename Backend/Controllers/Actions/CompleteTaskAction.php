<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Site\Controllers\Actions;


use Core\Base\Action;
use Core\WebApplication;
use Site\Models\Task;

class CompleteTaskAction extends Action
{
    public function run()
    {
        if (!WebApplication::$app->getUser()->isAdmin) {
            throw new \Exception('AccessDenied');
        }
        
        $taskId = $this->controller->request->getPostParam('taskId');
        
        if (!$taskId) {
            throw new \Exception('TaskIdRequired');
        }
        
        $model = Task::model()
            ->where('id', $taskId)
            ->limit(1)
            ->find();
        
        if (!$model) {
            throw new \Exception("TaskWithId[{$taskId}]NotFound");
        }
        
        $model = $model[0];
        
        $model->is_completed = 1;
        
        $model->save();
        
        return [
            'status' => true,
        ];
    }
}