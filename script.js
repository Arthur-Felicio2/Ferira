document.addEventListener('DOMContentLoaded', function () {
  const inputsQtd = document.querySelectorAll('.input-qtd');
  const carrinhoItensDiv = document.getElementById('carrinho-itens');
  const valorTotalSpan = document.getElementById('valor-total');
  const btnComprar = document.getElementById('btn-comprar');

  let carrinho = {};

  // Atualiza o carrinho quando a quantidade de um produto muda
  inputsQtd.forEach(input => {
    input.addEventListener('change', function () {
      const id = this.dataset.id;
      const nome = this.dataset.nome;
      const preco = parseFloat(this.dataset.preco);
      const quantidade = parseInt(this.value);

      if (quantidade > 0) {
        carrinho[id] = { nome, preco, quantidade };
      } else {
        delete carrinho[id];
      }

      atualizarVisualizacaoCarrinho();
    });
  });

  function atualizarVisualizacaoCarrinho() {
    carrinhoItensDiv.innerHTML = '';
    let total = 0;
    const itens = Object.values(carrinho);

    if (itens.length === 0) {
      carrinhoItensDiv.innerHTML = '<p>Sua cesta está vazia.</p>';
    } else {
      itens.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.classList.add('carrinho-item');
        itemDiv.innerText = `${item.quantidade}x ${item.nome} - R$ ${(item.preco * item.quantidade).toFixed(2)}`;
        carrinhoItensDiv.appendChild(itemDiv);
        total += item.preco * item.quantidade;
      });
    }

    valorTotalSpan.innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;
  }

  // AÇÃO DO BOTÃO COMPRAR - CORREÇÃO PRINCIPAL AQUI
  btnComprar.addEventListener('click', function () {
    // A variável 'isLoggedIn' vem do script inline no vendas.php
    if (!isLoggedIn) {
      alert("Login não funcionou! Por favor, faça o login para continuar sua compra.");
      // Redireciona o usuário para a página de login
      window.location.href = 'login.php';
      return; // Para a execução
    }

    const itensNoCarrinho = Object.keys(carrinho).length;
    if (itensNoCarrinho === 0) {
      alert("Sua cesta está vazia. Adicione produtos antes de comprar.");
      return;
    }

    // Se o usuário está logado e o carrinho não está vazio, prossiga.
    // Aqui viria a lógica para enviar os dados do carrinho para o servidor (PHP)
    // Por enquanto, vamos apenas mostrar uma mensagem de sucesso.
    console.log("Dados do carrinho para enviar:", carrinho);
    alert("Compra finalizada com sucesso! (Simulação)");

    // Limpar carrinho após a compra
    carrinho = {};
    inputsQtd.forEach(input => input.value = 0);
    atualizarVisualizacaoCarrinho();
  });
});