<?php
session_start();
session_destroy(); // Destrói todos os dados da sessão
header('Location: index.php'); // Redireciona para a página inicial
exit();
?>