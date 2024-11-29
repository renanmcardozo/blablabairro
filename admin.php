<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
require('conexao.php');

// Buscar as últimas 3 informações
$sql_informacoes = "SELECT id_info, titulo, descricao FROM informacoes ORDER BY id_info DESC LIMIT 3";
$stmt_informacoes = $pdo->prepare($sql_informacoes);
$stmt_informacoes->execute();
$informacoes = $stmt_informacoes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/index_adm.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <span>Bem-vindo, <strong><?php echo $_SESSION['nome']; ?></strong>!</span>
        </div>
        <div class="navbar-center">
            <span class="project-name">Blá-blá-Bairro</span>
        </div>
        <div class="navbar-right">
            <a href="logout.php" class="btn-navbar">Sair</a>
        </div>
    </div>

    <div class="geral">
        <div class="lista-esquerda">
            <h3>Informações e Eventos</h3>
            <ul>
                <?php foreach ($informacoes as $info): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($info['titulo']); ?></strong><br>
                        <?php echo htmlspecialchars($info['descricao']); ?><br>
                        <a href="editar_excluir_info.php?id=<?php echo $info['id_info']; ?>" class="btn-editar-excluir">Editar / Excluir</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="container">
            <h2>Painel de Controle</h2>
            <p>Você está logado como <strong><?php echo $_SESSION['tipo']; ?></strong>.</p>
            <div class="buttons">
                <a href="informacoes.php" class="btn">Registrar Informações</a>
                <a href="gerenciar_usuarios.php" class="btn">Gerenciar Usuários</a>
                <a href="validar_usuarios.php" class="btn">Validar Usuários</a>
                <a href="denuncias.php" class="btn">Denúncias</a>
            </div>
        </div>
    </div>
</body>
</html>
