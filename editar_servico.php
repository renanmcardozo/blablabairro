<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o id_servico foi passado pela URL
if (!isset($_GET['id_servico'])) {
    header("Location: index.php");
    exit();
}

$id_servico = $_GET['id_servico'];

// Consulta o serviço no banco de dados
require('conexao.php');
$sql_servico = "SELECT * FROM servicos WHERE id_servicos = :id_servico AND id_usuario = :id_usuario";
$stmt_servico = $pdo->prepare($sql_servico);
$stmt_servico->execute(['id_servico' => $id_servico, 'id_usuario' => $_SESSION['id_usuario']]);
$servico = $stmt_servico->fetch(PDO::FETCH_ASSOC);

// Verifica se o serviço existe
if (!$servico) {
    header("Location: index.php");
    exit();
}

// Processa o envio do formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coleta os dados do formulário
    $tipo_servico = $_POST['tipo_servico'];
    $endereco_servico = $_POST['endereco_servico'];
    $data_servico = $_POST['data_servico'];

    // Atualiza o serviço no banco de dados
    $sql_update = "UPDATE servicos SET tipo_servico = :tipo_servico, endereco_servico = :endereco_servico, data_servico = :data_servico WHERE id_servicos = :id_servico";
    $stmt_update = $pdo->prepare($sql_update);
    if ($stmt_update->execute([
        'tipo_servico' => $tipo_servico,
        'endereco_servico' => $endereco_servico,
        'data_servico' => $data_servico,
        'id_servico' => $id_servico
    ])) {
        $_SESSION['msg'] = 'Serviço atualizado com sucesso!';
    } else {
        $_SESSION['msg'] = 'Erro ao atualizar serviço. Tente novamente.';
    }

    // Redireciona após a atualização com a mensagem
    header("Location: editar_servico.php?id_servico=" . $id_servico);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço</title>
    <link rel="stylesheet" href="css/editar_servico.css">
    <script>
        // Exibir o popup de sucesso/erro se houver mensagem na sessão
        window.onload = function() {
            <?php if (isset($_SESSION['msg'])): ?>
                alert('<?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>');
            <?php endif; ?>
        };
    </script>
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

    <!-- Formulário de Edição de Serviço -->
    <div class="container">
        <h2>Editar Serviço</h2>
        <form method="POST">
            <label for="tipo_servico">Tipo de Serviço:</label>
            <input type="text" name="tipo_servico" value="<?php echo htmlspecialchars($servico['tipo_servico']); ?>" required>

            <label for="endereco_servico">Endereço do Serviço:</label>
            <input type="text" name="endereco_servico" value="<?php echo htmlspecialchars($servico['endereco_servico']); ?>" required>

            <label for="data_servico">Data do Serviço:</label>
            <input type="datetime-local" name="data_servico" value="<?php echo date('Y-m-d\TH:i', strtotime($servico['data_servico'])); ?>" required>

            <button type="submit">Atualizar Serviço</button>
        </form>
        <div class="back-link">
            <a href="index.php">Voltar ao Painel</a>
        </div>
    </div>
</body>
</html>
