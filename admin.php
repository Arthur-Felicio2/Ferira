<?php
include "funcs.php";

// Proteção da página
if (!isset($_SESSION['usuario']['admin']) || $_SESSION['usuario']['admin'] != true) {
    $_SESSION['mensagem'] = "Acesso negado. Apenas administradores podem acessar esta área.";
    header('Location: login.php');
    exit();
}

// Busca os produtos no banco
$conn = conecta();
$varSQL = "SELECT * FROM produto WHERE excluido = false ORDER BY id_produto DESC";
$result = $conn->query($varSQL);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Admin - Gerenciar Produtos</title>
    <link rel="stylesheet" href="estilo_admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Gerenciador de Produtos da Feira</h1>
        <a href="index.php" class="btn-voltar-admin">‹ Voltar ao Menu Principal</a>

        <div class="form-acoes" style="margin-bottom: 20px; justify-content: flex-start;">
            <a href="form_produto.php" class="btn-novo">Cadastrar Novo Produto</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Valor Unitário</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td>
                                <?php echo exibirImagem($row['imagem'], "Foto de " . $row['nome'], 'foto-produto'); ?>
                            </td>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['descricao']) ?></td>
                            <td>R$ <?= number_format($row['valor_unitario'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['qtde_estoque']) ?></td>
                            <td class="acoes">
                                <a href="form_produto.php?id=<?= $row['id_produto'] ?>" class="btn-editar">Editar</a>
                                <a href="processa_produto.php?acao=excluir&id=<?= $row['id_produto'] ?>" class="btn-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>