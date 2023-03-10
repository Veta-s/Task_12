<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

//Функция для разбиения ФИО на части
function getPartsFromFullname ($fullName) {
    $person_name = ['surname', 'name', 'patronomyc'];
    return array_combine($person_name,explode(' ', $fullName));
}
echo 'Результат функции для разбиения ФИО на части:' . "<br>";
$arrParts = getPartsFromFullname($example_persons_array[6]['fullname']);
print_r($arrParts);
echo "<br>"."<br>";

//Функция для объединения ФИО из частей
function getFullnameFromParts ($surname, $name, $patronomyc) {
    return $surname .= ' ' . $name . ' ' . $patronomyc;
}
echo 'Результат функции для объединения ФИО из частей:' . "<br>";
$arrFullName = getFullnameFromParts($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc']);
print_r($arrFullName);
echo "<br>"."<br>";

//Функция сокращения ФИО
function getShortName ($fullName) {
    $shortFIO = getPartsFromFullname ($fullName);
    return $shortFIO['name']. ' ' .mb_substr($shortFIO['surname'], 0, 1). '.';
}
echo 'Результат функции для сокращения ФИО:' . "<br>";
$arrSocr = getShortName($example_persons_array[6]['fullname']);
print_r($arrSocr);
echo "<br>"."<br>";

//Функция определения пола по ФИО
function getGenderFromName ($fullName) {
    $shortFIO = getPartsFromFullname ($fullName);
    $gender = 0;
    //Признаки женского пола:
    if (mb_substr($shortFIO['surname'], -2, 2) == 'ва') {
        --$gender;
    }
    if (mb_substr($shortFIO['name'], -1, 1) == 'а') {
        --$gender;
    }
    if (mb_substr($shortFIO['patronomyc'], -3, 3) == 'вна') {
        --$gender;
    }
    //Признаки мужского пола:
    if (mb_substr($shortFIO['surname'], -1, 1) == 'в') {
        ++$gender;
    }
    if (mb_substr($shortFIO['name'], -1, 1) == 'й' || (mb_substr($shortFIO['name'], -1, 1) == 'н')) {
        ++$gender;
    }
    if (mb_substr($shortFIO['patronomyc'], -2, 2) == 'ич') {
        ++$gender;
    } 
    switch($gender <=> 0){
        case 1:
            return 'Мужчина';
            break;
        case -1:
            return 'Женщина';
            break;
        default:
            return'Не удалось определить';
    }   
}
for ($i=0;$i<count($example_persons_array);$i++){
    // Определяется пол всех ФИО в массиве
$arrGender[$example_persons_array[$i]['fullname']] = getGenderFromName($example_persons_array[$i]['fullname']);
}
echo 'Результат функции определения пола по ФИО' . "<br>";
print_r($arrGender);
echo "<br>"."<br>";

//Функция для определения гендерного состава
function getGenderDescription($array){
    
    $males = array_filter($array, function($person) {
        return getGenderFromName($person['fullname']) == 'Мужчина';
    });

    $females = array_filter($array, function($person) {
        return getGenderFromName($person['fullname']) == 'Женщина';
    });

    $und = array_filter($array, function($person) {
        return getGenderFromName($person['fullname']) == 'Не удалось определить';
    });

    $malePercent = round(count($males)*100/count($array), 1);
    $femalePercent = round(count($females)*100/count($array), 1);
    $unknonwPercent = round(count($und)*100/count($array), 1);
    
    echo <<<HEREDOCLETTER
    Гендерный состав аудитории:<br>
    ---------------------------<br>
    Мужчины - $malePercent%<br>
    Женщины - $femalePercent%<br>
    Не удалось определить - $unknonwPercent%<br>
    HEREDOCLETTER;
    echo "<br>"."<br>";
}
getGenderDescription($example_persons_array);

//Функция идеального подбора пары
function getPerfectPartner($surname, $name, $patronomyc, $partners_array) {
    $surname1 = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $name1 = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $patronomyc1 = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);
        
    //склеиваем ФИО
    $personFullName = getFullnameFromParts($surname1, $name1, $patronomyc1);
    //определяем пол для ФИО   
    $genderPerson = getGenderFromName($personFullName);
    // Проверка что пол распознан
    while ($genderPerson === 0){        
        return 1;
    }
    // Выбор случайного партнера из массива
    $randomPartner = $partners_array[random_int(0, count($partners_array)-1)]['fullname'];
    // Проверка пола случайно выбранного партнера  
    $genderPartner = getGenderFromName($randomPartner);
    // Проверка на совпадение полов партнеров и распознание пола случайно выбранного партнера
    while ($genderPerson === $genderPartner || $genderPartner === 0 || $personFullName === $randomPartner)
    {
        $randomPartner = $partners_array[random_int(0, count($partners_array)-1)]['fullname'];
        $genderPartner = getGenderFromName($randomPartner);
    }
    $shortPersonName = getShortName($personFullName);
    $shortPartnerName = getShortName($randomPartner);
    $percentCompatibility = mt_rand(50, 100) + mt_rand(0, 100)/100;

    echo 'Результат функции идеального подбора пары' . "<br>";
    echo $shortPersonName . " + " . $shortPartnerName . " = " . "<br>";
    echo "♡". " Идеально на ". $percentCompatibility. "% " ."♡";
    return 0;
}
$arrParts = getPartsFromFullname($example_persons_array[random_int(0, count($example_persons_array)-1)]['fullname']);
$choosePartner = getPerfectPartner($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc'], $example_persons_array);