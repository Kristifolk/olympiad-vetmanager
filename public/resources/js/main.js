const timerContent = document.querySelector('.timer-content');
const timerStartTime = document.querySelector('.start-time');

let timerMinuteContent = document.querySelector('.timer-minute');
let timerSecondContent = document.querySelector('.timer-second');

setInterval(function () {
    let timerSecondContent = document.querySelector('.timer-second');
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
    let timerMinuteContent = document.querySelector('.timer-minute');
    let timerMinuteValue = timerMinuteContent.value;

    if (timerMinuteValue === "00") {
        alert("Время вышло");
    }

    timerMinuteValue--;
    return timerMinuteContent.value = timerMinuteValue;
}, 60000);

const btnNextTask = document.querySelector('#btn-task');
const numberTask = document.querySelector('.task-number');
//const btnResult = document.querySelector('#btnResult');

// if (numberTask.innerHTML === '1') {
//     btnResult.href = "/task?id=2";
// }
if (numberTask.innerHTML === '2') {
    btnNextTask.style.visibility = 'hidden';
    //btnResult.href = "/results";
}

