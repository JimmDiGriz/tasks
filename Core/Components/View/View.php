<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Components\View;

class View 
{
    private $config = [];
    
    /**
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }
    /**
     * @param string $file
     * @param array $params = array()
     * 
     * @return mixed
     */
    public function render($file, $params = array())
    {
        ob_start();
        ob_implicit_flush();
        extract($params);
        require(APP_PATH . (isset($this->config['viewPath']) ? $this->config['viewPath'] : '') . $file . '.php');
        
        $content = ob_get_clean();
        
        return $content;
    }
}