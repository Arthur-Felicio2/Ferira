<?php
include "funcs.php";
$conn = conecta();

// Determinamos a ação por POST ou GET
$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

if (empty($acao)) {
    header('Location: index.php');
    exit();
}

// --- AÇÃO DE CADASTRAR NOVO USUÁRIO ---
if ($acao == 'cadastrar') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // USANDO ValorSQL para verificar se o e-mail já existe. Mais limpo!
    $sql_check = "SELECT COUNT(cod_usuario) FROM usuario WHERE email = :email";
    if (ValorSQL($conn, $sql_check, [':email' => $email]) > 0) {
        $_SESSION['mensagem'] = "Erro: Este e-mail já está cadastrado.";
        header('Location: cadastro.php');
        exit();
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // USANDO ExecutaSQL para inserir o novo usuário. Mais simples!
    $sql_insert = "INSERT INTO usuario (nome, email, senha, admin) VALUES (:nome, :email, :senha, false)";
    ExecutaSQL($conn, $sql_insert, [
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senha_hash
    ]);

    $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Faça o login.";
    header('Location: login.php');
    exit();
}

// --- AÇÃO DE LOGIN ---
if ($acao == 'login') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // USANDO TrazLinhaSQL para buscar os dados do usuário. Mais direto!
    $sql = "SELECT * FROM usuario WHERE email = :email AND excluido = false";
    $usuario = TrazLinhaSQL($conn, $sql, [':email' => $email]);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // A lógica de sessão permanece a mesma
        $_SESSION['usuario'] = [
            'cod_usuario' => $usuario['cod_usuario'],
            'nome' => $usuario['nome'],
            'admin' => (bool)$usuario['admin'] // Convertendo para booleano para segurança
        ];
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['mensagem'] = "E-mail ou senha inválidos.";
        header('Location: login.php');
        exit();
    }
}

// --- AÇÃO DE AUTOEXCLUSÃO ---
if ($acao == 'auto_excluir') {
    if (!isset($_SESSION['usuario']['cod_usuario'])) {
        header('Location: login.php');
        exit();
    }

    // USANDO ExecutaSQL para marcar o usuário como excluído. Mais legível!
    $sql = "UPDATE usuario SET excluido = true WHERE cod_usuario = :id";
    ExecutaSQL($conn, $sql, [':id' => $_SESSION['usuario']['cod_usuario']]);

    // A lógica de logout permanece a mesma
    session_unset();
    session_destroy();

    session_start();
    $_SESSION['mensagem'] = "Sua conta foi excluída com sucesso.";
    header('Location: login.php');
    exit();
}
