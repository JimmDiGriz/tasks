<?php

namespace Site\Controllers;
use Core\Base\DB\BaseModel;
use Core\Base\WebController;
use Core\WebApplication;
use Site\Models\Task;

/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */
class DefaultController extends WebController
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
        $model = Task::model()->limit(3);

        $pagesCount = Task::model()->count() / 3;
        $currentPage = 1;
        
        $query = $this->request->getQueryParam('query', false);
        
        if ($query) {
            $model->orderBy($query, BaseModel::DESCENDANT);
        }
        
        $page = $this->request->getQueryParam('page');
        
        if ($page) {
            $model->page($page);

            $currentPage = $page;
        }
        
         return $this->render('index', [
             'tasks' => $model->find(),
             'pagesCount' => $pagesCount,
             'currentPage' => $currentPage,
             'query' => $query ?? ''
         ]);
    }
    
    public function actionLogin()
    {
        if (!WebApplication::$app->getUser()->isGuest) {
            $this->redirect('/');
        }
        
        $errors = [
            'login' => false,
            'password' => false,
        ];
        
        if ($this->request->getPostParam('login')) {
            $login = $this->request->getPostParam('login');
            $password = $this->request->getPostParam('password');
            
            if ($login == 'admin' && $password == '123') {
                $this->session->set('user', [
                    'isGuest' => false,
                    'isAdmin' => true,
                ]);

                $this->redirect('/');
            } else {
                $errors['login'] = $login != 'admin';
                $errors['password'] = $password != '123';
            }
        }
        
        return $this->render('login', [
            'errors' => $errors,
        ]);
    }
    
    public function actionLogout()
    {
        $this->session->destroy();
        
        $this->redirect('/');
    }
    
    public function actionAdd()
    {
        if ($this->request->getPostParam('user_name')) {
            $task = Task::model();
            $this->manageTask($task);
            
            $this->redirect('/');
        }
        
        return $this->render('task_form', [
            'action' => 'add',
            'task' => Task::model(),
        ]);
    }
    
    public function actionEdit()
    {
        $taskId = $this->request->getQueryParam('taskId');
        
        if (!$taskId) {
            $this->redirect('/');
        }
        
        $task = Task::model()
            ->where('id', $taskId)
            ->limit(1)
            ->find();
        
        if (!$task) {
            $this->redirect('/');
        }
        
        /**@var \Site\Models\Task $task*/
        $task = $task[0];

//        var_dump($this->request->params['post']); die();
        
        if ($this->request->getPostParam('user_name')) {
            $this->manageTask($task);

            $this->redirect('/');
        }

        return $this->render('task_form', [
            'action' => 'edit',
            'task' => $task,
        ]);
    }
    
    /**
     * @param \Site\Models\Task $task
     */
    private function manageTask($task)
    {
        $task->load($this->request->params['post']);

        if (isset($_FILES['photo']) && $_FILES['photo']['size']) {
            /**@var \Core\Components\Upload\Upload $upload*/
            $upload = WebApplication::$app->get('upload');

            if ($task->photo) {
                $upload->remove($task->photo);
            }

            $fileName = $upload->upload($_FILES['photo']);

            $task->photo = $fileName;
        }

        $task->save();
    }
}