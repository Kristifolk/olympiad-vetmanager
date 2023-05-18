<section class="container" style="max-width: 670px;">
    <h2><?= $_SESSION['TestLogin'] ?></h2>
    <div>
        <br>
        <h3>Общее время прохождения заданий: <span
                class="changeable-text"><?= $this->arguments['taskTransitTime']['minute'] ?>:<?= $this->arguments['taskTransitTime']['second'] ?></span>
        </h3>
        <h3 hidden="hidden">Общий процент выполнения всех заданий: <span
                class="changeable-text"><?= $this->arguments['taskTransitTime']['resultPercentage'] ?></span>
        </h3>
        <h2>Поздравляем вас с прохождением заданий по программе Ветменеджер!</h2>
        <br>
        <h2>Благодарим за участие и желаем успехов!</h2>
        <br>
        <div>
            <a class="btn btn-for-a btn-start" href="/" style="margin-left: auto; margin-right: auto;">Главная</a>
        </div>
        <div>
            <a class="btn btn-for-a btn-start" href="/certificate" style="margin-left: auto; margin-right: auto;">Получить
                сертификат</a>
        </div>
    </div>
</section>