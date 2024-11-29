<?php
$host = 'localhost';
$dbname = 'Blablabairro';
$username = 'root'; // ou outro nome de usuário
$password = ''; // ou a senha do seu banco de dados

try {
    // Criando a conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurando o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Caso haja erro, exibe a mensagem de erro
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit();
}
?>
