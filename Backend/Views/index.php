<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */
use Core\Helpers\Url;
use Core\WebApplication;

/**
 * @var \Site\Models\Task[] $tasks
 * @var int $pagesCount
 * @var int $currentPage
 * @var string $query
 */
?>

<table class="table table-hover">
<tr>
    <td>
        <a href="<?= Url::to('/default/index', ['page' => $currentPage, 'query' => 'user_name'])?>">Имя</a>
    </td>
    <td>
        <a href="<?= Url::to('/default/index', ['page' => $currentPage, 'query' => 'user_email'])?>">Email</a>
    </td>
    <td>Текст</td>
    <td>Фото</td>
    <td>
        <a href="<?= Url::to('/default/index', ['page' => $currentPage, 'query' => 'is_completed'])?>">Решена</a>
    </td>
    <? if (WebApplication::$app->getUser()->isAdmin): ?>
        <td>Редактировать</td>
    <? endif; ?>
</tr>

<? foreach ($tasks as $task): ?>
    <tr>
        <td class="text"><span><?= $task->user_name ?></span></td>
        <td class="text"><span><?= $task->user_email ?></span></td>
        <td class="text"><span><?= $task->text ?></span></td>
        <td><img src="/Uploads/<?= $task->photo ?>" class="img-responsive img-circle little center"></td>
        <td>
            <? if (WebApplication::$app->getUser()->isAdmin && !$task->is_completed): ?>
                <a href="#" class="complete-task" data-task="<?= $task->id ?>"><? if ($task->is_completed): ?> Да <? else: ?> Нет <? endif; ?></a>
            <? else: ?>
            <? if ($task->is_completed): ?> Да <? else: ?> Нет <? endif; ?>
            <? endif; ?>
        </td>
        <? if (WebApplication::$app->getUser()->isAdmin): ?>
            <td>
                <a href="<?= Url::to('/default/edit', ['taskId' => $task->id])?>">Редактировать</a>
            </td>
        <? endif; ?>
    </tr>
<? endforeach; ?>
</table>

<nav aria-label="Page navigation">
    <ul class="pagination center">
        <? if ($currentPage > 1): ?>
        <li>
            <a href="<?= Url::to('/default/index', ['page' => $currentPage - 1, 'query' => $query]) ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <? endif; ?>
        <li>
            <a href="#" aria-label="Previous">
                <span aria-hidden="true"><?= $currentPage ?></span>
            </a>
        </li>
        <? if ($pagesCount > $currentPage): ?>
        <li>
            <a href="<?= Url::to('/default/index', ['page' => $currentPage + 1, 'query' => $query]) ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        <? endif; ?>
    </ul>
</nav>
