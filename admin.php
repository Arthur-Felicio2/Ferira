<?php
include "funcs.php";
$conn = conecta();

$varSQL = "SELECT * FROM produtos ORDER BY id_produto DESC";
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
        <a href="index.php" class="btn-voltar-admin">‹ Voltar ao Menu</a>
        <a href="form_produto.php" class="btn-novo">Cadastrar Novo Produto</a>

        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Data da Colheita</th>
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
                                <?php
                                // Chama a nossa nova função para exibir a imagem de forma segura
                                echo exibirImagem($row['foto'], "Foto de " . $row['nome'], 'foto-produto');
                                ?>
                            </td>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($row['data_colheita'])) ?></td>
                            <td class="acoes">
                                <a href="form_produto.php?id=<?= $row['id_produto'] ?>" class="btn-editar">Editar</a>
                                <a href="processa_produto.php?acao=excluir&id=<?= $row['id_produto'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
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