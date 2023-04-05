<section class="container">
    <div id="content-task">
        <?= $this->arguments['task'] ?>
    </div>
    <div method="post" class="execution-result form-current-data">
        <input name="task-number" id="task-number" value="<?= $this->arguments['taskNumber'] ?>" hidden="hidden">
        <?= $this->arguments['timer'] ?>
        <?= $this->arguments['percentageCompletion'] ?>
    </div>
    <div class="btn-content">
        <a href="/store?id=1&option=result" id="btn-result" class="btn btn-for-a btn-start">Закончить олимпиаду</a>
    </div>
</section>