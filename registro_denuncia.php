<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require('conexao.php');

    $id_usuario = $_SESSION['id_usuario'];
    $tipo_denuncia = $_POST['tipo_denuncia'];
    $descricao = $_POST['descricao'];
    $endereco_denuncia = $_POST['endereco_denuncia'];
    $dia_hora = $_POST['dia_hora'];
    $declaracao = isset($_POST['declaracao']) ? 1 : 0;

    // Inicializando as variáveis de foto e vídeo
    $foto = null;
    $video = null;

    // Verificação dos arquivos de foto e vídeo
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_type = $_FILES['foto']['type'];
        if (in_array($foto_type, ['image/jpeg', 'image/png', 'image/gif'])) {
            $foto = file_get_contents($_FILES['foto']['tmp_name']);
        } else {
            echo "<p class='error'>Formato de foto inválido. Apenas JPEG, PNG e GIF são permitidos.</p>";
            exit();
        }
    }

    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $video_type = $_FILES['video']['type'];
        if (in_array($video_type, ['video/mp4', 'video/webm', 'video/ogg'])) {
            $video = file_get_contents($_FILES['video']['tmp_name']);
        } else {
            echo "<p class='error'>Formato de vídeo inválido. Apenas MP4, WebM e OGG são permitidos.</p>";
            exit();
        }
    }

    // Inserção no banco de dados
    $sql = "INSERT INTO denuncias (id_usuario, tipo_denuncia, descricao, foto, video, endereco_denuncia, dia_hora, declaracao)
            VALUES (:id_usuario, :tipo_denuncia, :descricao, :foto, :video, :endereco_denuncia, :dia_hora, :declaracao)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([
        'id_usuario' => $id_usuario,
        'tipo_denuncia' => $tipo_denuncia,
        'descricao' => $descricao,
        'foto' => $foto,
        'video' => $video,
        'endereco_denuncia' => $endereco_denuncia,
        'dia_hora' => $dia_hora,
        'declaracao' => $declaracao
    ])) {
        echo "<p class='success'>Denúncia registrada com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao registrar a denúncia. Tente novamente.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Denúncia</title>
    <link rel="stylesheet" href="css/caddenuncia.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Denúncia</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="tipo_denuncia">Tipo de Denúncia:</label>
            <select name="tipo_denuncia" required>
                <option value="Lixo">Lixo</option>
                <option value="Barulho">Barulho</option>
                <option value="Estrutural">Estrutural</option>
                <option value="Invasão ou Abandono">Invasão ou Abandono</option>
                <option value="Fiscalização">Fiscalização</option>
                <option value="Informações">Informações</option>
            </select>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" rows="5" placeholder="Descreva a denúncia..." required></textarea>

            <label for="endereco_denuncia">Endereço:</label>
            <input type="text" name="endereco_denuncia" placeholder="Digite o endereço" required>

            <label for="dia_hora">Data e Hora:</label>
            <input type="datetime-local" name="dia_hora" required>

            <label for="foto">Foto (opcional):</label>
            <input type="file" name="foto" accept="image/*">

            <label for="video">Vídeo (opcional):</label>
            <input type="file" name="video" accept="video/*">

            <label class="declaracao">
                Confirmo que as informações fornecidas são verdadeiras.
                <input type="checkbox" name="declaracao" checked>
            </label>

            <button type="submit" class="btn">Enviar Denúncia</button>
        </form>

        <div class="back-link">
            <a href="index.php">Voltar ao Painel</a>
        </div>
    </div>
</body>
</html>
