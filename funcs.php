<?php

function conecta($params = "")
{
    $params = "pgsql:host=localhost; port=5432; dbname=Feira; user=postgres; password=postgres";
    try {
        $varConn = new PDO($params);
        return $varConn;
    } catch (PDOException $e) {
        echo "não conectou";
        echo $e->getMessage();
        exit;
    }
};

/**
 * Função CORRIGIDA para exibir uma imagem salva localmente.
 *
 * @param string $caminho O caminho do arquivo salvo no banco de dados (ex: 'imagem/foto.jpg').
 * @param string $alt_text O texto alternativo para a imagem.
 * @param string $classe_css A classe CSS para estilizar a imagem.
 * @return string A tag HTML <img> completa.
 */
function exibirImagem($caminho, $alt_text, $classe_css = 'foto-produto')
{
    // Caminho para a sua imagem de erro padrão
    $imagem_erro = 'error.png';

    // MUDANÇA CRÍTICA:
    // Agora, verificamos se o caminho não está vazio E se o arquivo realmente existe no servidor.
    if (!empty($caminho) && file_exists($caminho)) {
        // Se o arquivo existe, mostra a imagem.
        $caminho_seguro = htmlspecialchars($caminho, ENT_QUOTES, 'UTF-8');
        $alt_seguro = htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8');

        return '<img 
                    src="' . $caminho_seguro . '" 
                    alt="' . $alt_seguro . '" 
                    class="' . $classe_css . '"
                >';
    } else {
        // Se o caminho estiver vazio ou o arquivo não for encontrado, mostra a imagem de erro.
        $alt_seguro = htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8');
        return '<img 
                    src="' . $imagem_erro . '" 
                    alt="Imagem indisponível para ' . $alt_seguro . '" 
                    class="' . $classe_css . '"
                >';
    }
}
