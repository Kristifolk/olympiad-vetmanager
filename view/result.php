<section class="container">
    <h2>Ваш результат, <?= $_SESSION['TestLogin'] ?></h2>
    <div>
        <h3>Общее время прохождения олимпиады: <span
            class="changeable-text"><?= $this->arguments['taskTransitTime']['minute'] ?>:<?= $this->arguments['taskTransitTime']['second'] ?></span>
        </h3>
        <h3>Общий процент выполнения всех заданий: <span
            class="changeable-text"><?= $this->arguments['taskTransitTime']['resultPercentage'] ?></span>
        </h3>
        <div>
            <a class="btn btn-for-a btn-start" href="/">Главная</a>
        </div>
    </div>
</section>