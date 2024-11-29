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
    $senha_confirm = $_POST['senha_confirm'];

    // Validação da senha e confirmação
    if ($senha && $senha !== $senha_confirm) {
        // Exibe o erro como popup
        echo "<script>
                alert('As senhas não coincidem. Tente novamente.');
              </script>";
    } else {
        // Se a senha for fornecida e confirmada
        if ($senha) {
            $senha = password_hash($senha, PASSWORD_BCRYPT); // Hasheia a senha
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
            // Exibe o sucesso como popup
            echo "<script>
                    alert('Perfil atualizado com sucesso!');
                    setTimeout(function() {
                        window.history.back();
                    }, 2000); // Aguarda 2 segundos antes de voltar
                  </script>";
        } else {
            // Exibe o erro como popup
            echo "<script>
                    alert('Erro ao atualizar perfil.');
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/editar_perfil.css">
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

            <label for="senha_confirm">Confirmar Nova Senha:</label>
            <input type="password" name="senha_confirm" placeholder="Confirme sua nova senha">

            <button type="submit" class="btn">Salvar Alterações</button>
        </form>

        <a href="index_prestador.php" class="btn-back">Voltar</a>
    </div>
</body>
</html>
