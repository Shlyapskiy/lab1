<?php

$objects = [
    ["name" => "Об'єкт 1", "country" => "Україна", "age" => 25, "salary" => 1500],
    ["name" => "Об'єкт 2", "country" => "Польща", "age" => 30, "salary" => 2000],
    ["name" => "Об'єкт 3", "country" => "США", "age" => 35, "salary" => 2500],
];

function вивести_таблицю($масив) {
    echo "<table border='1'>";
    echo "<tr><th>Ім'я</th><th>Країна</th><th>Вік</th><th>Зарплата</th></tr>";

    foreach ($масив as $обєкт) {
        echo "<tr>";
        echo "<td>{$обєкт['name']}</td>";
        echo "<td>{$обєкт['country']}</td>";
        echo "<td>{$обєкт['age']}</td>";
        echo "<td>{$обєкт['salary']}</td>";
        echo "</tr>";
    }

    echo "</table>";
}

function вибрати_за_запитом($масив, $запит) {
    $результат = [];

    foreach ($масив as $обєкт) {
        $додавати = true;

        foreach ($запит as $ключ => $значення) {
            if ($обєкт[$ключ] !== $значення) {
                $додавати = false;
                break;
            }
        }

        if ($додавати) {
            $результат[] = $обєкт;
        }
    }

    return $результат;
}
function зберегти_у_файл($масив, $імя_файлу) {
    $серіалізований_масив = serialize($масив);
    file_put_contents($імя_файлу, $серіалізований_масив);
}

function завантажити_з_файлу($імя_файлу) {
    if (file_exists($імя_файлу)) {
        $серіалізований_масив = file_get_contents($імя_файлу);
        return unserialize($серіалізований_масив);
    } else {
        return [];
    }
}

$objects = завантажити_з_файлу('objects_data.txt');

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "GET") {
    $запит = [
        "country" => isset($_GET["country"]) ? $_GET["country"] : null,
        "min_age" => isset($_GET["min_age"]) ? $_GET["min_age"] : null,
    ];

    echo "<h2>Результати запиту:</h2>";
    вивести_таблицю(вибрати_за_запитом($objects, $запит));
}

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    $новий_обєкт = [
        "name" => $_POST["name"],
        "country" => $_POST["country"],
        "age" => $_POST["age"],
        "salary" => $_POST["salary"]
    ];


    $objects[] = $новий_обєкт;

    header("Location: index.php");
    exit();
}

?>

<h2>Додати новий об'єкт</h2>
<form method="post">
    <label>Ім'я: <input type="text" name="name" required></label><br>
    <label>Країна: <input type="text" name="country" required></label><br>
    <label>Вік: <input type="number" name="age" required></label><br>
    <label>Зарплата: <input type="number" name="salary" required></label><br>
    <input type="submit" value="Додати">
</form>

<h2>Всі об'єкти:</h2>
<?php вивести_таблицю($objects); ?>
