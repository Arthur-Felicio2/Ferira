<?php
include "funcs.php";

if (!isset($_SESSION['usuario']['admin']) || $_SESSION['usuario']['admin'] != true) {
    die("Acesso não autorizado.");
}

$conn = conecta();

function uploadImagem($file_data)
{
    if ($file_data['error'] !== UPLOAD_ERR_OK) return false;
    $upload_dir = 'imagem/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
    $nome_arquivo = uniqid('', true) . '_' . basename($file_data['name']);
    $caminho_arquivo = $upload_dir . $nome_arquivo;
    if (move_uploaded_file($file_data['tmp_name'], $caminho_arquivo)) {
        return $caminho_arquivo;
    }
    return false;
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

// Ação de Cadastrar (Create)
if ($acao == 'cadastrar') {
    $caminho_imagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $caminho_imagem = uploadImagem($_FILES['imagem']);
    }

    $sql = "INSERT INTO produto (nome, descricao, valor_unitario, qtde_estoque, imagem, excluido) VALUES (:nome, :descricao, :valor, :estoque, :imagem, false)";

    // USANDO A NOVA FUNÇÃO
    ExecutaSQL($conn, $sql, [
        ':nome' => $_POST['nome'],
        ':descricao' => $_POST['descricao'],
        ':valor' => $_POST['valor_unitario'],
        ':estoque' => $_POST['qtde_estoque'],
        ':imagem' => $caminho_imagem
    ]);
}

// Ação de Editar (Update)
if ($acao == 'editar') {
    $caminho_imagem = $_POST['imagem_antiga'];
    if (isset($_FILES['imagem']) && !empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] == 0) {
        $nova_imagem = uploadImagem($_FILES['imagem']);
        if ($nova_imagem) {
            if ($caminho_imagem && file_exists($caminho_imagem)) {
                unlink($caminho_imagem);
            }
            $caminho_imagem = $nova_imagem;
        }
    }

    $sql = "UPDATE produto SET nome = :nome, descricao = :descricao, valor_unitario = :valor, qtde_estoque = :estoque, imagem = :imagem WHERE id_produto = :id";

    // USANDO A NOVA FUNÇÃO
    ExecutaSQL($conn, $sql, [
        ':nome' => $_POST['nome'],
        ':descricao' => $_POST['descricao'],
        ':valor' => $_POST['valor_unitario'],
        ':estoque' => $_POST['qtde_estoque'],
        ':imagem' => $caminho_imagem,
        ':id' => $_POST['id_produto']
    ]);
}

// Ação de Excluir (Delete Lógico)
if ($acao == 'excluir') {
    $sql = "UPDATE produto SET excluido = true WHERE id_produto = :id";

    // USANDO A NOVA FUNÇÃO
    ExecutaSQL($conn, $sql, [':id' => $_GET['id']]);
}

header("Location: admin.php");
exit();
