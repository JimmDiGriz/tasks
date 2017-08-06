<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */
use Core\WebApplication;

/**
 * @var mixed $content
 */

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Задачник</title>
</head>

<body>

<nav class="navbar navbar-inverse navbar-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            </button>
            <a class="navbar-brand" href="/">Задачник</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="/default/add">Добавить</a>
                </li>
                <li>
                    <? if (WebApplication::$app->getUser()->isGuest): ?>
                    <a href="/default/login">Войти</a>
                    <? else: ?>
                    <a href="/default/logout">Выйти</a>
                    <?endif?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?= $content ?>
</div>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<link rel="stylesheet" href="/css/style.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="/js/index.js"></script>
</body>
</html>
