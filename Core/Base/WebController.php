<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Base;
use Core\Values\HttpStatusCode;
use Core\WebApplication;

/**
 * @property \Core\Components\Request\Request $request
 * @property \Core\Components\Response\Response $response
 * @property string $layout
 */
abstract class WebController extends Controller
{
    const INDEX_ACTION = 'index';
    
    /**@var \Core\Components\Request\Request*/
    public $request = null;
    /**@var \Core\Components\Response\Response*/
    public $response = null;
    /**@var \Core\Components\View\View*/
    private $view = null;
    /**@var \Core\Components\Session\Session*/
    protected $session = null;
    
    public $layout;
    
    public function beforeAction()
    {
        parent::beforeAction();
        
        $this->session->start();
        
        /**@var \Core\Interfaces\UserInterface $user*/
        $user = WebApplication::$app->get('user');
        
        $user->authenticate();
    }
    
    public function afterAction()
    {
        parent::afterAction();
    }
    
    /**
     * @param string $action
     * 
     * @throws \Exception
     * 
     * @return mixed
     */
    public function runAction($action)
    {
        $this->request = WebApplication::$app->container->get('request');
        $this->response = WebApplication::$app->container->get('response');
        $this->view = WebApplication::$app->container->get('view');
        $this->session = WebApplication::$app->container->get('session');
        
        $actions = $this->actions();
        
        if ((!$actions || count($actions) == 0) && $action) {
            $method = 'action' . ucfirst($action);

            return $this->execute($method);
        }
        
        if (!$action) {
            if (!$actions || !isset($actions[self::INDEX_ACTION])) {
                $method = 'action' . ucfirst(self::INDEX_ACTION);

                return $this->execute($method);
            } else {
                /**@var \Core\Base\Action $action*/
                $action = new $actions[self::INDEX_ACTION]($this);
                
                return $this->execute($action);
            }
        }
        
        if (!$action && !in_array($action, $actions)) {
            throw new \Exception('ActionNotFound', 404);
        }
        
        $action = new $actions[$action]($this);

        return $this->execute($action);
    }

    /**
     * Supports only one layout and one view
     * 
     * @param string $file
     * @param array $params = array()
     *
     * @return mixed
     */
    public function render($file, $params = []) 
    {
        $content = $this->view->render($file, $params);
        
        return $this->view->render('Layouts/' . $this->layout, [
            'content' => $content
        ]);
    }
    
    /**
     * @param string $url
     * @param int $code = 302
     * 
     * @return \Core\Components\Response\Response
     */
    public function redirect($url, $code = HttpStatusCode::TEMPORARY_REDIRECT)
    {
        $this->response->addHeader('Location: http://' . $this->request->getHost() . $url);
        $this->response->setStatusCode($code);
        
        exit;
    }
}
