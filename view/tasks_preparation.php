<?php

use App\Services\View;

/** @var View $this */
?>

<section class="container">
    <h2>Подготовка к прохождению олимпиады</h2>
    <div>
        <p>Вам будет предложено пройти 1 задание</p>
        <p>Общее время прохождения олимпиады будет занимать 25 минут</p>
        <p>По истечении времени выполнение заданий не будет засчитано</p>
        <p>Вы можете закончить олимпиаду досрочно</p>
        <p>Желаем вам удачи!</p>
        <h1>Ваш логин: <?= $this->arguments["login"] ?></h1>
        <h1>Ваш пароль: <?= $this->arguments["password"] ?></h1>
        <h1>Платформа для выполнения задания: <a href="https://deviproff.vetmanager2.ru/login.php" target="_blank">deviproff.vetmanager.ru</a>
        </h1>
        <div class="btn-content">
            <a class="btn btn-for-a btn-start" href="/start">Старт</a>
            <a class="btn btn-for-a btn-start" href="/">Назад</a>
        </div>
    </div>
</section>