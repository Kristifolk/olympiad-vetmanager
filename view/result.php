<section class="container">
    <h2>Ваш результат</h2>
    <div>
        <h3>Общее время прохождения олимпиады: <span
            class="changeable-text"><?= $this->arguments['taskTransitTime']['minute'] ?>:<?= $this->arguments['taskTransitTime']['second'] ?></span>
        </h3>
        <h3>Общий процент выполнения всех заданий: <span
            class="changeable-text"><?= $this->arguments['taskTransitTime']['resultPercentage'] ?></span></h3>
        <h3>Вы набрали: <span class="changeable-text"><?= $this->arguments['resultMarks'] ?> баллов</span></h3>
        <h3>Что вы выполнили:</h3>
        <?php foreach ($this->arguments['resultTask'] as $value) : ?>
            <?php if($value["done"] == "true") :?>
                <p><?= $value["title"]?> : <?= $value["marks"]?></p>
            <?php endif ?>
        <?php endforeach?>
        <div>
            <a class="btn btn-for-a btn-start" href="/">Главная</a>
        </div>
    </div>
</section>