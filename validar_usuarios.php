<?php
session_start();
require 'conexao.php'; // Arquivo de conexão com o banco de dados

// Verifica se o usuário é um administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'Administrador') {
    header("Location: login.php"); // Redireciona para a tela de login caso não seja admin
    exit();
}

// Pega a cidade do administrador logado
$adminCidade = $_SESSION['cidade'];

// Consulta para obter usuários inativos da mesma cidade do administrador
$stmt = $pdo->prepare("SELECT id_usuario, nome, email, cidade, status FROM usuario WHERE status = 'Inativo' AND cidade = :cidade");
$stmt->bindParam(':cidade', $adminCidade);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processa a alteração de status quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $status = $_POST['status'];

    // Atualiza o status do usuário para "Ativo"
    $stmt = $pdo->prepare("UPDATE usuario SET status = :status WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id_usuario', $id_usuario);

    if ($stmt->execute()) {
        $message = "Usuário ativado com sucesso!"; // Mensagem de sucesso
    } else {
        $message = "Erro ao atualizar o status.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de Usuários</title>
    <link rel="stylesheet" href="css/denuncias.css"> <!-- Estilos da tabela -->
    <script>
        function confirmarAtivacao() {
            return confirm('Tem certeza de que deseja ativar este usuário?');
        }
    </script>
</head>
<body>

<div class="form-container">
    <h2>Validação de Usuários</h2>

    <!-- Exibe a mensagem de sucesso ou erro -->
    <?php if (isset($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Cidade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['cidade']) ?></td>
                    <td><?= htmlspecialchars($usuario['status']) ?></td>
                    <td>
                        <form method="post" onsubmit="return confirmarAtivacao()">
                            <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                            <input type="hidden" name="status" value="Ativo">
                            <button type="submit">Ativar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="back-link">
        <a href="admin.php">Voltar ao painel</a>
    </div>
</div>

</body>
</html>
