let timerMinuteContent = document.querySelector('.timer-minute');
let timerSecondContent = document.querySelector('.timer-second');

const btnNextTask = document.querySelector('#btn-task');
const numberTask = document.querySelector('.task-number');
const btnResultTasks = document.querySelector('#btn-result');

let inputPercentageCompletion = document.querySelector(".input-percentage-completion");
let app = document.querySelector('#app');

timerMinuteContent.value = 49 - timerMinuteContent.value;
timerSecondContent.value = 59 - timerSecondContent.value;

if (numberTask.innerHTML === '1') {
    btnResultTasks.href = "/store?id=1&option=result";
}

if (numberTask.innerHTML === '2') {
    btnNextTask.style.visibility = 'hidden';
    btnResultTasks.href = "/store?id=2";
}

let timerIntervalSecond = setInterval(function () {
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


let timerIntervalMinute = setInterval(function () {
    let timerMinuteValue = timerMinuteContent.value;

    if (timerMinuteValue === "00") {
        creatModalWindowEndOlimpiad();
    }

    timerMinuteValue--;

    return timerMinuteContent.value = timerMinuteValue;
}, 60000);

// if (timerMinuteContent.value === "48") {
//     fetchAndViewTheEndOlimpiade().then(r => app.innerHTML = r);
// }

/*PERCENTAGE COMPLETION*/

setInterval(function() {
    fetchAndViewUpdatePercentage().then(json => {
        inputPercentageCompletion.value = json + "%";
    });
}, 10000);

// function insertPercentageCompletionIntoHtml(json) {
//     inputPercentageCompletion.value = json;
// }

async function fetchAndViewUpdatePercentage() {
    let dataNowPercentage = inputPercentageCompletion.value.slice(0, -1);

    let response = await fetch('/update_percentage_completion', {
        method: 'POST',
    });

    return await response.json();
}

function creatModalWindowEndOlimpiad() {
    setTimeout(() => { clearInterval(timerIntervalMinute); }, 0);
    setTimeout(() => { clearInterval(timerIntervalSecond); }, 0);

    let div = document.createElement('div');
    let p = document.createElement('p');
    let a = document.createElement('a');

    div.className = "container-modal-window";
    p.innerHTML = "Время выполнения олимпиады закончилось";
    a.className = "btn btn-for-a";
    a.innerHTML = "Просмотреть результаты";

    div.appendChild(p);
    div.appendChild(a);

    if(numberTask.innerHTML === '1') {
        a.href = '/store?id=1&option=result';
    }
    if(numberTask.innerHTML === '2') {
        a.href = '/store?id=2&option=result';
    }
    btnNextTask.href = '#';
    btnResultTasks.href = '#';

    app.appendChild(div);
}