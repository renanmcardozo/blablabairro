<?php
session_start();

// Verifica se o usuário está logado e se é administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'Administrador') {
    header("Location: index.php"); // Redireciona para a página principal caso não seja administrador
    exit();
}

// Conexão com o banco de dados
require('conexao.php');

// Variável para armazenar a mensagem de feedback
$msg = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoria = $_POST['categoria'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    // Prepara e executa a query para inserir a nova informação
    $sql = "INSERT INTO informacoes (categoria, titulo, descricao) VALUES (:categoria, :titulo, :descricao)";
    $stmt = $pdo->prepare($sql);

    // Verifica se a execução foi bem-sucedida
    if ($stmt->execute([
        'categoria' => $categoria,
        'titulo' => $titulo,
        'descricao' => $descricao
    ])) {
        $msg = "Informação cadastrada com sucesso!";
    } else {
        $msg = "Erro ao cadastrar a informação. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Informações</title>
    <link rel="stylesheet" href="css/informacoes.css">
    <script>
        // Exibe o popup de sucesso/erro se houver mensagem
        window.onload = function() {
            <?php if (!empty($msg)): ?>
                alert('<?php echo $msg; ?>');
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Cadastrar Nova Informação</h2>
        <form method="POST">
            <label for="categoria">Categoria:</label>
            <input type="text" name="categoria" placeholder="Digite a categoria" required><br><br>

            <label for="titulo">Título:</label>
            <input type="text" name="titulo" placeholder="Digite o título" required><br><br>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" placeholder="Digite a descrição" required></textarea><br><br>

            <button type="submit">Cadastrar</button>
        </form>

        <div class="back-link">
            <a href="admin.php">Voltar ao Painel</a>
        </div>
    </div>
</body>
</html>
