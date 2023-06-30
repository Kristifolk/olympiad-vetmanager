<?php

use App\Services\View;

/** @var View $this */
?>

<div class="percentage-completion-container">
    <p>Ваш % выполнения олимпиады</p>
    <input class="input-timer input-percentage-completion percentage-completion"
           value="<?= $this->arguments['percentageCompletion'] ?>" readonly>
</div>