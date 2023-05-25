let inputPercentageCompletion = document.querySelector(".input-percentage-completion");
let app = document.querySelector('#app');

let timerMinuteContent = document.querySelector('#timer-minute');
let timerSecondContent = document.querySelector('#timer-second');

const numberTask = document.querySelector('.task-number');

/*TIMER*/

window.onload = function () {
    let minutesLeft = 24 - timerMinuteContent.value;
    let secondsLeft = 59 - timerSecondContent.value;
    let interval = setInterval(function () {
        document.getElementById("systemTime").value =
            minutesLeft + " : " + secondsLeft;
        secondsLeft--;
        if (secondsLeft === 0) {
            minutesLeft--;
            secondsLeft = 59;
        }

        minutesLeft = minutesLeft === 9 ? "0" + minutesLeft : minutesLeft;
        minutesLeft = minutesLeft === 8 ? "0" + minutesLeft : minutesLeft;
        minutesLeft = minutesLeft === 7 ? "0" + minutesLeft : minutesLeft;
        minutesLeft = minutesLeft === 6 ? "0" + minutesLeft : minutesLeft;
        minutesLeft = minutesLeft === 5 ? "0" + minutesLeft : minutesLeft;
        minutesLeft = minutesLeft === 4 ? "0" + minutesLeft : minutesLeft;
        minutesLeft = minutesLeft === 3 ? "0" + minutesLeft : minutesLeft;
        minutesLeft = minutesLeft === 2 ? "0" + minutesLeft : minutesLeft;
        minutesLeft = minutesLeft === 1 ? "0" + minutesLeft : minutesLeft;
        minutesLeft = minutesLeft === 0 ? "0" + minutesLeft : minutesLeft;
        secondsLeft = secondsLeft < 10 ? "0" + secondsLeft : secondsLeft;

        if (minutesLeft === "00" && secondsLeft === "01") {
            setTimeout(() => {
                clearInterval(interval);
            }, 0);
            window.location = "/store_end_time";
        }

    }, 1000);
};

async function fetchAndViewUpdateTimer() {
    let response = await fetch('/update_time', {
        method: 'POST',
    });

    return await response.text();
}

setInterval(function () {
    fetchAndViewUpdateTimer().then(json => {
        let apiData = String(json).split(':');
        let minutesPastFromServer = apiData[0];
        let secondsPastFromServer = apiData[1];

        if (minutesPastFromServer >= 25) {
            window.location = "/end_time";
        }

        let minutesLeft = 25 - minutesPastFromServer;
        let secondsLeft = 60 - secondsPastFromServer;

        if (minutesLeft < 10) {
            timerMinuteContent.value = "0" + minutesLeft;
        } else {
            timerMinuteContent.value = minutesLeft;
        }

        if (secondsLeft < 10) {
            timerSecondContent.value = "0" + secondsLeft;
        } else {
            timerSecondContent.value = secondsLeft;
        }

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