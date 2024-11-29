<?php
session_start();

// Verificar se o usuário está logado e se é do tipo "prestador"
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Prestador de Serviços') {
    header("Location: login.php");
    exit();
}

require('conexao.php');

// Obter a cidade do prestador de serviços
$id_usuario = $_SESSION['id_usuario'];
$sql_cidade = "SELECT cidade FROM usuario WHERE id_usuario = :id_usuario";
$stmt_cidade = $pdo->prepare($sql_cidade);
$stmt_cidade->execute(['id_usuario' => $id_usuario]);
$usuario = $stmt_cidade->fetch(PDO::FETCH_ASSOC);

// Verificar se o usuário tem uma cidade registrada
if (!$usuario) {
    die('Cidade do prestador não encontrada.');
}

$cidade_prestador = $usuario['cidade'];

// Buscar serviços disponíveis para o prestador, filtrando pela cidade e com status diferente de "Concluída"
$sql_servicos = "SELECT * FROM servicos 
                 WHERE endereco_servico LIKE :cidade 
                 AND status != 'Concluída'
                 ORDER BY data_servico DESC";
$stmt_servicos = $pdo->prepare($sql_servicos);
$stmt_servicos->execute([
    'cidade' => "%" . $cidade_prestador . "%"  // Filtra pela cidade do prestador
]);
$servicos = $stmt_servicos->fetchAll(PDO::FETCH_ASSOC);

// Alterar o status do serviço
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status']) && isset($_POST['id_servico'])) {
    $id_servico = $_POST['id_servico'];
    $status = $_POST['status'];

    // Atualizar o status do serviço
    $sql_update = "UPDATE servicos SET status = :status WHERE id_servicos = :id_servicos";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        'status' => $status,
        'id_servicos' => $id_servico
    ]);

    header("Location: servicos_disponiveis.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços Disponíveis</title>
    <link rel="stylesheet" href="css/servicos_disponiveis.css">
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

    <div class="container">
        <h2>Serviços Disponíveis</h2>

        <?php if (count($servicos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tipo de Serviço</th>
                        <th>Quantidade</th>
                        <th>Endereço</th>
                        <th>Data do Serviço</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos as $servico): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($servico['tipo_servico']); ?></td>
                            <td><?php echo htmlspecialchars($servico['quantidade']); ?></td>
                            <td><?php echo htmlspecialchars($servico['endereco_servico']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($servico['data_servico'])); ?></td>
                            <td>
                                <form method="POST">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="Aberta" <?php if ($servico['status'] == 'Aberta') echo 'selected'; ?>>Aberta</option>
                                        <option value="Em Andamento" <?php if ($servico['status'] == 'Em Andamento') echo 'selected'; ?>>Em Andamento</option>
                                        <option value="Concluída" <?php if ($servico['status'] == 'Concluída') echo 'selected'; ?>>Concluída</option>
                                    </select>
                                    <input type="hidden" name="id_servico" value="<?php echo $servico['id_servicos']; ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Não há serviços disponíveis para você no momento.</p>
        <?php endif; ?>
    </div>
    <div class="back-link">
    <a href="index_prestador.php">Voltar ao Painel</a>
</div>
</body>
</html>
