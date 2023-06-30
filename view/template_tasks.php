<?php

use App\Services\View;

/** @var View $this */
?>

<section class="container">
    <div id="content-task">
        <?= $this->arguments['task'] ?>
    </div>
    <div class="execution-result form-current-data">
        <input name="task-number" id="task-number" value="<?= $this->arguments['taskNumber'] ?>" hidden="hidden">
        <?= $this->arguments['timer'] ?>
        <?= $this->arguments['percentageCompletion'] ?>
    </div>
    <div class="btn-content">
        <a href="/store" id="btn-result" class="btn btn-for-a btn-start">Закончить олимпиаду</a>
    </div>
</section>
<script src="/resources/js/task.js"></script>