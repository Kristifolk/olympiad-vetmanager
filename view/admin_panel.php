<h2 class="header-task">Панель администратора<span class="task-number" hidden="hidden">1</span></h2>
<div class="container-task container container-admin">
    <input id="inputSearch" class="input-search input-form" placeholder="Поле поиска пользователей по логину">
    <div id="content-table">
        <?php if(!empty($this->arguments['resultTask'])): ?>
        <?php foreach ($this->arguments['resultTask'] as $value) :
        $i = 0;
        $countMarks = 0?>
        <article>
            <h3>Участник: <?= $value[0]["lastName"] ?> <?= $value[0]["firstName"] ?> <?= $value[0]["middleName"] ?></h3>
            <h3 class="login-container">Логин
                <span id="login-user"
                      class="login-text changeable-text"><?= $value[1]["login"] ?></span></h3>
            <table class="table-admin">
                <tr>
                    <th>№</th>
                    <th>Задание</th>
                    <th>Кол-во баллов</th>
                    <th>Выполнено</th>
                </tr>
                <?php foreach ($value[2] as $item) : ?>
                <?php if($item["done"] === "false") :?>
                <tr class="tr-failed-user">
                    <td><?= ++$i ?></td>
                    <td><?= $item["title"] ?></td>
                    <td><?= $item["marks"] ?></td>
                    <td><?= $item["done"] ?></td>
                </tr>
                <?php endif ?>
                <?php if($item["done"] === "true") :?>
                <tr>
                    <td><?= ++$i ?></td>
                    <td><?= $item["title"] ?></td>
                    <td><?= $item["marks"] ?></td>
                    <td><?= $item["done"] ?></td>
                </tr>
                <?php $countMarks += (int)$item["marks"];
                    endif ?>
                <?php endforeach ?>
            </table>
            <h3>Общее количество баллов: <?= $countMarks ?> </h3>
            <hr>
        </article>
        <?php endforeach ?>
        <?php endif ?>
    </div>
</div>
