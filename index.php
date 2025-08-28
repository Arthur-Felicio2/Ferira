<?php
// Incluímos para poder verificar a sessão
include "funcs.php";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à Feira Fresca</title>
    <link rel="stylesheet" href="estilo_menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="menu-container">
        <header>
            <div class="info-usuario-menu">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <div class="menu-perfil">
                        <button class="btn-perfil" onclick="toggleMenu()">
                            Olá, <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>! &#9662;
                        </button>
                        <div id="dropdown-perfil" class="dropdown-conteudo">
                            <a href="logout.php">Sair</a>
                            <a href="auth.php?acao=auto_excluir" class="link-perigo"
                                onclick="return confirm('ATENÇÃO!\n\nTem certeza que deseja excluir sua conta?\nEsta ação é permanente e não pode ser desfeita.');">
                                Excluir Conta
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Login / Cadastrar</a>
                <?php endif; ?>
            </div>
            <h1>Feira Fresca</h1>
            <p>Selecione seu tipo de acesso</p>
        </header>

        <main class="opcoes">
            <a href="vendas.php" class="card-opcao">
                <h2>Fazer Compras</h2>
                <p>Acesse nossa loja para ver os produtos fresquinhos e encher sua cesta!</p>
            </a>

            <a href="admin.php" class="card-opcao">
                <h2>Área Administrativa</h2>
                <p>Gerencie os produtos, adicione novidades, atualize preços e muito mais.</p>
            </a>
        </main>
    </div>
    <script>
        function toggleMenu() {
            document.getElementById("dropdown-perfil").classList.toggle("mostrar");
        }
        window.onclick = function (event) {
            if (!event.target.matches('.btn-perfil')) {
                var dropdowns = document.getElementsByClassName("dropdown-conteudo");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('mostrar')) {
                        openDropdown.classList.remove('mostrar');
                    }
                }
            }
        }
    </script>
</body>

</html>