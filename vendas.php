<?php
// Mantenha seu funcs.php como está
include "funcs.php";
$conn = conecta();

// Sua query SQL está perfeita
$varSQL = "SELECT id_produto, nome, preco, data_colheita, foto FROM produtos ORDER BY nome";
$result = $conn->query($varSQL);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feira Fresca - Vendas</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <a href="index.php" class="btn-voltar">‹ Voltar ao Menu</a>

    <div class="container">
        <div class="secao-produtos">
            <h1>Nossos Produtos Fresquinhos</h1>
            <div class="lista-produtos">
                <?php
                // Loop para exibir cada produto do banco de dados
                if ($result) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <div class="produto-card">
                            <?php
                            // Chama a nossa nova função para exibir a imagem de forma segura
                            echo exibirImagem($row['foto'], $row['nome']);
                            ?>
                            <h2><?= htmlspecialchars($row['nome']) ?></h2>
                            <p class="preco">R$ <?= number_format($row['preco'], 2, ',', '.') ?></p>
                            <p class="colheita">Colhido em: <?= date('d/m/Y', strtotime($row['data_colheita'])) ?></p>

                            <div class="controle-quantidade">
                                <label for="qtd-<?= $row['id_produto'] ?>">Quantidade:</label>
                                <input
                                    type="number"
                                    id="qtd-<?= $row['id_produto'] ?>"
                                    min="0"
                                    value="0"
                                    class="input-qtd"
                                    data-id="<?= $row['id_produto'] ?>"
                                    data-nome="<?= htmlspecialchars($row['nome']) ?>"
                                    data-preco="<?= $row['preco'] ?>">
                            </div>
                        </div>
                <?php
                    }
                }
                ?>

                ?>
            </div>
        </div>

        <div class="secao-carrinho">
            <h2>Sua Cesta</h2>
            <div id="carrinho-itens">
                <p>Sua cesta está vazia.</p>
            </div>
            <div class="carrinho-total">
                <h3>Total:</h3>
                <span id="valor-total">R$ 0,00</span>
            </div>
            <button id="btn-comprar">Comprar</button>
        </div>
    </div>

    <script src="script.js"></script>

</body>

</html>