<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


function conecta()
{
    $params = "pgsql:host=localhost; port=5432; dbname=Comercio; user=postgres; password=postgres";
    try {
        $varConn = new PDO($params);
        $varConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilitar exceções é uma boa prática
        return $varConn;
    } catch (PDOException $e) {
        error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
        die("Não foi possível conectar ao banco de dados. Tente novamente mais tarde.");
    }
}


function ExecutaSQL($conn, $sql, $params = [])
{
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount() > 0;
}

function ValorSQL($conn, $sql, $params = [])
{
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

function TrazLinhaSQL($conn, $sql, $params = [])
{
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function exibirImagem($caminho, $alt_text, $classe_css = 'foto-produto')
{
    $imagem_erro = 'error.png';
    if (!empty($caminho) && file_exists($caminho)) {
        $caminho_seguro = htmlspecialchars($caminho, ENT_QUOTES, 'UTF-8');
        $alt_seguro = htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8');
        return '<img src="' . $caminho_seguro . '" alt="' . $alt_seguro . '" class="' . $classe_css . '">';
    } else {
        $alt_seguro = htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8');
        return '<img src="' . $imagem_erro . '" alt="Imagem indisponível para ' . $alt_seguro . '" class="' . $classe_css . '">';
    }
}
