<section>
    <?= $this->arguments['task'] ?>
    <div class="execution-result">
        <input name="task-number" id="task-number" value="<?= $this->arguments['taskNumber'] ?>" hidden="hidden">
        <?= $this->arguments['timer'] ?>
        <?= $this->arguments['percentageCompletion'] ?>
        <a href="/task?id=2" id="btn-task" class="btn btn-for-a btn-task">Следующее задание</a>
    </div>
    <a href="/results" id="btnResult" class="btn btn-for-a btn-start">Закончить олимпиаду</a>
</section>
