<?php
session_start();

// Verificar se o usuário está logado e se é do tipo "Administrador"
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Administrador') {
    header("Location: login.php");
    exit();
}

require('conexao.php');

// Obter a cidade do administrador logado
$id_admin = $_SESSION['id_usuario'];
$sql_cidade = "SELECT cidade FROM usuario WHERE id_usuario = :id_usuario";
$stmt_cidade = $pdo->prepare($sql_cidade);
$stmt_cidade->execute(['id_usuario' => $id_admin]);
$admin = $stmt_cidade->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die('Cidade do administrador não encontrada.');
}

$cidade_admin = $admin['cidade'];

// Buscar usuários da mesma cidade que o administrador
$sql_usuarios = "SELECT id_usuario, nome, tipo FROM usuario WHERE cidade = :cidade AND tipo != 'Administrador'";
$stmt_usuarios = $pdo->prepare($sql_usuarios);
$stmt_usuarios->execute(['cidade' => $cidade_admin]);
$usuarios = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);

// Atualizar o tipo de usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'], $_POST['tipo'])) {
    $id_usuario = $_POST['id_usuario'];
    $tipo = $_POST['tipo'];

    $sql_update = "UPDATE usuario SET tipo = :tipo WHERE id_usuario = :id_usuario";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        'tipo' => $tipo,
        'id_usuario' => $id_usuario
    ]);

    header("Location: gerenciar_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="css/gerenciar_usuarios.css">
    <script>
        function confirmarMudanca() {
            return confirm('Tem certeza de que deseja alterar o tipo de usuário?');
        }
    </script>
</head>
<body>

    <div class="container">
        <h2>Gerenciar Usuários</h2>
        <?php if (count($usuarios) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tipo de Usuário</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirmarMudanca()">
                                    <select name="tipo" onchange="this.form.submit()">
                                        <option value="Comum" <?php if ($usuario['tipo'] == 'Comum') echo 'selected'; ?>>Comum</option>
                                        <option value="Prestador de Serviços" <?php if ($usuario['tipo'] == 'Prestador de Serviços') echo 'selected'; ?>>Prestador de Serviços</option>
                                    </select>
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                    <button type="submit">Confirmar Mudança</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="back-link">
    <a href="admin.php">Voltar ao Painel</a>
</div>
        <?php else: ?>
            <p>Não há usuários na sua cidade no momento.</p>
        <?php endif; ?>
    </div>
    
</body>
</html>
