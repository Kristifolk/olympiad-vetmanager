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
            if (minutesLeft === 0) {
                minutesLeft = 5;
            }
        }
        minutesLeft = minutesLeft < 10 ? "0" + minutesLeft : minutesLeft;
        secondsLeft = secondsLeft < 10 ? "0" + secondsLeft : secondsLeft;

        if (minutesLeft === "00" && secondsLeft === "00") {
            setTimeout(() => {
                clearInterval(interval);
            }, 0);
            creatModalWindowEndOlimpiada();
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
            creatModalWindowEndOlimpiada();
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

        console.log('Time from server: ' + apiData);
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


/*SEARCH*/
document.addEventListener('keyup', search);

// function search() {
//     let input = document.getElementById("inputSearch");
//     let filter = input.value.toUpperCase();
//     let ul = document.getElementById("content-table");
//     let li = ul.getElementsByTagName("li");
//
//
//     // Перебирайте все элементы списка и скрывайте те, которые не соответствуют поисковому запросу
//     for (let i = 0; i < li.length; i++) {
//         let span = li[i].getElementsByTagName("span")[0];
//         let table = li[i].getElementsByTagName("table")[0];
//         console.log(span);
//         if (span[i].textContent.toUpperCase().indexOf(filter) > -1) {
//             span[i].style.display = "";
//             table[i].style.display = "";
//         } else {
//             span[i].style.display = "none";
//             table[i].style.display = "none";
//         }
//     }
// }

function search() {
    let input = document.getElementById("inputSearch");
    let filter = input.value.toUpperCase();
    let ul = document.getElementById("content-table");
    let li = ul.getElementsByTagName("li");

    // Перебирайте все элементы списка и скрывайте те, которые не соответствуют поисковому запросу
    for (let i = 0; i < li.length; i++) {
        let span = li[i].getElementsByTagName("span")[0];
        if (span.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}