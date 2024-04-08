<?php

namespace App\Services\Task;


use App\Services\Data\DataForJonFile;
use JsonException;

class TaskCollection
{
    public function __construct(
        public array $template,
    )
    {
    }

    /**
     * @throws JsonException
     */
    public function generateData(): array
    {
        $this->generateAnimalAge();
        $this->generateAnimalColor();
        $this->generateAnimalName();
        $this->generateBreedPet();
        $this->generateFullNameClient();
        $this->generateDiagnose();
        return $this->template;
    }

    private function generateDiagnose(): void
    {
        $this->template["animal_diagnosis:meaning"] = "Кошачье акне";
    }

    private function generateAnimalAge(): void
    {
        $animalAgeArray = $this->dataAnimalAge();
        $age = $animalAgeArray[rand(0, count($animalAgeArray) - 1)];
        $this->template["dateOfBirth:meaning"] = $age['totalYears'];
        $_SESSION['TotalYearsEnglish'] = $age['totalYearsEnglish'];
    }

    private function generateAnimalColor(): void
    {
        $animalColorArray = $this->dataAnimalColor();
        $color = $animalColorArray[rand(0, count($animalColorArray) - 1)];
        $this->template["color:meaning"] = $color['nominativeBase'];
        $_SESSION['AnimalColorGenitiveBase'] = $color['genitiveBase'];
    }

    private function generateAnimalName(): void
    {
        $animalNameArray = $this->dataAnimalName();
        $pet = $animalNameArray[rand(0, count($animalNameArray) - 1)];
        $this->template["alias:meaning"] = $pet["alias"];
        $this->template["gender:meaning"] = $pet["gender"];
    }

    private function generateFullNameClient(): void
    {
        $nameClientArray = $this->dataNameClient();
        $surnameClientArray = $this->dataSurnameClient();
        $patronymicClientArray = $this->dataPatronymicClient();

        $nameClient = $nameClientArray[rand(0, count($nameClientArray) - 1)];
        $surnameClient = $surnameClientArray[rand(0, count($surnameClientArray) - 1)];
        $patronymicClient = $patronymicClientArray[rand(0, count($patronymicClientArray) - 1)];

        $this->template["add_client:meaning"] = $surnameClient . " " . $nameClient . " " . $patronymicClient;
        $this->generateLastAndFirstNameClient();
    }

    private function generateLastAndFirstNameClient(): void
    {
        $fullName = explode(" ", $this->clientFullName());
        $_SESSION['LastAndFirstNameClient'] = $fullName[1] . " " . $fullName[2];
    }

    private function clientFullName(): string
    {
        return $this->template["add_client:meaning"];
    }

    /**
     * @throws JsonException
     */
    private function generateBreedPet(): void
    {
        $arrayBreeds = (new DataForJonFile())->getDataFromJsonFile(PET_BREEDS_PATH);
        $breed = $arrayBreeds["breed"][rand(0, count($arrayBreeds["breed"]) - 1)];
        $this->template["breed:meaning"] = $breed['title'];
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
            'Абрамов',
            'Дмитриев',
            'Теребов',
            'Зимин',
            'Жуков',
            'Бобылёв',
            'Беспалов',
            'Зимин',
            'Данилов',
            'Панов',
            'Агафонов',
            'Большаков',
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
            ['alias' => 'Aдaм', 'gender' => 'male'],
            ['alias' => 'Aдoльф', 'gender' => 'male'],
            ['alias' => 'Бaрс', 'gender' => 'male'],
            ['alias' => 'Бaрри', 'gender' => 'male'],
            ['alias' => 'Брут', 'gender' => 'male'],
            ['alias' => 'Вoльт', 'gender' => 'male'],
            ['alias' => 'Гaрфилд', 'gender' => 'male'],
            ['alias' => 'Гaрри', 'gender' => 'male'],
            ['alias' => 'Гeрaльд', 'gender' => 'male'],
            ['alias' => 'Джo', 'gender' => 'male'],
            ['alias' => 'Жeрaр', 'gender' => 'male'],
            ['alias' => 'Жoрa', 'gender' => 'male'],
            ['alias' => 'Зeвс', 'gender' => 'male'],
            ['alias' => 'Зeфир', 'gender' => 'male'],
            ['alias' => 'Изюм', 'gender' => 'male'],
            ['alias' => 'Ирис', 'gender' => 'male'],
            ['alias' => 'Кaй', 'gender' => 'male'],
            ['alias' => 'Каспер', 'gender' => 'male'],
            ['alias' => 'Клaус', 'gender' => 'male'],
            ['alias' => 'Лукaс', 'gender' => 'male'],
            ['alias' => 'Люк', 'gender' => 'male'],
            ['alias' => 'Лeo', 'gender' => 'male'],
            ['alias' => 'Луис', 'gender' => 'male'],
            ['alias' => 'Мaрс', 'gender' => 'male'],
            ['alias' => 'Мэтт', 'gender' => 'male'],
            ['alias' => 'Нильс', 'gender' => 'male'],
            ['alias' => 'Нуaр', 'gender' => 'male'],
            ['alias' => 'Оджи', 'gender' => 'male'],
            ['alias' => 'Олaф', 'gender' => 'male'],
            ['alias' => 'Оливeр', 'gender' => 'male'],
            ['alias' => 'Орбит', 'gender' => 'male'],
            ['alias' => 'Оскaр', 'gender' => 'male'],
            ['alias' => 'Остин', 'gender' => 'male'],
            ['alias' => 'Пaтрик', 'gender' => 'male'],
            ['alias' => 'Пeпeр', 'gender' => 'male'],
            ['alias' => 'Пoтaп', 'gender' => 'male'],
            ['alias' => 'Рaлли', 'gender' => 'male'],
            ['alias' => 'Рaфик', 'gender' => 'male'],
            ['alias' => 'Сириус', 'gender' => 'male'],
            ['alias' => 'Цeзaрь', 'gender' => 'male']
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
            ['nominativeBase' => 'рыжий', 'genitiveBase' => 'рыжего'],
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