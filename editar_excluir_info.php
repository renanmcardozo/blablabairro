<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require('conexao.php');

// Verificar se o ID da informação foi passado
if (isset($_GET['id'])) {
    $id_info = $_GET['id'];

    // Buscar os dados da informação com o ID fornecido
    $sql = "SELECT * FROM informacoes WHERE id_info = :id_info";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_info' => $id_info]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$info) {
        // Se não encontrar o ID, redireciona para a página de admin
        header("Location: admin.php");
        exit();
    }

    // Atualizar a informação quando o formulário for enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Verificar se é uma edição ou exclusão
        if (isset($_POST['editar'])) {
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];

            $update_sql = "UPDATE informacoes SET titulo = :titulo, descricao = :descricao WHERE id_info = :id_info";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([
                'titulo' => $titulo,
                'descricao' => $descricao,
                'id_info' => $id_info
            ]);

            // Verificar se a atualização foi bem-sucedida
            if ($update_stmt->rowCount() > 0) {
                // Exibe o sucesso como popup
                echo "<script>
                        alert('Informação atualizada com sucesso!');
                        setTimeout(function() {
                            window.location.href = 'admin.php'; // Redireciona para a página de admin após 2 segundos
                        }, 2000);
                      </script>";
            } else {
                // Exibe o erro como popup
                echo "<script>
                        alert('Nenhuma alteração feita!');
                      </script>";
            }
        } elseif (isset($_POST['excluir'])) {
            // Deletar a informação
            $delete_sql = "DELETE FROM informacoes WHERE id_info = :id_info";
            $delete_stmt = $pdo->prepare($delete_sql);
            $delete_stmt->execute(['id_info' => $id_info]);

            // Verificar se a exclusão foi bem-sucedida
            if ($delete_stmt->rowCount() > 0) {
                // Exibe o sucesso como popup
                echo "<script>
                        alert('Informação excluída com sucesso!');
                        setTimeout(function() {
                            window.location.href = 'admin.php'; // Redireciona para a página de admin após 2 segundos
                        }, 2000);
                      </script>";
            } else {
                // Exibe o erro como popup
                echo "<script>
                        alert('Erro ao excluir a informação!');
                      </script>";
            }
        }
    }
} else {
    // Se não houver ID na URL, redireciona para a página de admin
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar ou Excluir Informação</title>
    <link rel="stylesheet" href="css/editar_excluir_info.css">
</head>
<body>

    <div class="container">
        <h2>Editar ou Excluir Informação</h2>

        <!-- Aqui, a mensagem de sucesso será gerada via popup -->
        <form method="POST">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($info['titulo']); ?>" required>
            
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" required><?php echo htmlspecialchars($info['descricao']); ?></textarea>
            
            <button type="submit" name="editar">Salvar Alterações</button>
            <button type="submit" name="excluir" style="background-color: #d24472;">Excluir Informação</button>
        </form>
    </div>
</body>
</html>
