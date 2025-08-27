<?php

include "funcs.php";
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['admin'] != 1) {
    $_SESSION['mensagem'] = "Acesso negado. Apenas administradores podem acessar esta área.";
    header('Location: login.php');
    exit();
}
$conn = conecta();

/**
 * Processa o upload de uma foto, salva na pasta e retorna o caminho completo.
 * @param array $foto_file Dados do arquivo vindo de $_FILES['foto'].
 * @return string|false O caminho do arquivo salvo ou false em caso de falha.
 */
function uploadFoto($foto_file)
{
    // Usando a pasta 'imagem' no singular, como você definiu.
    $upload_dir = 'imagem/';
    // Gera um nome único para o arquivo para evitar sobreposição.
    $nome_arquivo = time() . '_' . basename($foto_file['name']);
    $caminho_arquivo = $upload_dir . $nome_arquivo;

    // Tenta mover o arquivo temporário para o destino final.
    if (move_uploaded_file($foto_file['tmp_name'], $caminho_arquivo)) {
        return $caminho_arquivo; // Retorna o caminho completo.
    }

    // Em caso de falha, retorna false.
    return false;
}

// --- VERIFICA A AÇÃO A SER TOMADA ---

// Ação de Cadastrar (Create)
if (isset($_POST['acao']) && $_POST['acao'] == 'cadastrar') {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $data_colheita = $_POST['data_colheita'];
    $caminho_foto = ''; // Por padrão, não há foto.

    // Verifica se um arquivo de foto foi enviado e não teve erros.
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
    $caminho_foto = $_POST['foto_antiga']; // Mantém a foto antiga por padrão.

    // Se uma NOVA foto foi enviada, processa o upload.
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $nova_foto = uploadFoto($_FILES['foto']);
        if ($nova_foto) {
            // Se a foto antiga existia, apaga ela do servidor.
            if ($caminho_foto && file_exists($caminho_foto)) {
                unlink($caminho_foto);
            }
            $caminho_foto = $nova_foto; // Atualiza para o caminho da nova foto.
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

    // 1. Pega o caminho da foto no banco para poder apagar o arquivo.
    $stmt_select = $conn->prepare("SELECT foto FROM produtos WHERE id_produto = :id");
    $stmt_select->execute([':id' => $id_produto]);
    $produto = $stmt_select->fetch();

    // 2. Se o produto tinha uma foto e o arquivo existe, apaga o arquivo do servidor.
    if ($produto && !empty($produto['foto']) && file_exists($produto['foto'])) {
        unlink($produto['foto']);
    }

    // 3. Apaga o registro do produto do banco de dados.
    $stmt_delete = $conn->prepare("DELETE FROM produtos WHERE id_produto = :id");
    $stmt_delete->execute([':id' => $id_produto]);
}

// RESTAURADO: Redireciona de volta para a página principal do admin após qualquer ação.
header("Location: admin.php");
exit();
