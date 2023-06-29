/*SEARCH*/

document.querySelector("#inputSearch").addEventListener('keyup', search);

function search() {
    let input = document.querySelector("#inputSearch");
    let filter = input.value.toUpperCase();
    let div = document.querySelector("#content-table");

    let chosenVariant = document.querySelector("#searchVariant").value;

    let article = div.getElementsByTagName("article");

    for (let i = 0; i < article.length; i++) {
        let span = article[i].getElementsByTagName("span")[0];
        if (span.innerHTML.toUpperCase().indexOf(filter) >= 0 && (chosenVariant === '0' || chosenVariant === article[i].getElementsByTagName("span")[1].innerHTML)) {
            article[i].style.display = "";
        } else {
            article[i].style.display = "none";
        }
    }
}

document.querySelector("#searchVariant").addEventListener('change', function (e) {
    let div = document.querySelector("#content-table");
    let article = div.getElementsByTagName("article");
    let input = document.querySelector("#inputSearch");
    input.value = "";

    for (let i = 0; i < article.length; i++) {
        let span = article[i].getElementsByTagName("span")[1];

        if (e.target.value == span.innerHTML) {
            article[i].style.display = "";
        } else {
            if (e.target.value == 0) {
                article[i].style.display = "";
            } else {
                article[i].style.display = "none";
            }
        }
    }
})

document.querySelector("#sortBy").addEventListener('change', function (e) {
    console.log("Changed to: " + e.target.value);
    let div = document.querySelector("#content-table");
    let article = div.getElementsByTagName("article");

    for (let i = 0; i < article.length; i++) {
        let span = article[i].getElementsByTagName("span")[2];

        if (e.target.value == span.innerHTML && article[i].style.display == "") {
            article[i].style.display = "";
        } else {
            if (e.target.value == 0) {
                article[i].style.display = "";
            } else {
                article[i].style.display = "none";
            }
        }
    }
})

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
    let emailInput = document.querySelector("#email");

    if (lastNameInput.value === "") {
        lastNameInput.placeholder = "Введите свою фамилию!";
        lastNameInput.style.borderColor = "red";
    } else {
        lastNameInput.style.borderColor = "green";
    }

    if (firstNameInput.value === "") {
        firstNameInput.placeholder = "Введите своё имя!";
        firstNameInput.style.borderColor = "red";
    } else {
        firstNameInput.style.borderColor = "green";
    }

    if (middleNameInput.value === "") {
        middleNameInput.placeholder = "Введите своё отчетсво!";
        middleNameInput.style.borderColor = "red";
    } else {
        middleNameInput.style.borderColor = "green";
    }

    if (emailInput.value === "") {
        emailInput.placeholder = "Введите Email!";
        emailInput.style.borderColor = "red";
    } else if (validEmail(emailInput.value.trim()) === false) {
        emailInput.style.borderColor = "red";
    } else {
        emailInput.style.borderColor = "green";
    }

    if (lastNameInput.value !== "" && firstNameInput.value !== "" && middleNameInput.value !== "") {
        fetchAndViewAuthorization().then(r => "");
    }
}

function validEmail(email) {
    let regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    return regex.test(email);
}
