<?php
// Mantenha seu funcs.php como está, ele já inicia a sessão
include "funcs.php";
$conn = conecta();

// SQL AJUSTADO: Seleciona da tabela "produto" e usa as colunas corretas.
$varSQL = "SELECT id_produto, nome, valor_unitario, imagem, descricao FROM produto WHERE excluido = false ORDER BY nome";
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
    <header class="menu-usuario">
        <div class="container-menu">
            <a href="index.php" class="btn-voltar">‹ Voltar ao Menu</a>
            <div class="info-usuario">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <span>Olá, <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>!</span>
                    <a href="logout.php" class="btn-sair">Sair</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Login / Cadastrar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="secao-produtos">
            <h1>Nossos Produtos Fresquinhos</h1>
            <div class="lista-produtos">
                <?php
                if ($result) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <div class="produto-card">
                            <?php
                            echo exibirImagem($row['imagem'], $row['nome']);
                            ?>
                            <h2><?= htmlspecialchars($row['nome']) ?></h2>
                            <p class="preco">R$ <?= number_format($row['valor_unitario'], 2, ',', '.') ?></p>
                            <p class="descricao"><?= htmlspecialchars($row['descricao']) ?></p>

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
                                    data-preco="<?= $row['valor_unitario'] ?>">
                            </div>
                        </div>
                <?php
                    }
                }
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

    <script>
        const isLoggedIn = <?= isset($_SESSION['usuario']) ? 'true' : 'false' ?>;
    </script>
    <script src="script.js"></script>

</body>

</html>