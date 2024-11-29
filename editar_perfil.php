<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require('conexao.php');

$id_usuario = $_SESSION['id_usuario'];

// Buscar os dados do usuário no banco de dados
$sql = "SELECT nome, email FROM usuario WHERE id_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_usuario' => $id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = !empty($_POST['senha']) ? $_POST['senha'] : null;
    $confirma_senha = $_POST['confirma_senha'];

    // Verificar se a senha e a confirmação são iguais
    if ($senha && $senha !== $confirma_senha) {
        $_SESSION['msg'] = 'As senhas não coincidem. Por favor, tente novamente.';
        header("Location: editar_perfil.php"); // Redireciona de volta
        exit;
    }

    if ($senha) {
        // Se a senha foi fornecida, faz o hash da senha
        $senha = password_hash($senha, PASSWORD_BCRYPT);
    }

    // Atualizar os dados no banco
    $sql_update = "UPDATE usuario SET nome = :nome, email = :email";
    if ($senha) {
        $sql_update .= ", senha = :senha";
    }
    $sql_update .= " WHERE id_usuario = :id_usuario";

    $stmt = $pdo->prepare($sql_update);
    $params = [
        'nome' => $nome,
        'email' => $email,
        'id_usuario' => $id_usuario
    ];

    if ($senha) {
        $params['senha'] = $senha;
    }

    if ($stmt->execute($params)) {
        $_SESSION['nome'] = $nome; // Atualiza o nome na sessão
        $_SESSION['msg'] = 'Perfil atualizado com sucesso!';
    } else {
        $_SESSION['msg'] = 'Erro ao atualizar perfil.';
    }

    header("Location: editar_perfil.php"); // Redireciona para a mesma página
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/editar_perfil.css">
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
    <div class="container">
        <h2>Editar Perfil</h2>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

            <label for="senha">Nova Senha (opcional):</label>
            <input type="password" name="senha" placeholder="Deixe em branco para não alterar">

            <label for="confirma_senha">Confirmar Nova Senha:</label>
            <input type="password" name="confirma_senha" placeholder="Confirme sua nova senha">

            <button type="submit" class="btn">Salvar Alterações</button>
        </form>

        <a href="index.php" class="btn-back">Voltar</a>
    </div>
</body>
</html>
