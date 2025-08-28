<?php
// Inicia a sessão para podermos usar variáveis de sessão (ex: mensagens de erro)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - Feira Fresca</title>
    <link rel="stylesheet" href="estilo_admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Acessar Minha Conta</h1>

        <?php
        // Exibe mensagens de erro ou sucesso que podem ter sido enviadas pelo auth.php
        if (isset($_SESSION['mensagem'])) {
            // Adicionei uma classe para poder estilizar a mensagem
            echo "<p class='mensagem-feedback'>" . htmlspecialchars($_SESSION['mensagem']) . "</p>";
            unset($_SESSION['mensagem']);
        }
        ?>

        <form action="auth.php" method="POST">
            <input type="hidden" name="acao" value="login">
            <div class="form-grupo">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-grupo">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-acoes">
                <button type="submit" class="btn-salvar">Entrar</button>
                <a href="vendas.php" class="btn-cancelar">Voltar à Loja</a>
            </div>
        </form>

        <div class="cadastro-link">
            <p>Ainda não tem uma conta?</p>
            <a href="cadastro.php" class="btn-novo">Cadastre-se Agora!</a>
        </div>
    </div>
</body>

</html>