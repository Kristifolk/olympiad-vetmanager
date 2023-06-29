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
    <button type="button" onclick="validateInputUser()" class="btn btn-start">Сохранить</button>
    <a type="button" class="btn btn-start" href="/">Назад</a>
</form>
