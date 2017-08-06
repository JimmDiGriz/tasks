<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Helpers;

class ArrayHelper 
{
    public static function merge($first, $second, $isSimple = false): array
    {
        if ($isSimple) {
            return array_merge($first, $second);
        }
        
        $result = $first;
        
        foreach ($second as $key => $value)
        {
            if (isset($result[$key]) && is_array($value)) {
                if (!is_array($result[$key])) {
                    $result[$key] = [$result[$key]];
                }
                
                $result[$key] = self::merge($result[$key], $value);
                
                continue;
            }
            
            $result[$key] = $value;
        }
        
        return $result;
    }
}