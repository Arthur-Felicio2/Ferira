<?php
include "funcs.php";
$conn = conecta();

$modo_edicao = false;
$produto = [
    'id_produto' => '',
    'nome' => '',
    'preco' => '',
    'data_colheita' => '',
    'foto' => '' // Vai armazenar o link (URL)
];

// Se um ID foi passado pela URL, estamos em modo de edição
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $modo_edicao = true;
    $id_produto = $_GET['id'];

    $sql = "SELECT * FROM produtos WHERE id_produto = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

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

        <form action="processa_produto.php" method="POST">

            <input type="hidden" name="acao" value="<?= $modo_edicao ? 'editar' : 'cadastrar' ?>">
            <?php if ($modo_edicao): ?>
                <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
            <?php endif; ?>

            <div class="form-grupo">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>

            <div class="form-grupo">
                <label for="preco">Preço (ex: 9.99):</label>
                <input type="number" step="0.01" id="preco" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required>
            </div>

            <div class="form-grupo">
                <label for="data_colheita">Data da Colheita:</label>
                <input type="date" id="data_colheita" name="data_colheita" value="<?= htmlspecialchars($produto['data_colheita']) ?>" required>
            </div>

            <div class="form-grupo">
                <label for="foto">Link (URL) da Foto do Produto:</label>
                <input type="url" id="foto" name="foto" placeholder="https://exemplo.com/imagem.jpg" value="<?= htmlspecialchars($produto['foto']) ?>">

                <?php if ($modo_edicao && !empty($produto['foto'])): ?>
                    <p>Foto atual: <img src="<?= htmlspecialchars($produto['foto']) ?>" alt="Foto atual" class="foto-produto-preview"></p>
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