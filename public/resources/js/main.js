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

function search() {
    let input = document.querySelector("#inputSearch");
    let filter = input.value.toUpperCase();
    let div = document.querySelector("#content-table");
    let article = div.getElementsByTagName("article");

    for (let i = 0; i < article.length; i++) {
        let span = article[i].getElementsByTagName("span")[0];
        if (span.innerHTML.toUpperCase().indexOf(filter) > -1) {
            article[i].style.display = "";
        } else {
            article[i].style.display = "none";
        }
    }
}

/*AUTHORIZATION*/

async function fetchAndViewAuthorization() {
    const form = new FormData(document.querySelector("form.form-authorization"));
    let app = document.querySelector('#app');

    let response = await fetch('/authorization_participant', {
        method: 'POST',
        body: form
    });
    window.location = "http://0.0.0.0:8080/tasks_preparation";
}

function validateInputUser() {
    let lastNameInput = document.querySelector("#last-name");
    let firstNameInput = document.querySelector("#first-name");
    let middleNameInput = document.querySelector("#middle-name");

    if(lastNameInput.value === "") {
        lastNameInput.placeholder = "Введиете свою фамилию!";
        lastNameInput.style.borderColor = "red";
    }

    if(firstNameInput.value === "") {
        firstNameInput.placeholder = "Введиете своё имя!";
        firstNameInput.style.borderColor = "red";
    }

    if(middleNameInput.value === "") {
        middleNameInput.placeholder = "Введиете своё отчетсво!";
        middleNameInput.style.borderColor = "red";
    }

    if(lastNameInput.value !== "" && firstNameInput.value !== "" && middleNameInput.value !== "") {
        fetchAndViewAuthorization().then(r => "");
    }
}