<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */
 
return [
        'db' => require(__DIR__ . '/db.php'),
        'router' => [
            'defaultController' => 'DefaultController',
            'errorController' => 'ErrorController',
            'controllerNamespace' => 'Site\\Controllers\\',
        ],
        'view' => [
            'viewPath' => '/Backend/Views/',
        ],
        'upload' => [
            'uploadPath' => '/Frontend/Uploads/',
            'allowedTypes' => [
                'image/jpeg',
                'image/png',
                'image/gif',
            ],
            'maxSize' => [
                'width' => 320,
                'height' => 240,
            ],
        ],
];