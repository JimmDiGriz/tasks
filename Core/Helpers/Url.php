<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Helpers;


class Url
{
    public static function to($route, $params)
    {
        $result = $route;
        
        if (substr($result, -1) != '/') {
            $result .= '/';
        }
        
//        $paramsCount = count($params);
        
        $result .= '?';
        
        foreach ($params as $name => $value) {
            $result .= "{$name}={$value}&";
        }

        $result = mb_substr($result, 0, mb_strlen($result) - 1);
        
        return $result;
    }
}