<?php

use App\Services\View;

/** @var View $this */
?>

<div class="timer-container">
    <div class="timer-content">
        <input id="systemTime" class="input-timer" value="" readonly>
        <input hidden="hidden" name="timer-minute" id="timer-minute" class="timer-minute input-timer"
               value="<?= $this->arguments['minutes'] ?>" readonly>
        <input hidden="hidden" name="timer-second" id="timer-second" class="timer-second input-timer"
               value="<?= $this->arguments['seconds'] ?>" readonly>
    </div>
</div>

