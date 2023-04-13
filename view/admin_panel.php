<h2 class="header-task">Панель администратора<span class="task-number" hidden="hidden">1</span></h2>
<div class="container-task container container-admin">
    <input id="inputSearch" class="input-search input-form" placeholder="Поле поиска пользователей по логину">
    <ul id="content-table">
        <?php foreach ($this->arguments['resultTask'] as $value) :
            $i = 0; ?>
            <li>
                <h3 class="login-container">Логин</h3>
                <span id="login-user"
                      class="login-text changeable-text"><?= $value[0][0] ?></span>
                <table class="table-admin">
                    <tr>
                        <th>№</th>
                        <th>Задание</th>
                        <th>Кол-во баллов</th>
                        <th>Выполнено</th>
                    </tr>
                    <?php foreach ($value[1] as $item) : ?>
                        <tr>
                            <td><?= ++$i ?></td>
                            <td><?= $item["title"] ?></td>
                            <td><?= $item["marks"] ?></td>
                            <td><?= $item["done"] ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </li>
        <?php endforeach ?>
    </ul>
</div>
