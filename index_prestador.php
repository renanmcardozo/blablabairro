<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
require('conexao.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
    <div class="navbar-left">
        <span>Bem-vindo, <strong><?php echo $_SESSION['nome']; ?></strong>!</span>
    </div>
    <div class="navbar-center">
        <span class="project-name">Blá-blá-Bairro</span>
    </div>
    <div class="navbar-right">
        <a href="editar_perfil_prestador.php" class="btn-navbar">Editar Perfil</a>
        <a href="logout.php" class="btn-navbar">Sair</a>
    </div>
</div>

<div class="geral">

    <!-- Painel de Controle Central -->
    <div class="container">
        <h2>Painel de Controle</h2>
        <p>Você está logado como <strong><?php echo $_SESSION['tipo']; ?></strong>.</p>
        <div class="buttons">
            <a href="servicos_disponiveis.php" class="btn">Serviços Disponiveis</a>
        </div>
    </div>

</div>



    

</body>
</html>
