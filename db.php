<?php
$dsn = 'mysql:host=localhost;dbname=car_rental_system';
$username = 'root'; // Change this to your MySQL username
$password = ''; // Change this to your MySQL password

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
