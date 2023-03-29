<div class="timer-container">
    <div class="timer-content">
        <input name="timer-minute" id="timer-minute" class="timer-minute input-timer" value="<?= $_SESSION["EndTimeMinute"] = $this->arguments['startTimeMinute'] ?>>" readonly>
        :
        <input name="timer-second" id="timer-second" class="timer-second input-timer" value="<?= $_SESSION["EndTimeSecond"] = $this->arguments['startTimeSecond'] ?>" readonly>
    </div>
</div>
