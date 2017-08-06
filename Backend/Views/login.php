<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

/**
 * @var string $errors
 */

?>

<form action="/default/login" method="post">
    <div class="form-group <? if ($errors['login']): ?>has-error<? endif; ?>">
        <label for="loginInput">Логин</label>
        <input type="text" name="login" class="form-control" id="loginInput" placeholder="Логин">
    </div>
    <div class="form-group <? if ($errors['password']): ?>has-error<? endif; ?>">
        <label for="passwordInput">Password</label>
        <input type="password" name="password" class="form-control" id="passwordInput" placeholder="Пароль">
    </div>
    <button type="submit" class="btn btn-default">Войти</button>
</form>
