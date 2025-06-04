<?php
$dbFile = 'products_shop.sqlite';

try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        price REAL NOT NULL
    );");

    $count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
        $products = [
            ['Батон "Нарізний"', 22.50],
            ['Молоко 2.5% 1л', 34.90],
            ['Яйця курячі 10шт', 49.00],
            ['Сир твердий 200г', 85.75],
            ['Ковбаса "Лікарська" 500г', 119.90],
            ['Гречка 1кг', 43.20],
            ['Олія соняшникова 1л', 56.00],
            ['Цукор 1кг', 39.50],
            ['Картопля молода 1кг', 27.80],
            ['Яблука червоні 1кг', 31.40],
        ];
        foreach ($products as $product) {
            $stmt->execute($product);
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>