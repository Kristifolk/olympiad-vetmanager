<?php

namespace App\Services\Task;


use App\Services\Data\DataForJonFile;
use JsonException;

class TaskCollection
{
    private string $clientFullName;

    public function defaultSessionData(): void
    {
        $_SESSION["ResultPercentage"] = '0%';
        $_SESSION["TimeEndTask"] = ["minutes" => "00", "seconds" => "00"];
        $_SESSION['Diagnose'] = "Абсцесс";
        $_SESSION['AnimalGender'] = "";
    }

    public function generateAnimalAge(): void
    {
        $animalAgeArray = $this->dataAnimalAge();
        $age = $animalAgeArray[rand(0, count($animalAgeArray) - 1)];
        $_SESSION['DateOfBirth'] = $age['totalYears'];
        $_SESSION['TotalYearsEnglish'] = $age['totalYearsEnglish'];
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

        $_SESSION['NameClient'] = $nameClientArray[rand(0, count($nameClientArray) - 1)];
        $_SESSION['SurnameClient'] = $surnameClientArray[rand(0, count($surnameClientArray) - 1)];
        $_SESSION['PatronymicClient'] = $patronymicClientArray[rand(0, count($patronymicClientArray) - 1)];

        $_SESSION['FullNameClient'] =$_SESSION['SurnameClient'] . " " . $_SESSION['NameClient'] . " "  . $_SESSION['PatronymicClient'];
        $this->clientFullName = $_SESSION['FullNameClient'];
    }

    public function generateLastAndFirstNameClient(): void
    {
        $fullName = explode(" ", $this->clientFullName);
        $_SESSION['LastAndFirstNameClient'] = $fullName[1] . " " . $fullName[2];
    }

    /**
     * @throws JsonException
     */
    public function generateBreedPet(): void
    {
        $arrayBreeds = (new DataForJonFile())->getDataFromJsonFile(PET_BREEDS_PATH);
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
            ['alias' => 'Айрис', 'gender' => 'female'],
            ['alias' => 'Аврора', 'gender' => 'female'],
            ['alias' => 'Галилея', 'gender' => 'female'],
            ['alias' => 'Шанси', 'gender' => 'female'],
            ['alias' => 'Рута', 'gender' => 'female'],
            ['alias' => 'Фабби', 'gender' => 'female'],
            ['alias' => 'Вента', 'gender' => 'female'],
            ['alias' => 'Ронда', 'gender' => 'female'],
            ['alias' => 'Окки', 'gender' => 'female'],
            ['alias' => 'Шкода', 'gender' => 'female'],
            ['alias' => 'Лисичка', 'gender' => 'female'],
            ['alias' => 'Соня', 'gender' => 'female'],
            ['alias' => 'Вира', 'gender' => 'female'],
            ['alias' => 'Абель', 'gender' => 'female'],
            ['alias' => 'Юша', 'gender' => 'female'],
            ['alias' => 'Гретхен', 'gender' => 'female'],
            ['alias' => 'Шелби', 'gender' => 'female'],
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
            ['alias' => 'Рони', 'gender' => 'female'],
            ['alias' => 'Джеси', 'gender' => 'female'],
            ['alias' => 'Йошка', 'gender' => 'female'],
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
                'totalYearsEnglish' => '2 month'
            ],
            [
                'totalYears' => '6 мес',
                'totalYearsEnglish' => '6 month'
            ],
            [
                'totalYears' => '8 мес',
                'totalYearsEnglish' => '8 month'
            ],
            [
                'totalYears' => '1 год',
                'totalYearsEnglish' => '1 year'
            ],
            [
                'totalYears' => '18 мес',
                'totalYearsEnglish' => '6 month 1 year'
            ],
            [
                'totalYears' => '2 года',
                'totalYearsEnglish' => '2 year'
            ],
            [
                'totalYears' => '3 года',
                'totalYearsEnglish' => '3 year'
            ],
            [
                'totalYears' => '4 года',
                'totalYearsEnglish' => '4 year'
            ],
            [
                'totalYears' => '5 лет',
                'totalYearsEnglish' => '5 year'
            ],
            [
                'totalYears' => '6 лет',
                'totalYearsEnglish' => '6 year'
            ]
        ];
    }
}