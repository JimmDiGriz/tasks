<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */
use Core\Helpers\Url;

/**
 * @var string $action
 * @var \Site\Models\Task $task
 */

?>

<form action="<?= Url::to('/default/' . $action, ['taskId' => $task->id]) ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="userNameInput">Имя пользователя</label>
        <input type="text" name="user_name" class="form-control" id="userName" placeholder="Имя пользователя" value="<?= $task->user_name ?>">
    </div>
    <div class="form-group">
        <label for="emailInput">Email пользователя</label>
        <input type="email" name="user_email" class="form-control" id="emailInput" placeholder="Email пользователя" value="<?= $task->user_email ?>">
    </div>
    <div class="form-group">
        <label for="textInput">Текст</label>
        <input type="text" name="text" class="form-control" id="textInput" placeholder="Текст" value="<?= $task->text ?>">
    </div>
    <div class="form-group">
        <label for="photoInput">Фото</label>
        <input type="file" name="photo" class="form-control" id="photoInput">
    </div>
    <button type="submit" class="btn btn-default">Сохранить</button>
</form>
<br><br>
<button type="button" class="btn btn-default" id="run-preview">Предпросмотр</button>

<table class="table table-hover hidden" id="task-preview">
    <tr>
        <td>Имя</td>
        <td>Email</td>
        <td>Текст</td>
        <td>Фото</td>
        <td>Решена</td>
    </tr>
    <tr>
        <td class="text"><span id="preview-name"></span></td>
        <td class="text"><span id="preview-email"></span></td>
        <td class="text"><span id="preview-text"></span></td>
        <td>
            <img id="preview-image" src="/Uploads/<?= $task->photo ?>" class="img-responsive img-circle little center">
        </td>
        <td>Нет</td>
    </tr>
</table>

