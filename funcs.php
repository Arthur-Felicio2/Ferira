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

// seu código da função conecta() já existente fica aqui em cima...

/**
 * Função para exibir uma imagem de forma segura e com um fallback.
 * Se o link do BD estiver quebrado ou bloqueado, mostra uma imagem de erro.
 *
 * @param string $url O link da imagem vindo do banco de dados.
 * @param string $alt_text O texto alternativo para a imagem (geralmente o nome do produto).
 * @param string $classe_css A classe CSS para estilizar a imagem.
 * @return string A tag HTML <img> completa.
 */
function exibirImagem($url, $alt_text, $classe_css = 'foto-produto') {
    // Caminho para a sua imagem de erro padrão
    $imagem_erro = 'error.png'; 

    // Verifica se a URL do banco de dados não está vazia
    if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
        // Se a URL for válida, gera a tag <img> com o evento 'onerror'.
        // O 'onerror' é um truque de JavaScript: se o 'src' principal falhar,
        // ele tenta carregar a imagem de erro.
        $url_segura = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $alt_seguro = htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8');
        
        return '<img 
                    src="' . $url_segura . '" 
                    alt="' . $alt_seguro . '" 
                    class="' . $classe_css . '" 
                    onerror="this.onerror=null; this.src=\'' . $imagem_erro . '\';"
                >';
    } else {
        // Se a URL do banco de dados estiver vazia ou for inválida, mostra a imagem de erro diretamente.
        $alt_seguro = htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8');
        return '<img 
                    src="' . $imagem_erro . '" 
                    alt="Imagem indisponível para ' . $alt_seguro . '" 
                    class="' . $classe_css . '"
                >';
    }
}