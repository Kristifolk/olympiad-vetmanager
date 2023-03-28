<?php

namespace App\Controllers;

use App\Services\Response;
use App\Services\View;
use App\Services\ViewPath;

class TasksController
{
    public string $clientFullName;

    public function viewTaskType(ViewPath $path): void
    {
        $html = new View(
            $path,
            [
                'fullNameClient' => $this->generateFullNameClient(),
                'lastAndFirstNameClient' => $this->generateLastAndFirstNameClient(),
                'animalName' => $this->generateAnimalName(),
                'animalColor' => $this->generateAnimalColor(),
                'animalAge' => $this->generateAnimalAge()
            ]
        );
        $timerHtml = new View(ViewPath::TimerContent);
        $templateWithContent = new View(ViewPath::TemplateContent, ['content' => $html, 'timer' => $timerHtml]);
        (new Response($templateWithContent))->echo();
    }

    public function generateAnimalAge(): string
    {
        $animalAgeArray = $this->dataAnimalAge();
        return $animalAgeArray[rand(0, count($animalAgeArray) - 1)];
    }

    public function dataAnimalAge(): array
    {
        return [
            '2 мес',
            '6 мес',
            '8 мес',
            '12 мес',
            '18 мес',
            '2 лет',
            '3 лет',
            '4 лет',
            '5 лет',
            '6 лет'
        ];
    }


    public function generateAnimalColor(): string
    {
        $animalColorArray = $this->dataAnimalColor();
        return $animalColorArray[rand(0, count($animalColorArray) - 1)];
    }

    public function dataAnimalColor(): array
    {
        return [
            'белого',
            'синего',
            'жёлтого'
        ];
    }


    public function generateAnimalName(): string
    {
        $animalNameArray = $this->dataAnimalName();
        return $animalNameArray[rand(0, count($animalNameArray) - 1)];
    }

    public function dataAnimalName(): array
    {
        return [
            'Фира',
            'Лейла',
            'Сьюзен',
            'Айрис',
            'Аврора',
            'Галилея',
            'Шанси',
            'Рута',
            'Фабби',
            'Вента',
            'Ронда',
            'Окки',
            'Шкода',
            'Лисичка',
            'Соня',
            'Вира',
            'Абель',
            'Юша',
            'Гретхен',
            'Шелби',
            'Сайга',
            'Валгала',
            'Диана',
            'Ямайка',
            'Ямми',
            'Булка',
            'Виоль',
            'Рошель',
            'Ильда',
            'Анжи',
            'Омега',
            'Зайка',
            'Гаара',
            'Веселина',
            'Сильва',
            'Верна',
            'Рони',
            'Джеси',
            'Йошка',
            'Микаелла'
        ];
    }


    public function generateFullNameClient(): string
    {
        $fullNameArray = $this->dataFullNameClient();
        $this->clientFullName = $fullNameArray[rand(0, count($fullNameArray) - 1)];
        return $this->clientFullName;
    }

    public function generateLastAndFirstNameClient(): string
    {
        $fullName = explode(" ", $this->clientFullName);
        return $fullName[1] . " " . $fullName[2];
    }

    public function dataFullNameClient(): array
    {
        return [
            'Филатов Харитон Тимофеевич',
            'Мишин Альфред Робертович',
            'Лапин Ибрагил Егорович',
            'Макаров Афанасий Владиславович',
            'Шубин Вячеслав Андреевич',
            'Овчинников Клим Макарович',
            'Веселов Соломон Филатович',
            'Цветков Эрнест Робертович',
            'Русаков Богдан Пётрович',
            'Шилов Иосиф Максович',
            'Антонов Вальтер Максович',
            'Абрамов Аскольд Геннадьевич',
            'Дмитриев Дмитрий Игнатьевич',
            'Терентьев Виталий Яковович',
            'Зимин Ярослав Аристархович',
            'Жуков Адриан Мартынович',
            'Бобылёв Макар Павлович',
            'Беспалов Константин Никитевич',
            'Александров Осип Артёмович',
            'Зимин Сергей Михайлович',
            'Данилов Степан Тимурович',
            'Панов Адам Олегович',
            'Агафонов Демьян Вячеславович',
            'Большаков Давид Владленович',
            'Галкин Юлиан Арсеньевич',
            'Галкин Фрол Федорович',
            'Мамонтов Альфред Германнович',
            'Титов Бронислав Куприянович',
            'Харитонов Венедикт Станиславович',
            'Шарапов Аполлон Витальевич',
            'Уваров Макар Яковович',
            'Жуков Геннадий Максович',
            'Емельянов Лавр Иринеевич',
            'Ефремов Юлиан Парфеньевич',
            'Калинин Авраам Наумович',
            'Анисимов Лев Артемович',
            'Брагин Мстислав Вадимович',
            'Воробьёв Авраам Дамирович',
            'Суворов Любовь Аристархович',
            'Дроздов Адриан Митрофанович'
        ];
    }
}