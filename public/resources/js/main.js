const timerContent = document.querySelector('.timer-content');
const timerStartTime = document.querySelector('.start-time');

let timerMinuteContent = document.querySelector('.timer-minute');
let timerSecondContent = document.querySelector('.timer-second');

setInterval(function() {
    let timerSecondContent = document.querySelector('.timer-second');
    let timerSecondValue = timerSecondContent.innerHTML;

    if (timerSecondValue === "00") {
        timerSecondValue = "60";
    }

    timerSecondValue--;

    if (timerSecondValue < "10") {
        timerSecondValue = "0" + timerSecondValue;
    }

    return timerSecondContent.innerHTML = timerSecondValue;
}, 1000);


setInterval(function() {
    let timerMinuteContent = document.querySelector('.timer-minute');
    let timerMinuteValue = timerMinuteContent.innerHTML;

    if (timerMinuteValue === "00") {
        alert("Время вышло");
    }

    timerMinuteValue--;
    return timerMinuteContent.innerHTML = timerMinuteValue;
}, 60000);