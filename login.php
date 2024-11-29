<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Usando require para incluir a conexão com o banco de dados
    require('conexao.php');
    
    // Captura os dados do formulário de login
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o email existe no banco de dados
    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário existe e se a senha fornecida é igual à armazenada
    if ($usuario && $usuario['senha'] == $senha) {  // Comparação direta sem hash
        // Login bem-sucedido, inicia a sessão
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['tipo'] = $usuario['tipo'];
        $_SESSION['cidade'] = $usuario['cidade'];  // Armazenando a cidade na sessão

        // Redireciona com base no tipo de usuário
        if ($usuario['tipo'] == 'Administrador') {
            header("Location: admin.php"); // Página do administrador
        } elseif ($usuario['tipo'] == 'Prestador de Serviços') {
            header("Location: index_prestador.php"); // Página do prestador de serviços
        } else {
            header("Location: index.php"); // Página principal para outros tipos de usuários
        }
        exit();
    } else {
        $erro = "Email ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Blá-Blá-Bairro</title>
    <link rel="stylesheet" href="css/login.css"> <!-- Link para o CSS externo -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap" rel="stylesheet"> <!-- Fonte Libre Baskerville -->
</head>
<body>
    <!-- Nome do projeto -->
    <header class="project-header">
        <h1>Blá-Blá-Bairro</h1>
    </header>

    <!-- Formulário de login -->
    <form method="POST">
        <h2>Login</h2>
        <!-- Exibição de erro -->
        <?php if (isset($erro)) { echo "<p class='error'>$erro</p>"; } ?>
        
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Digite seu email" required>
        
        <label for="senha">Senha:</label>
        <input type="password" name="senha" placeholder="Digite sua senha" required>
        
        <br><button type="submit">Entrar</button><br>
        
        <!-- Link para o cadastro -->
        <br><p>Não tem uma conta? <a href="registro.php">Registre-se aqui</a></p><br>
    </form>
</body>
</html>


