<form id="form-authorize" class="form-authorization container-modal-window authorization-window">
    <h3>Введите свои данные перед прохождением олимпиады</h3>
    <label class="label-form" for="last-name">Фамилия</label>
    <input name="last-name" id="last-name" class="input-form" required>
    <label class="label-form" for="first-name">Имя</label>
    <input name="first-name" id="first-name" class="input-form" required>
    <label class="label-form" for="middle-name">Отчество</label>
    <input name="middle-name" id="middle-name" class="input-form" required>
    <label class="label-form" for="email">Email</label>
    <input type="email" name="email" id="email" class="input-form" required>
    <button type="button" onclick="validateInputUserAndFetchIfSucceeds()" class="btn btn-start">Сохранить</button>
    <a type="button" class="btn btn-start" href="/">Назад</a>
</form>

<script>
    /*AUTHORIZATION*/
    function validateInputUserAndFetchIfSucceeds() {

        let lastNameInput = document.querySelector("#last-name");
        let firstNameInput = document.querySelector("#first-name");
        let middleNameInput = document.querySelector("#middle-name");
        let emailInput = document.querySelector("#email");

        if (lastNameInput.value === "") {
            lastNameInput.placeholder = "Введите свою фамилию";
            lastNameInput.style.borderColor = "red";
        } else {
            lastNameInput.style.borderColor = "green";
        }

        if (firstNameInput.value === "") {
            firstNameInput.placeholder = "Введите своё имя";
            firstNameInput.style.borderColor = "red";
        } else {
            firstNameInput.style.borderColor = "green";
        }

        if (middleNameInput.value === "") {
            middleNameInput.placeholder = "Введите своё отчество";
            middleNameInput.style.borderColor = "red";
        } else {
            middleNameInput.style.borderColor = "green";
        }

        if (emailInput.value === "") {
            emailInput.placeholder = "Введите Email";
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

    async function fetchAndViewAuthorization() {
        const form = new FormData(document.querySelector("form.form-authorization"));

        let response = await fetch('/authorization_participant', {
            method: 'POST',
            body: form
        });

        //window.location = "/tasks_preparation"; //(failed)net::ERR_HTTP_RESPONSE_CODE_FAILURE
        let responseJson = await response.json();
        //alert(responseJson);

        if (responseJson.success === false) {
            alert(responseJson.message);
        } else {
            window.location = "/tasks_preparation";
        }
    }

</script>
