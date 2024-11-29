<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $tipo_servico = $_POST['tipo_servico'];
    $quantidade = $_POST['quantidade'];
    $endereco_servico = $_POST['endereco_servico'];
    $data_servico = $_POST['data_servico'];
    $id_usuario = $_SESSION['id_usuario'];

    // Prepara a query para inserir o novo serviço
    $sql = "INSERT INTO servicos (id_usuario, tipo_servico, quantidade, endereco_servico, data_servico) 
            VALUES (:id_usuario, :tipo_servico, :quantidade, :endereco_servico, :data_servico)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id_usuario' => $id_usuario,
        'tipo_servico' => $tipo_servico,
        'quantidade' => $quantidade,
        'endereco_servico' => $endereco_servico,
        'data_servico' => $data_servico
    ]);

    // Exibe uma mensagem de sucesso via popup
    echo "<script>
            alert('Serviço registrado com sucesso!');
            setTimeout(function() {
                window.location.href = 'index.php'; // Redireciona para o painel após 2 segundos
            }, 2000);
          </script>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Serviço</title>
    <link rel="stylesheet" href="css/cadastro_servico.css">
</head>
<body>
    <div class="form-container">
        <h2>Cadastro de Serviço</h2>
        <form method="POST">
            <label for="tipo_servico">Tipo de Serviço:</label>
            <select name="tipo_servico" required>
                <option value="Coleta de Óleo">Coleta de Óleo</option>
                <option value="Coleta de Reciclados">Coleta de Reciclados</option>
                <option value="Entulho">Entulho</option>
                <option value="Poda/Retirada de Resíduos">Poda/Retirada de Resíduos</option>
                <option value="Sugestões">Sugestões</option>
                <option value="Informações">Informações</option>
            </select><br><br>

            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" required min="1"><br><br>

            <label for="endereco_servico">Endereço:</label>
            <input type="text" name="endereco_servico" required><br><br>

            <label for="data_servico">Data do Serviço:</label>
            <input type="datetime-local" name="data_servico" required><br><br>

            <button type="submit">Cadastrar Serviço</button>
        </form>
        <div class="back-link">
            <a href="index.php">Voltar ao Painel</a>
        </div>
    </div>
</body>
</html>
