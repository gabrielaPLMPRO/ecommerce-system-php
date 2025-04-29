<?php
$host = 'localhost';
$dbname = 'virtual_store';
$user = 'postgres';
$password = '123';

function getConnection() {
    global $host, $dbname, $user, $password;

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Erro de conexão: ' . $e->getMessage();
        exit;
    }
}
?>
