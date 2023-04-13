let inputPercentageCompletion = document.querySelector(".input-percentage-completion");
let app = document.querySelector('#app');

let timerMinuteContent = document.querySelector('#timer-minute');
let timerSecondContent = document.querySelector('#timer-second');

const numberTask = document.querySelector('.task-number');
const btnResultTasks = document.querySelector('#btn-result');


/*TIMER*/


//
// if (24 - timerMinuteContent.value < "10") {
//     timerMinuteContent.value = "0" + $timeMinuteContent;
// } else {
//     timerMinuteContent.value = $timeMinuteContent;
// }

console
let timeMinuteStart = 25 - timerMinuteContent.value;
let timerSecondStart = 60 - timerSecondContent.value;

window.onload = function () {
    var minute = timeMinuteStart;
    var sec = timerSecondStart;
    setInterval(function () {
        document.getElementById("systemTime").value =
            minute + " : " + sec;
        sec--;
        if (sec === "00") {
            minute--;
            sec = 60;
            if (minute === 0) {
                minute = 5;
            }
        }
    }, 1000);
};

function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    let interval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        if (minutes === "05" && seconds === "00") {
            setTimeout(() => {
                clearInterval(interval);
            }, 0);

            creatModalWindowEndOlimpiada();
        }
        display.value = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}

window.onload = function () {

    let allTimeMinutes = timeMinuteStart * timerSecondStart;
    let display = document.querySelector('#systemTime');
    console.log(timeMinuteStart);
    startTimer(allTimeMinutes, display);
};


async function fetchAndViewUpdateTimer() {
    let response = await fetch('/update_time', {
        method: 'POST',
    });

    return await response.text();
}

setInterval(function () {
    fetchAndViewUpdateTimer().then(json => {
        $arr = String(json).split(':');

        if ($arr[0] >= 25) {
            creatModalWindowEndOlimpiada();
        }

        $minute = 24 - $arr[0];
        $second  = 59 - $arr[1];

        if ($minute < 10) {
            timerMinuteContent.value = "0" + $minute;
        } else {
            timerMinuteContent.value = $minute;
        }

        if ($second < 10) {
            timerSecondContent.value = "0" + $second;
        } else {
            timerSecondContent.value = $second;
        }

        console.log(json);
    });
}, 30000);

/*MODAL WINDOW*/

function creatModalWindowEndOlimpiada() {
    let div = document.createElement('div');
    let p = document.createElement('p');
    let a = document.createElement('a');

    div.className = "container-modal-window";
    p.innerHTML = "Время выполнения олимпиады закончилось";
    a.className = "btn btn-for-a";
    a.innerHTML = "Просмотреть результаты";

    div.appendChild(p);
    div.appendChild(a);

    if (numberTask.innerHTML === '1') {
        a.href = '/store?id=1';
    }

    btnResultTasks.href = '#';

    app.appendChild(div);
}

/*PERCENTAGE COMPLETION*/

setInterval(function () {
    fetchAndViewUpdatePercentage().then(json => {
        inputPercentageCompletion.value = json + "%";
    });
}, 20000);

async function fetchAndViewUpdatePercentage() {
    let response = await fetch('/update_percentage_completion', {
        method: 'POST',
    });

    return await response.json();
}