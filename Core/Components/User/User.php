<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Components\User;

use Core\Interfaces\UserInterface;
use Core\WebApplication;

class User implements UserInterface
{
    public $isGuest = true;
    public $isAdmin = false;

    function authenticate()
    {
        /**@var \Core\Components\Session\Session $session*/
        $session = WebApplication::$app->get('session');
        
        $user = $session->get('user');
        
        if ($user) {
            $this->isGuest = $user['isGuest'];
            $this->isAdmin = $user['isAdmin'];
        }
    }
}