document.addEventListener('DOMContentLoaded', () => {

    // Objeto para armazenar os itens do carrinho. A chave será o ID do produto.
    const carrinho = {};

    // Elementos do DOM que vamos manipular
    const listaProdutos = document.querySelector('.lista-produtos');
    const carrinhoItensContainer = document.getElementById('carrinho-itens');
    const valorTotalEl = document.getElementById('valor-total');
    const btnComprar = document.getElementById('btn-comprar');

    // --- FUNÇÕES PRINCIPAIS ---

    /**
     * Atualiza o carrinho (adiciona, remove ou modifica a quantidade de um produto)
     */
    function atualizarCarrinho(produtoId, nome, preco, quantidade) {
        if (quantidade > 0) {
            carrinho[produtoId] = { nome, preco, quantidade };
        } else {
            // Se a quantidade for 0, remove o item do carrinho
            delete carrinho[produtoId];
        }
        
        // Atualiza a exibição do carrinho e o total
        renderizarCarrinho();
        calcularTotal();
    }

    /**
     * Desenha os itens do carrinho na tela
     */
    function renderizarCarrinho() {
        // Limpa a lista atual
        carrinhoItensContainer.innerHTML = '';

        // Pega as chaves (IDs) dos produtos no carrinho
        const idsDosProdutos = Object.keys(carrinho);

        if (idsDosProdutos.length === 0) {
            carrinhoItensContainer.innerHTML = '<p>Sua cesta está vazia.</p>';
            return;
        }

        const listaUl = document.createElement('ul');
        idsDosProdutos.forEach(id => {
            const item = carrinho[id];
            const itemLi = document.createElement('li');
            
            const subtotal = item.preco * item.quantidade;
            
            itemLi.innerHTML = `
                <span>${item.quantidade}x ${item.nome}</span>
                <span>R$ ${subtotal.toFixed(2).replace('.', ',')}</span>
            `;
            listaUl.appendChild(itemLi);
        });

        carrinhoItensContainer.appendChild(listaUl);
    }

    /**
     * Calcula e exibe o valor total da compra
     */
    function calcularTotal() {
        let total = 0;
        for (const id in carrinho) {
            total += carrinho[id].preco * carrinho[id].quantidade;
        }

        valorTotalEl.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
    }

    /**
     * Limpa tudo para uma nova compra
     */
    function resetarCompra() {
        // 1. Limpa o objeto do carrinho
        for (const key in carrinho) {
            delete carrinho[key];
        }

        // 2. Atualiza a exibição do carrinho e total
        renderizarCarrinho();
        calcularTotal();

        // 3. Zera todos os campos de input de quantidade na página de produtos
        const todosInputs = document.querySelectorAll('.input-qtd');
        todosInputs.forEach(input => {
            input.value = 0;
        });

        // 4. Exibe uma mensagem de sucesso
        alert('Compra finalizada com sucesso! Pronto para o próximo cliente.');
    }


    // --- EVENT LISTENERS (MONITORAMENTO DE AÇÕES DO USUÁRIO) ---

    // Monitora mudanças nos inputs de quantidade
    listaProdutos.addEventListener('change', (event) => {
        // Verifica se o evento foi em um input de quantidade
        if (event.target.classList.contains('input-qtd')) {
            const input = event.target;
            const produtoId = input.dataset.id;
            const nome = input.dataset.nome;
            const preco = parseFloat(input.dataset.preco);
            const quantidade = parseInt(input.value);

            atualizarCarrinho(produtoId, nome, preco, quantidade);
        }
    });

    // Monitora o clique no botão "Comprar"
    btnComprar.addEventListener('click', () => {
        // Verifica se o carrinho não está vazio
        if (Object.keys(carrinho).length === 0) {
            alert('Por favor, adicione pelo menos um item à cesta!');
            return;
        }
        resetarCompra();
    });

});