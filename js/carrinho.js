// Inicializa o carrinho com base no localStorage
document.addEventListener("DOMContentLoaded", () => {
  atualizarVisibilidadeBotaoCarrinho();
});

// Adiciona produto ao carrinho
function adicionarAoCarrinho(codigo, produto, qtd = 1) {
  let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

  // monta o item corretamente
  const item = {
    ...produto,
    codigo,
    preco: parseFloat(produto.preco),
    qtd: parseInt(qtd)
  };

  // busca pelo código
  const index = carrinho.findIndex(p => p.codigo === item.codigo);

  if (index !== -1) {
    carrinho[index].qtd += item.qtd;
  } else {
    carrinho.push(item); // ✅ agora sim, com preco numérico e código garantido
  }

  localStorage.setItem("carrinho", JSON.stringify(carrinho));
  atualizarVisibilidadeBotaoCarrinho();

  // animação
  const btn = document.getElementById("btnCarrinho");
  btn.classList.remove("btn-animate-pulse");
  void btn.offsetWidth;
  btn.classList.add("btn-animate-pulse");
}



// Exibe o carrinho no modal
function abrirCarrinho() {
  atualizarCarrinhoUI();
  const modalElement = document.getElementById("modalCarrinho");
  const carrinhoModal = bootstrap.Modal.getOrCreateInstance(modalElement);
  carrinhoModal.show();
}


// Remove item
function removerDoCarrinho(index) {
  let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
  carrinho.splice(index, 1);
  localStorage.setItem("carrinho", JSON.stringify(carrinho));
  atualizarCarrinhoUI();
  atualizarVisibilidadeBotaoCarrinho();
}

function alterarQtd(index, delta) {
  let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

  if (!carrinho[index]) return;

  carrinho[index].qtd += delta;
  if (carrinho[index].qtd <= 0) {
    // remove se ficar ≤0
    carrinho.splice(index, 1);
  }
  localStorage.setItem("carrinho", JSON.stringify(carrinho));

  // atualiza UI e badge
  atualizarCarrinhoUI();
  atualizarVisibilidadeBotaoCarrinho();
}

function atualizarCarrinhoUI() {
  const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
  const container = document.getElementById("carrinhoItens");
  const totalSpan = document.getElementById("carrinhoTotal");

  container.innerHTML = "";
  let total = 0;

  carrinho.forEach((item, i) => {
    const subtotal = item.preco * item.qtd;
    total += subtotal;

    container.innerHTML += `
        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
          <div class="me-3 flex-grow-1">
            <strong>${item.codigo} - ${item.nome}</strong><br>
            <small>R$ ${item.preco.toFixed(2).replace('.', ',')} cada</small>
          </div>

          <div class="d-flex align-items-center gap-1">
            <button class="btn btn-sm btn-outline-secondary"
                    onclick="alterarQtd(${i}, -1)">−</button>

            <span class="px-2" id="qtd-${i}">${item.qtd}</span>

            <button class="btn btn-sm btn-outline-secondary"
                    onclick="alterarQtd(${i}, 1)">+</button>
          </div>

          <div class="ms-3 text-end" style="width:110px;">
            <small>Subtotal:</small><br>
            <strong>R$ ${(item.preco * item.qtd).toFixed(2).replace('.', ',')}</strong>
          </div>

          <button class="btn btn-sm btn-outline-danger ms-2"
                  onclick="removerDoCarrinho(${i})">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      `;

  });

  totalSpan.textContent = total.toFixed(2).replace('.', ',');
}



// Mostra ou esconde o botão do carrinho
function atualizarVisibilidadeBotaoCarrinho() {
  const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
  const btn = document.getElementById("btnCarrinho");
  const badge = document.getElementById("carrinhoQtdBadge");

  const totalQtd = carrinho.reduce((acc, item) => acc + item.qtd, 0);

  if (totalQtd > 0) {
    btn.style.display = "block";
    badge.textContent = totalQtd;
  } else {
    btn.style.display = "none";
  }
}

function limparCarrinho() {
  localStorage.removeItem("carrinho");
  atualizarCarrinhoUI();
  atualizarVisibilidadeBotaoCarrinho();


}
