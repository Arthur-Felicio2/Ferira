<?php
include "funcs.php";
$conn = conecta();

// --- A função de upload de foto foi REMOVIDA, pois não é mais necessária. ---

// --- VERIFICA A AÇÃO A SER TOMADA ---

// Ação de Cadastrar (Create)
if (isset($_POST['acao']) && $_POST['acao'] == 'cadastrar') {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $data_colheita = $_POST['data_colheita'];
    // Pega o link diretamente do formulário
    $foto_url = $_POST['foto']; 

    $sql = "INSERT INTO produtos (nome, preco, data_colheita, foto) VALUES (:nome, :preco, :data, :foto)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':preco' => $preco,
        ':data' => $data_colheita,
        ':foto' => $foto_url // Salva o link no banco de dados
    ]);
}

// Ação de Editar (Update)
if (isset($_POST['acao']) && $_POST['acao'] == 'editar') {
    $id_produto = $_POST['id_produto'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $data_colheita = $_POST['data_colheita'];
    // Pega o link novo (ou o antigo, se não for alterado) diretamente do formulário
    $foto_url = $_POST['foto'];

    // Lógica de upload e exclusão de arquivo foi removida.
    
    $sql = "UPDATE produtos SET nome = :nome, preco = :preco, data_colheita = :data, foto = :foto WHERE id_produto = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':preco' => $preco,
        ':data' => $data_colheita,
        ':foto' => $foto_url,
        ':id' => $id_produto
    ]);
}

// Ação de Excluir (Delete)
if (isset($_GET['acao']) && $_GET['acao'] == 'excluir') {
    $id_produto = $_GET['id'];

    // A lógica para apagar o arquivo do servidor foi REMOVIDA.
    // Nós não gerenciamos o arquivo, apenas o link.

    // Apenas apaga o registro do banco de dados
    $stmt_delete = $conn->prepare("DELETE FROM produtos WHERE id_produto = :id");
    $stmt_delete->execute([':id' => $id_produto]);
}

// Redireciona de volta para a página principal do admin
header("Location: admin.php");
exit();
?>