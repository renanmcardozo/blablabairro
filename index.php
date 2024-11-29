<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
require('conexao.php');

// Buscar as últimas 3 informações
$sql_informacoes = "SELECT titulo, descricao FROM informacoes ORDER BY id_info DESC LIMIT 3";
$stmt_informacoes = $pdo->prepare($sql_informacoes);
$stmt_informacoes->execute();
$informacoes = $stmt_informacoes->fetchAll(PDO::FETCH_ASSOC);

// Buscar os serviços registrados pelo usuário logado
$sql_servicos = "SELECT id_servicos, tipo_servico, endereco_servico FROM servicos WHERE id_usuario = :id_usuario";
$stmt_servicos = $pdo->prepare($sql_servicos);
$stmt_servicos->execute(['id_usuario' => $_SESSION['id_usuario']]);
$servicos = $stmt_servicos->fetchAll(PDO::FETCH_ASSOC);
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
        <a href="editar_perfil.php" class="btn-navbar">Editar Perfil</a>
        <a href="logout.php" class="btn-navbar">Sair</a>
    </div>
</div>

<div class="geral">
    <!-- Lista de Informações (lado esquerdo) -->
    <div class="lista-esquerda">
        <h3>Informações e Eventos</h3>
        <ul>
            <?php foreach ($informacoes as $info): ?>
                <li>
                    <span><strong><?php echo htmlspecialchars($info['titulo']); ?></strong></span><br>
                    <span><?php echo htmlspecialchars($info['descricao']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Painel de Controle Central -->
    <div class="container">
        <h2>Painel de Controle</h2>
        <p>Você está logado como <strong><?php echo $_SESSION['tipo']; ?></strong>.</p>
        <div class="buttons">
            <a href="registro_denuncia.php" class="btn">Registrar Denúncia</a>
            <a href="cadastro_servico.php" class="btn">Cadastrar Serviço</a>
        </div>
    </div>

    <!-- Lista de Serviços (lado direito) -->
    <div class="lista-direita">
        <h3>Serviços Registrados</h3>
        <ul>
            <?php foreach ($servicos as $servico): ?>
                <li>
                    <span><strong>Tipo:</strong> <?php echo htmlspecialchars($servico['tipo_servico']); ?></span><br>
                    <span><strong>Endereço:</strong> <?php echo htmlspecialchars($servico['endereco_servico']); ?></span><br>
                    <a href="editar_servico.php?id_servico=<?php echo $servico['id_servicos']; ?>" class="btn-editar">Editar Serviço</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>



    

</body>
</html>
