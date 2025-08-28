<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro - Feira Fresca</title>
    <link rel="stylesheet" href="estilo_forms.css">
</head>

<body>
    <div class="container">
        <h1>Criar Nova Conta</h1>
        <?php
        if (isset($_SESSION['mensagem'])) {
            echo "<p class='mensagem-feedback'>" . htmlspecialchars($_SESSION['mensagem']) . "</p>";
            unset($_SESSION['mensagem']);
        }
        ?>
        <form action="auth.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar">
            <div class="form-grupo">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-grupo">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-grupo">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-acoes">
                <button type="submit" class="btn-salvar">Cadastrar</button>
                <a href="login.php" class="btn-cancelar">Já tenho conta</a>
            </div>
        </form>
    </div>
</body>

</html>