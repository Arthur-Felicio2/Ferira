<?php
include "funcs.php";
$conn = conecta();

// --- FUNÇÃO AUXILIAR PARA UPLOAD DE FOTO ---
function uploadFoto($foto_file)
{
    $upload_dir = 'uploads/';
    // Gera um nome único para o arquivo para evitar substituições
    $nome_arquivo = uniqid() . '_' . basename($foto_file['name']);
    $caminho_arquivo = $upload_dir . $nome_arquivo;

    // Tenta mover o arquivo para a pasta de uploads
    if (move_uploaded_file($foto_file['tmp_name'], $caminho_arquivo)) {
        return $caminho_arquivo; // Retorna o caminho se o upload foi bem sucedido
    }
    return false; // Retorna falso se falhou
}

// --- VERIFICA A AÇÃO A SER TOMADA ---

// Ação de Cadastrar (Create)
if (isset($_POST['acao']) && $_POST['acao'] == 'cadastrar') {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $data_colheita = $_POST['data_colheita'];
    $caminho_foto = '';

    // Verifica se uma foto foi enviada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $caminho_foto = uploadFoto($_FILES['foto']);
    }

    $sql = "INSERT INTO produtos (nome, preco, data_colheita, foto) VALUES (:nome, :preco, :data, :foto)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':preco' => $preco,
        ':data' => $data_colheita,
        ':foto' => $caminho_foto
    ]);
}

// Ação de Editar (Update)
if (isset($_POST['acao']) && $_POST['acao'] == 'editar') {
    $id_produto = $_POST['id_produto'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $data_colheita = $_POST['data_colheita'];
    $caminho_foto = $_POST['foto_antiga']; // Mantém a foto antiga por padrão

    // Se uma nova foto foi enviada, faz o upload e apaga a antiga
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $nova_foto = uploadFoto($_FILES['foto']);
        if ($nova_foto) {
            // Se a foto antiga existir e não for um placeholder, apaga do servidor
            if (file_exists($caminho_foto)) {
                unlink($caminho_foto);
            }
            $caminho_foto = $nova_foto;
        }
    }

    $sql = "UPDATE produtos SET nome = :nome, preco = :preco, data_colheita = :data, foto = :foto WHERE id_produto = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':preco' => $preco,
        ':data' => $data_colheita,
        ':foto' => $caminho_foto,
        ':id' => $id_produto
    ]);
}

// Ação de Excluir (Delete)
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir') {
    $id_produto = $_GET['id'];

    // 1. Pega o caminho da foto para poder apagar o arquivo
    $stmt_select = $conn->prepare("SELECT foto FROM produtos WHERE id_produto = :id");
    $stmt_select->execute([':id' => $id_produto]);
    $produto = $stmt_select->fetch();

    if ($produto && file_exists($produto['foto'])) {
        unlink($produto['foto']); // Apaga o arquivo da foto do servidor
    }

    // 2. Apaga o registro do banco de dados
    $stmt_delete = $conn->prepare("DELETE FROM produtos WHERE id_produto = :id");
    $stmt_delete->execute([':id' => $id_produto]);
}

// Redireciona de volta para a página principal do admin
header("Location: admin.php");
exit();
?>