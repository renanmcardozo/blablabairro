<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'Administrador') {
    header('Location: login.php');
    exit;
}

$id_admin = $_SESSION['id_usuario'];

$stmtAdmin = $pdo->prepare("SELECT cidade FROM usuario WHERE id_usuario = :id_admin");
$stmtAdmin->execute(['id_admin' => $id_admin]);
$admin = $stmtAdmin->fetch();

if (!$admin) {
    echo "Erro ao buscar informações do administrador.";
    exit;
}

$cidadeAdmin = $admin['cidade'];

$stmtDenuncias = $pdo->prepare("
    SELECT 
        d.*, u.nome AS nome_usuario 
    FROM denuncias d
    JOIN usuario u ON d.id_usuario = u.id_usuario
    WHERE u.cidade = :cidadeAdmin
");
$stmtDenuncias->execute(['cidadeAdmin' => $cidadeAdmin]);
$denuncias = $stmtDenuncias->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_denuncia'], $_POST['status'])) {
    $stmtUpdate = $pdo->prepare("
        UPDATE denuncias 
        SET status = :status 
        WHERE id_denuncia = :id_denuncia
    ");
    $stmtUpdate->execute([
        'status' => $_POST['status'],
        'id_denuncia' => $_POST['id_denuncia']
    ]);
    $_SESSION['msg'] = 'Status da denúncia atualizado com sucesso!'; // Definir a mensagem de sucesso
    header('Location: denuncias.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Denúncias</title>
    <link rel="stylesheet" href="css/denuncias.css">
    <script>
        // Exibir o popup de sucesso se houver mensagem na sessão
        window.onload = function() {
            <?php if (isset($_SESSION['msg'])): ?>
                alert('<?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>');
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Gerenciar Denúncias - Cidade: <?= htmlspecialchars($cidadeAdmin) ?></h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Endereço</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($denuncias as $denuncia): ?>
                    <tr>
                        <td><?= htmlspecialchars($denuncia['id_denuncia']) ?></td>
                        <td><?= htmlspecialchars($denuncia['nome_usuario']) ?></td>
                        <td><?= htmlspecialchars($denuncia['tipo_denuncia']) ?></td>
                        <td><?= htmlspecialchars($denuncia['descricao']) ?></td>
                        <td><?= htmlspecialchars($denuncia['endereco_denuncia']) ?></td>
                        <td><?= htmlspecialchars($denuncia['dia_hora']) ?></td>
                        <td><?= htmlspecialchars($denuncia['status']) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="id_denuncia" value="<?= $denuncia['id_denuncia'] ?>">
                                <select name="status">
                                    <option <?= $denuncia['status'] === 'Aberta' ? 'selected' : '' ?> value="Aberta">Aberta</option>
                                    <option <?= $denuncia['status'] === 'Em Andamento' ? 'selected' : '' ?> value="Em Andamento">Em Andamento</option>
                                    <option <?= $denuncia['status'] === 'Concluída' ? 'selected' : '' ?> value="Concluída">Concluída</option>
                                </select>
                                <button type="submit">Salvar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="back-link">
            <a href="admin.php">Voltar ao Painel Administrativo</a>
        </div>
    </div>
</body>
</html>
