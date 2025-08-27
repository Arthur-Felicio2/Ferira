document.addEventListener("DOMContentLoaded", () => {
  const carrinho = {};
  const listaProdutos = document.querySelector(".lista-produtos");
  const carrinhoItensContainer = document.getElementById("carrinho-itens");
  const valorTotalEl = document.getElementById("valor-total");
  const btnComprar = document.getElementById("btn-comprar");

  // --- FUNÇÕES (permanecem as mesmas) ---
  function atualizarCarrinho(produtoId, nome, preco, quantidade) {
    if (quantidade > 0) {
      carrinho[produtoId] = { nome, preco, quantidade };
    } else {
      delete carrinho[produtoId];
    }
    renderizarCarrinho();
    calcularTotal();
  }

  function renderizarCarrinho() {
    carrinhoItensContainer.innerHTML = "";
    const idsDosProdutos = Object.keys(carrinho);
    if (idsDosProdutos.length === 0) {
      carrinhoItensContainer.innerHTML = "<p>Sua cesta está vazia.</p>";
      return;
    }
    const listaUl = document.createElement("ul");
    idsDosProdutos.forEach((id) => {
      const item = carrinho[id];
      const itemLi = document.createElement("li");
      const subtotal = item.preco * item.quantidade;
      itemLi.innerHTML = `
                <span>${item.quantidade}x ${item.nome}</span>
                <span>R$ ${subtotal.toFixed(2).replace(".", ",")}</span>
            `;
      listaUl.appendChild(itemLi);
    });
    carrinhoItensContainer.appendChild(listaUl);
  }

  function calcularTotal() {
    let total = 0;
    for (const id in carrinho) {
      total += carrinho[id].preco * carrinho[id].quantidade;
    }
    valorTotalEl.textContent = `R$ ${total.toFixed(2).replace(".", ",")}`;
  }

  // --- EVENT LISTENERS ---

  // Monitora mudanças nos inputs de quantidade (permanece o mesmo)
  if (listaProdutos) {
    listaProdutos.addEventListener("change", (event) => {
      if (event.target.classList.contains("input-qtd")) {
        const input = event.target;
        const produtoId = input.dataset.id;
        const nome = input.dataset.nome;
        const preco = parseFloat(input.dataset.preco);
        const quantidade = parseInt(input.value);
        atualizarCarrinho(produtoId, nome, preco, quantidade);
      }
    });
  }

  // Monitora o clique no botão "Comprar" (MODIFICADO)
  if (btnComprar) {
    btnComprar.addEventListener("click", () => {
      // >>> INÍCIO DA MODIFICAÇÃO <<<
      // 1. Verifica se a variável 'isLoggedIn' existe e é verdadeira
      //    (Essa variável será criada no arquivo vendas.php)
      if (typeof isLoggedIn === "undefined" || !isLoggedIn) {
        alert("Você precisa fazer login para finalizar a compra!");
        window.location.href = "login.php"; // Redireciona para o login
        return; // Para a execução da função
      }
      // >>> FIM DA MODIFICAÇÃO <<<

      // O resto da lógica continua igual
      if (Object.keys(carrinho).length === 0) {
        alert("Por favor, adicione pelo menos um item à cesta!");
        return;
      }

      // Lógica de finalização (pode ser aprimorada no futuro)
      alert("Obrigado pela sua compra! (Implementação do checkout pendente)");

      // Limpa o carrinho e a tela para a próxima compra
      Object.keys(carrinho).forEach((key) => delete carrinho[key]);
      renderizarCarrinho();
      calcularTotal();
      document
        .querySelectorAll(".input-qtd")
        .forEach((input) => (input.value = 0));
    });
  }
});
