<?php

namespace App\Services\Task;


use App\Services\Data;

class TaskCollection
{
    private string $clientFullName;

    public function defaultSessionData(): void
    {
        $_SESSION["ResultPercentage"] = '0%';
        $_SESSION["TimeEndTask"] = ["minutes" => "00", "seconds" => "00"];
        $_SESSION['Diagnose'] = "Абсцесс";
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
        $pet = $animalNameArray[rand(0, count($animalNameArray) - 1)];
        $_SESSION['AnimalName'] = $pet["alias"];
        $_SESSION['AnimalGender'] = $pet["gender"];
    }

    public function generateFullNameClient(): void
    {
        $nameClientArray = $this->dataNameClient();
        $surnameClientArray = $this->dataSurnameClient();
        $patronymicClientArray = $this->dataPatronymicClient();

        $nameClient = $nameClientArray[rand(0, count($nameClientArray) - 1)];
        $surnameClient = $surnameClientArray[rand(0, count($surnameClientArray) - 1)];
        $patronymicClient = $patronymicClientArray[rand(0, count($patronymicClientArray) - 1)];

        $_SESSION['FullNameClient'] = $nameClient . " " . $surnameClient . " " . $patronymicClient;
        $this->clientFullName = $_SESSION['FullNameClient'];
    }

    public function generateLastAndFirstNameClient(): void
    {
        $fullName = explode(" ", $this->clientFullName);
        $_SESSION['LastAndFirstNameClient'] = $fullName[1] . " " . $fullName[2];
    }

    /**
     * @throws \JsonException
     */
    public function generateBreedPet(): void
    {
        $arrayBreeds = (new Data)->getDataFromJsonFile(PET_BREEDS_PATH);
        $_SESSION['Breed'] = $arrayBreeds["breed"][rand(0, count($arrayBreeds["breed"]) - 1)];

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

    private function dataAnimalName(): array
    {
        return [
            ['alias' => 'Фира', 'gender' => 'female'],
            ['alias' => 'Лейла', 'gender' => 'female'],
            ['alias' => 'Сьюзен', 'gender' => 'female'],
            ['alias' => 'Айрис', 'gender' => 'male'],
            ['alias' => 'Аврора', 'gender' => 'female'],
            ['alias' => 'Галилея', 'gender' => 'female'],
            ['alias' => 'Шанси', 'gender' => 'male'],
            ['alias' => 'Рута', 'gender' => 'female'],
            ['alias' => 'Фабби', 'gender' => 'female'],
            ['alias' => 'Вента', 'gender' => 'female'],
            ['alias' => 'Ронда', 'gender' => 'female'],
            ['alias' => 'Окки', 'gender' => 'male'],
            ['alias' => 'Шкода', 'gender' => 'female'],
            ['alias' => 'Лисичка', 'gender' => 'female'],
            ['alias' => 'Соня', 'gender' => 'female'],
            ['alias' => 'Вира', 'gender' => 'female'],
            ['alias' => 'Абель', 'gender' => 'female'],
            ['alias' => 'Юша', 'gender' => 'male'],
            ['alias' => 'Гретхен', 'gender' => 'male'],
            ['alias' => 'Шелби', 'gender' => 'male'],
            ['alias' => 'Сайга', 'gender' => 'female'],
            ['alias' => 'Валгала', 'gender' => 'female'],
            ['alias' => 'Диана', 'gender' => 'female'],
            ['alias' => 'Ямайка', 'gender' => 'female'],
            ['alias' => 'Ямми', 'gender' => 'female'],
            ['alias' => 'Булка', 'gender' => 'female'],
            ['alias' => 'Виоль', 'gender' => 'female'],
            ['alias' => 'Рошель', 'gender' => 'female'],
            ['alias' => 'Ильда', 'gender' => 'female'],
            ['alias' => 'Анжи', 'gender' => 'female'],
            ['alias' => 'Омега', 'gender' => 'female'],
            ['alias' => 'Зайка', 'gender' => 'female'],
            ['alias' => 'Гаара', 'gender' => 'female'],
            ['alias' => 'Веселина', 'gender' => 'female'],
            ['alias' => 'Сильва', 'gender' => 'female'],
            ['alias' => 'Верна', 'gender' => 'female'],
            ['alias' => 'Рони', 'gender' => 'male'],
            ['alias' => 'Джеси', 'gender' => 'female'],
            ['alias' => 'Йошка', 'gender' => 'male'],
            ['alias' => 'Микаелла', 'gender' => 'female']
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
}