<?php
// api/conexao.php
$host = 'localhost';
$dbname = 'wikiescolar';
$user = 'root'; // Geralmente 'root' no XAMPP
$pass = '';     // Geralmente vazio no XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode(["sucesso" => false, "erro" => "Erro de Conexão: " . $e->getMessage()]));
}
?>