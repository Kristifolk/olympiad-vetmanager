let timerMinuteContent = document.querySelector('.timer-minute');
let timerSecondContent = document.querySelector('.timer-second');

const btnNextTask = document.querySelector('#btn-task');
const numberTask = document.querySelector('.task-number');
const btnResultTasks = document.querySelector('#btn-result');

timerMinuteContent.value = 49 - timerMinuteContent.value;
timerSecondContent.value = 59 - timerSecondContent.value;

if (numberTask.innerHTML === '1') {
    btnResultTasks.href = "/store?id=1&option=result";
}

if (numberTask.innerHTML === '2') {
    btnNextTask.style.visibility = 'hidden';
    btnResultTasks.href = "/store?id=2";
}

setInterval(function () {
    let timerSecondValue = timerSecondContent.value;

    if (timerSecondValue === "00") {
        timerSecondValue = "60";
    }

    timerSecondValue--;

    if (timerSecondValue < "10") {
        timerSecondValue = "0" + timerSecondValue;
    }

    return timerSecondContent.value = timerSecondValue;
}, 1000);


setInterval(function () {
    let timerMinuteValue = timerMinuteContent.value;

    if (timerMinuteValue === "00") {
        alert("Время вышло");
    }

    timerMinuteValue--;
    return timerMinuteContent.value = timerMinuteValue;
}, 60000);


