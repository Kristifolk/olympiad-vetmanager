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
    window.location = "/tasks_preparation";
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