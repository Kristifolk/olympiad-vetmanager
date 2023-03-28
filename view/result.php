<section>
    <h2>Ваш результат</h2>
    <div>
        <h3>Общее время прохождения олимпиады: <span class="changeable-text"></span></h3>
        <h3>Общий процент выполнения всех заданий: <span class="changeable-text"></span></h3>
        <div>
            <h3>Результаты выполнения первой задачи</h3>
            <p>Время выполнения: <span class="changeable-text"><?= $this->arguments['fullTransitTime']['minute']?>:<?= $this->arguments['fullTransitTime']['second']?></span></p>
            <p>Процент выполнения: <span class="changeable-text"></span></p>
            <?php foreach ($this->arguments['taskTransitTime'] as $task) :?>
                <h3>Результаты выполнения задачи <?= $task['numberTask'] ?></h3>
                <p>Время выполнения: <span class="changeable-text"><?= $task['minute'] ?>:<?= $task['second'] ?></span></p>
                <p>Процент выполнения: <span class="changeable-text"></span></p>
            <?php endforeach ?>
            <a class="btn btn-for-a btn-start" href="/">Главная</a>
        </div>
    </div>
</section>
