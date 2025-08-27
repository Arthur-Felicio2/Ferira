<?php
session_start();
include "funcs.php";
$conn = conecta();

if (!isset($_POST['acao'])) {
    header('Location: index.php');
    exit();
}

$acao = $_POST['acao'];

// --- AÇÃO DE CADASTRAR NOVO USUÁRIO ---
if ($acao == 'cadastrar') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // 1. Verificar se o e-mail já existe
    $stmt = $conn->prepare("SELECT cod_usuario FROM usuario WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        $_SESSION['mensagem'] = "Erro: Este e-mail já está cadastrado.";
        header('Location: cadastro.php');
        exit();
    }

    // 2. Criptografar a senha (HASH)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // 3. Inserir no banco de dados (admin é false por padrão)
    $sql = "INSERT INTO usuario (nome, email, senha, admin) VALUES (:nome, :email, :senha, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
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

    // 1. Buscar usuário pelo e-mail
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Verificar se o usuário existe e se a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido!
        $_SESSION['usuario'] = [
            'cod_usuario' => $usuario['cod_usuario'],
            'nome' => $usuario['nome'],
            'admin' => $usuario['admin']
        ];
        header('Location: vendas.php'); // Redireciona para a loja
        exit();
    } else {
        // Login falhou
        $_SESSION['mensagem'] = "E-mail ou senha inválidos.";
        header('Location: login.php');
        exit();
    }
}
?>