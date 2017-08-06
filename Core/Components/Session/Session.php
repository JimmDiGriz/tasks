<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Components\Session;


class Session
{
    private $isStarted = false;
    
    /**
     * @return bool
     */
    public function start()
    {
        if ($this->isStarted) {
            return true;
        }
        
        if (session_id()) {
            $this->isStarted = true;
            return true;
        }
        
        $this->isStarted = session_start();
        
        return $this->isStarted;
    }
    
    /**
     * @param string $name
     * @param mixed $default
     * 
     * @return mixed
     */
    public function get($name, $default = false)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        
        return $default;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }
    
    public function destroy()
    {
        if ($this->isStarted || session_id()) {
            session_destroy();
        }
    }
}