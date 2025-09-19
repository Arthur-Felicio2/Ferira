<?php
include "funcs.php";

if (!isset($_SESSION['usuario']['admin']) || $_SESSION['usuario']['admin'] != true) {
    die("Acesso não autorizado.");
}

$conn = conecta();
$modo_edicao = false;
$produto = [
    'id_produto' => '',
    'nome' => '',
    'descricao' => '',
    'valor_unitario' => '',
    'qtde_estoque' => '',
    'imagem' => ''
];

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $modo_edicao = true;

    // USANDO A NOVA FUNÇÃO PARA BUSCAR A LINHA
    $sql = "SELECT * FROM produto WHERE id_produto = :id";
    $produto = TrazLinhaSQL($conn, $sql, [':id' => $_GET['id']]);

    if (!$produto) {
        die("Produto não encontrado!");
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= $modo_edicao ? 'Editar' : 'Cadastrar' ?> Produto</title>
    <link rel="stylesheet" href="estilo_admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1><?= $modo_edicao ? 'Editar Produto' : 'Cadastrar Novo Produto' ?></h1>

        <form action="processa_produto.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="<?= $modo_edicao ? 'editar' : 'cadastrar' ?>">
            <?php if ($modo_edicao): ?>
                <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                <input type="hidden" name="imagem_antiga" value="<?= htmlspecialchars($produto['imagem']) ?>">
            <?php endif; ?>

            <div class="form-grupo">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>
            <div class="form-grupo">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="3" required><?= htmlspecialchars($produto['descricao']) ?></textarea>
            </div>
            <div class="form-grupo">
                <label for="valor_unitario">Valor Unitário (ex: 9.99):</label>
                <input type="number" step="0.01" id="valor_unitario" name="valor_unitario" value="<?= htmlspecialchars($produto['valor_unitario']) ?>" required>
            </div>
            <div class="form-grupo">
                <label for="qtde_estoque">Quantidade em Estoque:</label>
                <input type="number" step="1" id="qtde_estoque" name="qtde_estoque" value="<?= htmlspecialchars($produto['qtde_estoque']) ?>" required>
            </div>
            <div class="form-grupo">
                <label for="imagem">Imagem do Produto:</label>
                <input type="file" id="imagem" name="imagem" accept="image/png, image/jpeg, image/gif">
                <?php if ($modo_edicao && !empty($produto['imagem'])): ?>
                    <p>Imagem atual: <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem atual" class="foto-produto-preview"></p>
                <?php endif; ?>
            </div>
            <div class="form-acoes">
                <button type="submit" class="btn-salvar">Salvar</button>
                <a href="admin.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>