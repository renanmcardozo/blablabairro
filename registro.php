<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Usando require para incluir a conexão com o banco de dados
    require('conexao.php');

    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha']; // Senha sem criptografia
    $cpf = $_POST['cpf'];
    $nascimento = $_POST['nascimento'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];

    // Verifica se os arquivos foram enviados
    if (isset($_FILES['imagemDocumento']) && isset($_FILES['imagemEndereco'])) {
        // Processa o arquivo de imagem do documento
        $imagemDocumento = file_get_contents($_FILES['imagemDocumento']['tmp_name']);

        // Processa o arquivo de imagem de endereço
        $imagemEndereco = file_get_contents($_FILES['imagemEndereco']['tmp_name']);

        // Prepara a query para inserir o novo usuário
        $sql = "INSERT INTO usuario (nome, email, senha, cpf, nascimento, telefone, cep, endereco, bairro, cidade, imagemDocumento, imagemEndereco) 
                VALUES (:nome, :email, :senha, :cpf, :nascimento, :telefone, :cep, :endereco, :bairro, :cidade, :imagemDocumento, :imagemEndereco)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha, // Senha sem criptografar
            'cpf' => $cpf,
            'nascimento' => $nascimento,
            'telefone' => $telefone,
            'cep' => $cep,
            'endereco' => $endereco,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'imagemDocumento' => $imagemDocumento,
            'imagemEndereco' => $imagemEndereco
        ]);

        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Por favor, envie ambos os arquivos de imagem (documento e endereço).";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <link rel="stylesheet" href="css/registro.css">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
</head>
<body>
    
    <form method="POST" enctype="multipart/form-data">
    <h2>Cadastro de Usuário</h2>

    <div class="input-group">
        <div class="input-wrapper">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>
        </div>
        <div class="input-wrapper">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div class="input-wrapper">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>
        </div>
        <div class="input-wrapper">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" required>
        </div>
        <div class="input-wrapper">
            <label for="nascimento">Data de Nascimento:</label>
            <input type="date" name="nascimento" required>
        </div>
        <div class="input-wrapper">
            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone">
        </div>
        <div class="input-wrapper full-width">
            <label for="cep">CEP:</label>
            <input type="text" name="cep" required>
        </div>
        <div class="input-wrapper full-width">
            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" required>
        </div>
        <div class="input-wrapper">
            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" required>
        </div>
        <div class="input-wrapper">
            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" required>
        </div>
        <div class="input-wrapper">
            <label for="imagemDocumento">Imagem do Documento:</label>
            <input type="file" name="imagemDocumento" accept="image/*" required>
        </div>
        <div class="input-wrapper">
            <label for="imagemEndereco">Imagem do Endereço:</label>
            <input type="file" name="imagemEndereco" accept="image/*" required>
        </div>
    </div>

    <button type="submit">Cadastrar</button>
    <a href="login.php">Voltar</a>
</form>

</body>
</html>
