<section>
    <div>
        <h2>Задание 1 / 2</h2>
        <div>
            <h4>Сведения об организации</h4>
            <p>Ветеринарная клиника «Котопес». Находится в городе Краснодар по адресу ул. Калинина 157. Занимается лечением животных с применением современных медикаментозных препаратов и использованием современного оборудования для диагностики и лечения. Клиника работает в CRM системе Ветменеджер.
                В клинике «Котопес» недавно была запущена рекламная кампания. Чтобы отследить ее эффективность, клиника использует купон «Я профессионал», который даёт скидку 10% на все товары и услуги.
            </p>
            <h4>Ситуация</h4>
            <p>В клинику «Котопес» пришел на первичный прием
                <span class="changeable-text"><?= $this->arguments['fullNameClient']?></span>
                        с купоном «Я профессионал»  и своей любимой собакой по кличке
                <span class="changeable-text"><?= $this->arguments['animalName']?></span>
                        , породы корело-финская лайка,
                <span class="changeable-text"><?= $this->arguments['animalColor']?></span>
                        цвета, в возрасте
                <span class="changeable-text"><?= $this->arguments['animalAge']?></span>.
                        Обращение в клинику было с жалобами на то, что у собаки повышена температура и она почти ничего не ест. Врач клиники провел осмотр и  обнаружил, что повышение температуры и отсутствие аппетита было связано с гнойным воспалительным процессом - флегмона.
                <span class="changeable-text"><?= $this->arguments['animalName']?></span>
                        сделали 3 укола: жаропонижающий и два с антибиотиком. Стоимость первичного приема 750 руб.
                <span class="changeable-text"><?= $this->arguments['lastAndFirstNameClient']?></span>
                        заплатил за уколы 1100 руб. Укол жаропонижающий стоимостью 300 руб. и 2 укола с антибиотиком каждый по 400 руб.
                        Клиенту назначили повторный визит через неделю.
            </p>
            <h4>Ваша цель</h4>
            <p>Вы врач в клинике “Котопес”, проведите эту ситуацию в программе “Ветменеджер” по адресу: адрес
                <a href="https://deviproff.vetmanager2.ru/login.php" target="_blank">deviproff.vet-manager.ru</a>
                    , используя логин:
                <span class="changeable-text">admin</span>
                    и пароль
                <span class="changeable-text">mrIA62dj</span>
            </p>
        <p>Время на выполнение задания <span class="start-time changeable-text">25</span> минут.</p>
        </div>

        <a class="btn btn-for-a btn-start" href="/second_task">Следующее задание</a>
        <a class="btn btn-for-a btn-start" href="/result">Закончить олимпиаду</a>
    </div>
    <div>
        <div class="timer"></div>
        <div class="percentage-of-completion"></div>
    </div>
</section>
