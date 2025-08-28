<?php
include "funcs.php";

// VERIFICAÇÃO DE ADMIN CORRIGIDA E MAIS ROBUSTA
if (!isset($_SESSION['usuario']['admin']) || $_SESSION['usuario']['admin'] != true) {
    die("Acesso não autorizado.");
}

$conn = conecta();

/**
 * Processa o upload de uma imagem, salva na pasta e retorna o caminho.
 * @param array $file_data Dados do arquivo vindo de $_FILES.
 * @return string|false O caminho do arquivo salvo ou false em caso de falha.
 */
function uploadImagem($file_data)
{
    if ($file_data['error'] !== UPLOAD_ERR_OK) {
        return false; // Retorna falso se houver erro de upload
    }

    $upload_dir = 'imagem/'; // Garanta que esta pasta exista e tenha permissão de escrita
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Gera um nome único para o arquivo para evitar sobreposição
    $nome_arquivo = uniqid('', true) . '_' . basename($file_data['name']);
    $caminho_arquivo = $upload_dir . $nome_arquivo;

    if (move_uploaded_file($file_data['tmp_name'], $caminho_arquivo)) {
        return $caminho_arquivo; // Sucesso
    }

    return false; // Falha ao mover o arquivo
}


// --- VERIFICA A AÇÃO A SER TOMADA ---

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

// Ação de Cadastrar (Create)
if ($acao == 'cadastrar') {
    // 1. Coletar os dados corretos do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $valor_unitario = $_POST['valor_unitario'];
    $qtde_estoque = $_POST['qtde_estoque'];
    $caminho_imagem = null;

    // 2. Processar o upload da imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $caminho_imagem = uploadImagem($_FILES['imagem']);
    }

    // 3. Inserir na tabela correta ('produto') com as colunas corretas
    $sql = "INSERT INTO produto (nome, descricao, valor_unitario, qtde_estoque, imagem, excluido) VALUES (:nome, :descricao, :valor, :estoque, :imagem, false)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':valor' => $valor_unitario,
        ':estoque' => $qtde_estoque,
        ':imagem' => $caminho_imagem
    ]);
}

// Ação de Editar (Update)
if ($acao == 'editar') {
    // 1. Coletar os dados corretos do formulário
    $id_produto = $_POST['id_produto'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $valor_unitario = $_POST['valor_unitario'];
    $qtde_estoque = $_POST['qtde_estoque'];
    $caminho_imagem = $_POST['imagem_antiga']; // Manter a imagem antiga por padrão

    // 2. Se uma NOVA imagem foi enviada, processa o upload
    if (isset($_FILES['imagem']) && !empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] == 0) {
        $nova_imagem = uploadImagem($_FILES['imagem']);
        if ($nova_imagem) {
            // Se a imagem antiga existia e era diferente, apaga ela do servidor
            if ($caminho_imagem && file_exists($caminho_imagem)) {
                unlink($caminho_imagem);
            }
            $caminho_imagem = $nova_imagem; // Atualiza para o caminho da nova imagem
        }
    }

    // 3. Atualizar a tabela correta ('produto') com as colunas corretas
    $sql = "UPDATE produto SET nome = :nome, descricao = :descricao, valor_unitario = :valor, qtde_estoque = :estoque, imagem = :imagem WHERE id_produto = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':valor' => $valor_unitario,
        ':estoque' => $qtde_estoque,
        ':imagem' => $caminho_imagem,
        ':id' => $id_produto
    ]);
}

// Ação de Excluir (Delete Lógico)
if ($acao == 'excluir') {
    $id_produto = $_GET['id'];

    // Em vez de DELETAR, vamos ATUALIZAR o campo 'excluido' para true
    $sql = "UPDATE produto SET excluido = true WHERE id_produto = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id_produto]);
}

// Redireciona de volta para a página principal do admin após qualquer ação
header("Location: admin.php");
exit();