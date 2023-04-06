<?php

namespace App\Controllers;

class TaskCollection
{
    private string $clientFullName;
    public function defaultSessionData(): void
    {
        $_SESSION["ResultPercentage"] = '0%';
        $_SESSION["TimeEndTask"] = ["minutes" => "00", "seconds" => "00"];
        $_SESSION['Diagnose'] = "Абсцесс";
    }
    public function generateUserLogin(): void
    {
        $userLoginArray = $this->dataUserLogin();
        $_SESSION["TestLogin"] = $userLoginArray[rand(0, count($userLoginArray) - 1)]['login'];
        $_SESSION["TestPassword"] = $userLoginArray[rand(0, count($userLoginArray) - 1)]['password'];
    }


    public function generateAnimalAge(): void
    {
        $animalAgeArray = $this->dataAnimalAge();
        $age = $animalAgeArray[rand(0, count($animalAgeArray) - 1)];
        $_SESSION['DateOfBirth'] = $age['dateOfBirth'];
        $_SESSION['AnimalAge'] = $age['totalYears'];
    }

    public function generateAnimalColor(): void
    {
        $animalColorArray = $this->dataAnimalColor();
        $color = $animalColorArray[rand(0, count($animalColorArray) - 1)];
        $_SESSION['AnimalColor'] = $color['nominativeBase'];
        $_SESSION['AnimalColorGenitiveBase'] = $color['genitiveBase'];
    }

    public function generateAnimalName(): void
    {
        $animalNameArray = $this->dataAnimalName();
        $_SESSION['AnimalName'] = "Дуся";//$animalNameArray[rand(0, count($animalNameArray) - 1)];
    }

    public function generateFullNameClient(): void
    {
//        $nameClientArray = $this->dataNameClient();
//        $surnameClientArray = $this->dataSurnameClient();
//        $patronymicClientArray = $this->dataPatronymicClient();
//
//        $nameClient = $nameClientArray[rand(0, count($nameClientArray) - 1)];
//        $surnameClient = $surnameClientArray[rand(0, count($surnameClientArray) - 1)];
//        $patronymicClient = $patronymicClientArray[rand(0, count($patronymicClientArray) - 1)];

        $nameClient = 'Соломон';
        $surnameClient = 'Шубин';
        $patronymicClient = 'Михайлович';

        $_SESSION['NameClient'] = 'Соломон';
        $_SESSION['SurnameClient'] = 'Шубин';
        $_SESSION['PatronymicClient'] = 'Михайлович';

        $_SESSION['NameClient'] = $nameClient;
        $_SESSION['SurnameClient'] = $surnameClient;
        $_SESSION['PatronymicClient'] = $patronymicClient;

        $_SESSION['FullNameClient'] = $nameClient . " " . $surnameClient . " " . $patronymicClient;
        $this->clientFullName = $_SESSION['FullNameClient'];
    }

    public function generateLastAndFirstNameClient(): void
    {
        $fullName = explode(" ", $this->clientFullName);
        $_SESSION['LastAndFirstNameClient'] = $fullName[1] . " " . $fullName[2];
    }


    private function dataNameClient(): array
    {
        return [
            'Харитон',
            'Альфред',
            'Ибрагил',
            'Афанасий',
            'Вячеслав',
            'Клим',
            'Соломон',
            'Эрнест',
            'Богдан',
            'Иосиф',
            'Вальтер',
            'Аскольд',
            'Дмитрий',
            'Виталий',
            'Ярослав',
            'Адриан',
            'Макар',
            'Константин',
            'Осип',
            'Сергей',
            'Степан',
            'Адам',
            'Демьян',
            'Давид',
            'Юлиан',
            'Фрол',
            'Альфред',
            'Бронислав',
            'Венедикт',
            'Аполлон',
            'Макар',
            'Геннадий',
            'Лавр',
            'Юлиан',
            'Авраам',
            'Лев',
            'Мстислав',
            'Авраам',
            'Любовь',
            'Адриан'
        ];
    }

    private function dataSurnameClient(): array
    {
        return [
            'Филатов',
            'Мишин',
            'Лапин',
            'Макаров',
            'Шубин',
            'Овчинников',
            'Веселов',
            'Цветков',
            'Русаков',
            'Шилов',
            'Антонов',
            'Абрамов ',
            'Дмитриев ',
            'Терентьевталий ',
            'Зимин',
            'Жуков',
            'Бобылёв',
            'Беспалов',
            'Александр',
            'Зимин',
            'Данилов',
            'Панов',
            'Агафонов',
            'Большаковвид',
            'Галкин',
            'Галкин',
            'Мамонтов',
            'Титов',
            'Харитонов',
            'Шарапов',
            'Уваров',
            'Жуков',
            'Емельянов',
            'Ефремов',
            'Калинин',
            'Анисимов',
            'Брагин',
            'Воробьёв',
            'Суворов',
            'Дроздов'
        ];
    }

    private function dataPatronymicClient(): array
    {
        return [
            'Тимофеевич',
            'Робертович',
            'Егорович',
            'Владиславович',
            'Андреевич',
            'Макарович',
            'Филатович',
            'Робертович',
            'Пётрович',
            'Максович',
            'Максович',
            'Геннадьевич',
            'Игнатьевич',
            'Яковович',
            'Аристархович',
            'Мартынович',
            'Павлович',
            'Никитевич',
            'Артёмович',
            'Михайлович',
            'Тимурович',
            'Олегович',
            'Вячеславович',
            'Владленович',
            'Арсеньевич',
            'Федорович',
            'Германнович',
            'Куприянович',
            'Станиславович',
            'Витальевич',
            'Яковович',
            'Максович',
            'Иринеевич',
            'Парфеньевич',
            'Наумович',
            'Артемович',
            'Вадимович',
            'Дамирович',
            'Аристархович',
            'Митрофанович'
        ];
    }

    private function dataFullNameClient(): array
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

    private function dataAnimalName(): array
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

    private function dataAnimalColor(): array
    {
        return [
            ['nominativeBase' => 'белый', 'genitiveBase' => 'белого'],
            ['nominativeBase' => 'голубой', 'genitiveBase' => 'голубого'],
            ['nominativeBase' => 'голубой черепаховый', 'genitiveBase' => 'голубого черепахового'],
            ['nominativeBase' => 'колор-поинт', 'genitiveBase' => 'колор-поинт'],
            ['nominativeBase' => 'коричневый', 'genitiveBase' => 'коричневого'],
            ['nominativeBase' => 'лиловый', 'genitiveBase' => 'лилового'],
            ['nominativeBase' => 'палевый', 'genitiveBase' => 'палевого'],
            ['nominativeBase' => 'персиковый', 'genitiveBase' => 'персикового'],
            ['nominativeBase' => 'разнообразный', 'genitiveBase' => 'разнообразного'],
            ['nominativeBase' => 'рыжий', 'genitiveBase' => 'рыжого'],
            ['nominativeBase' => 'серебристый', 'genitiveBase' => 'серебристого'],
            ['nominativeBase' => 'серый', 'genitiveBase' => 'серого'],
            ['nominativeBase' => 'тигровый', 'genitiveBase' => 'тигрового'],
            ['nominativeBase' => 'черепаховый', 'genitiveBase' => 'черепахового'],
            ['nominativeBase' => 'черноподпалый', 'genitiveBase' => 'черноподпалого'],
            ['nominativeBase' => 'черный', 'genitiveBase' => 'черного'],
        ];
    }

    private function dataAnimalAge(): array
    {
        return [
            [
                'totalYears' => '2 мес',
                'dateOfBirth' => ''
            ],
            [
                'totalYears' => '6 мес',
                'dateOfBirth' => ''
            ],
            [
                'totalYears' => '8 мес',
                'dateOfBirth' => ''
            ],
            [
                'totalYears' => '12 мес',
                'dateOfBirth' => ''
            ],
            [
                'totalYears' => '18 мес',
                'dateOfBirth' => ''
            ],
            [
                'totalYears' => '2 лет',
                'dateOfBirth' => ''],
            [
                'totalYears' => '3 лет',
                'dateOfBirth' => ''
            ],
            [
                'totalYears' => '4 лет',
                'dateOfBirth' => ''
            ],
            [
                'totalYears' => '5 лет',
                'dateOfBirth' => ''
            ],
            [
                'totalYears' => '6 лет',
                'dateOfBirth' => ''
            ]
        ];
    }
    private function dataUserLogin(): array
    {
        return [
            ['login' => 'admin1', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin2', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin3', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin4', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin5', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin6', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin7', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin8', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin9', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin10', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin11', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin12', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin13', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin14', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin15', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin16', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin17', 'password' => 'iJ1x9nfO'],
            ['login' => 'admin18', 'password' => 'iJ1x9nfO'],
        ];
    }
}