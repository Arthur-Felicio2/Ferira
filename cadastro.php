<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Feira Fresca</title>
    <link rel="stylesheet" href="estilo_admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Criar Nova Conta</h1>
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