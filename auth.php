<?php
include "funcs.php";
$conn = conecta();

// Determinamos a ação por POST ou GET
$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

if (empty($acao)) {
    header('Location: index.php');
    exit();
}

// --- AÇÃO DE CADASTRAR NOVO USUÁRIO (SIMPLIFICADO) ---
if ($acao == 'cadastrar') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT cod_usuario FROM usuario WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        $_SESSION['mensagem'] = "Erro: Este e-mail já está cadastrado.";
        header('Location: cadastro.php');
        exit();
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // O usuário é SEMPRE cadastrado como não-admin (admin = 0 ou false)
    $sql = "INSERT INTO usuario (nome, email, senha, admin) VALUES (:nome, :email, :senha, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senha_hash]);

    $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Faça o login.";
    header('Location: login.php');
    exit();
}

// --- AÇÃO DE LOGIN ---
if ($acao == 'login') {
    // ... (a lógica de login permanece a mesma)
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE email = :email AND excluido = false";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $isAdmin = ($usuario['admin'] == 1);
        $_SESSION['usuario'] = [
            'cod_usuario' => $usuario['cod_usuario'],
            'nome' => $usuario['nome'],
            'admin' => $isAdmin
        ];
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['mensagem'] = "E-mail ou senha inválidos.";
        header('Location: login.php');
        exit();
    }
}

// --- NOVA AÇÃO DE AUTOEXCLUSÃO ---
if ($acao == 'auto_excluir') {
    // Verifica se há um usuário logado na sessão
    if (!isset($_SESSION['usuario']['cod_usuario'])) {
        header('Location: login.php');
        exit();
    }

    $id_para_excluir = $_SESSION['usuario']['cod_usuario'];

    // Marca o usuário como excluído no banco
    $sql = "UPDATE usuario SET excluido = true WHERE cod_usuario = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id_para_excluir]);

    // Destrói a sessão para fazer logout
    session_unset();
    session_destroy();

    // Reinicia a sessão apenas para passar uma mensagem para a tela de login
    session_start();
    $_SESSION['mensagem'] = "Sua conta foi excluída com sucesso.";
    header('Location: login.php');
    exit();
}