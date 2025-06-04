#!/usr/bin/env php

<?php 
function getMaxLength($array) {
    $maxLength = 0;
    foreach ($array as $item) {
        $name = $item[0];
        $length = preg_match_all('/./u', $name, $matches);
        if ($length > $maxLength) {
            $maxLength = $length;
        }
    }
    return $maxLength;
}

function showMenu() {
    echo "################################\n";
    echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
    echo "################################\n";
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
    echo "Введіть команду: ";
}

function showList(&$basket){
    $handle = fopen("php://stdin", "r");
    $exitList = false;

    $products = [
        '1' => ['Молоко пастеризоване', 12],
        '2' => ['Хліб білий', 9],
        '3' => ['Сир білий', 21],
        '4' => ['Сметана 20%', 25],
        '5' => ['Вода газована', 18],
        '6' => ['Кофе', 21],
        '7' => ['Рожевий торт', 35],
        '8' => ['Коренья на блюді', 12],
        '9' => ['Диня', 20],
    ];

    while (!$exitList) {
        $maxLength = getMaxLength($products);

        echo "№  НАЗВА" . str_repeat(' ', $maxLength - 3) . "ЦІНА\n";
        foreach ($products as $key => [$name, $price]) {
            $nameLength = preg_match_all('/./u', $name, $matches);
            $paddedName = $name . str_repeat(' ', $maxLength - $nameLength);
            printf("%-2d %s  %-3d\n", $key, $paddedName, $price);
        }

        echo "---------------------------\n";
        echo "0  Повернутися\n";
        echo "Виберіть товар: ";

        $item = trim(fgets($handle));

        if ($item == "0") {
            $exitList = true;
        } elseif (isset($products[$item])) {
            [$name, $price] = $products[$item];
            echo "Вибрано: $name\n";
            echo "Введіть кількість, штук: ";
            $count = (int)trim(fgets($handle));

            if ($count > 0 && $count < 100) {
                $basket[] = [$name, $count, $price];

            } elseif ($count === 0) {
                $found = false;
                foreach ($basket as $key => [$fname]) {
                    if ($fname === $name) {
                        unset($basket[$key]);
                        $found = true;
                        echo "$name видалено з кошика.\n";
                    }
                }

                if (!$found) {
                    echo "ПОМИЛКА! ТОВАРУ НЕМАЄ У ВАШОМУ КОШИКУ\n";
                }
            }

            showBasket($basket);
            fgets($handle);
        } else {
            echo "ПОМИЛКА! Невірний номер товару\n";
        }
    }
}

function showBasket($basket) {
    $maxLength = getMaxLength($basket);

    echo "НАЗВА" . str_repeat(' ', $maxLength - 3) . "КІЛЬКІСТЬ\n";
    foreach ($basket as $key => [$name, $count]) {
        $nameLength = preg_match_all('/./u', $name, $matches);
        $paddedName = $name . str_repeat(' ', $maxLength - $nameLength);
        printf("%s  %-2d\n", $paddedName, $count);
    }
}

function showTotal($basket) {
    $maxLength = getMaxLength($basket);
    $handle = fopen("php://stdin", "r");
    $sum = 0;

    if ($maxLength === 0) {
        echo "КОШИК ПОРОЖНІЙ\n";
        fgets($handle);
    }
    else 
    {
        echo "№  НАЗВА" . str_repeat(' ', $maxLength - 3) . "ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n";
        foreach ($basket as $key => [$name, $count, $price]) {
            $sum += $price * $count;
            $nameLength = preg_match_all('/./u', $name, $matches);
            $paddedName = $name . str_repeat(' ', $maxLength - $nameLength);
            printf("%-2d %s  %-5d %-10d %-8d\n", $key, $paddedName, $price, $count, $price * $count);
        }
        echo "РАЗОМ ДО СПЛАТИ: " . $sum;

        fgets($handle);
    }
}

function setProfile(&$name, &$age) {
    $handle = fopen("php://stdin","r");

    do {
        echo "Ваше ім'я: ";
        $newName = trim(fgets($handle));
        $hasLetter = preg_match('/\p{L}/u', $newName);

        if (!$hasLetter) {
            echo "ПОМИЛКА! Імʼя користувача не може бути порожнім і повинно містити хоча б одну літеру.\n";
        }
    } while (!$hasLetter);
        
    do {
        echo "Ваш вік: ";
        $newAge = (int)trim(fgets($handle));
        if ($newAge < 7 || $newAge > 150) {
            echo "ПОМИЛКА! Користувач не може бути молодшим 7-ми або старшим 150-ти років.\n";
        }
    } while ($newAge < 7 || $newAge > 150);

    $name = $newName;
    $age = $newAge;
}

function showProfile(&$name, &$age) {
    $handle = fopen("php://stdin", "r");

    if ($name === "" || $age === 0) {
        setProfile($name, $age);
    }
    else {
        echo "\nПРОФІЛЬ\n";
        echo "Ваше ім'я: " . $name . "\n";
        echo "Ваш вік: " . $age . "\n";

        $exitProfile = false;

        while (!$exitProfile) {
            echo "1 Змінити дані\n";
            echo "0 Повернутися\n";
            echo "Введіть команду: ";

            $choice = trim(fgets($handle));
            switch($choice) {
                case "1": 
                    setProfile($name, $age);
                    break;
                case "0":
                    $exitProfile = true;
                    break;
                default:
                    echo "ПОМИЛКА! Введіть правильну команду\n";
            }
        }
    } 
}

$exit = false;
$basket = [];
$name = ""; $age = 0;
$handle = fopen("php://stdin", "r");

while (!$exit) {
    showMenu();
    $choice = trim(fgets($handle));

    switch ($choice) {
        case "1":
            showList($basket);
            break;
        case "2":
            showTotal($basket);
            break;
        case "3":
            showProfile($name, $age);
            break;
        case "0":
            $exit = true;
            break;
        default:
            echo "ПОМИЛКА! Введіть правильну команду\n";
        }
}
?>