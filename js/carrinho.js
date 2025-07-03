document.addEventListener("DOMContentLoaded", () => {
  atualizarVisibilidadeBotaoCarrinho();
});

function obterUsuarioAtual() {
  return localStorage.getItem("usuario_logado") || "anonimo";
}

function obterCarrinho() {
  const usuario = obterUsuarioAtual();
  return JSON.parse(localStorage.getItem("carrinho_" + usuario)) || [];
}

function salvarCarrinho(carrinho) {
  const usuario = obterUsuarioAtual();
  localStorage.setItem("carrinho_" + usuario, JSON.stringify(carrinho));
}

function adicionarAoCarrinho(codigo, produto, qtd = 1) {
  let carrinho = obterCarrinho();

  const item = {
    ...produto,
    codigo,
    preco: parseFloat(produto.preco),
    estoque: parseInt(produto.estoque),
    qtd: parseInt(qtd)
  };

  const index = carrinho.findIndex(p => p.codigo === item.codigo);

  if (index !== -1) {
    const novaQtd = carrinho[index].qtd + item.qtd;
    if (novaQtd <= item.estoque) {
      carrinho[index].qtd = novaQtd;
    }
  } else {
    if (item.qtd <= item.estoque) {
      carrinho.push(item);
    }
  }

  salvarCarrinho(carrinho);
  atualizarVisibilidadeBotaoCarrinho();

  const btn = document.getElementById("btnCarrinho");
  btn.classList.remove("btn-animate-pulse");
  void btn.offsetWidth;
  btn.classList.add("btn-animate-pulse");
}

function abrirCarrinho() {
  atualizarCarrinhoUI();
  const modalElement = document.getElementById("modalCarrinho");
  const carrinhoModal = bootstrap.Modal.getOrCreateInstance(modalElement);
  carrinhoModal.show();
}

function removerDoCarrinho(index) {
  let carrinho = obterCarrinho();
  carrinho.splice(index, 1);
  salvarCarrinho(carrinho);
  atualizarCarrinhoUI();
  atualizarVisibilidadeBotaoCarrinho();
}

function alterarQtd(index, delta) {
  let carrinho = obterCarrinho();

  if (!carrinho[index]) return;

  const novaQtd = carrinho[index].qtd + delta;

  if (novaQtd > carrinho[index].estoque) {
    return;
  }

  if (novaQtd <= 0) {
    carrinho.splice(index, 1);
  } else {
    carrinho[index].qtd = novaQtd;
  }

  salvarCarrinho(carrinho);
  atualizarCarrinhoUI();
  atualizarVisibilidadeBotaoCarrinho();
}

function atualizarCarrinhoUI() {
  const carrinho = obterCarrinho();
  const container = document.getElementById("carrinhoItens");
  const totalSpan = document.getElementById("carrinhoTotal");

  container.innerHTML = "";
  let total = 0;

  carrinho.forEach((item, i) => {
    const subtotal = item.preco * item.qtd;
    total += subtotal;

    const disableMais = item.qtd >= item.estoque ? 'disabled' : '';

    container.innerHTML += `
      <div class="d-flex justify-content-between align-items-center border-bottom py-2">
        <div class="me-3 flex-grow-1">
          <strong>${item.codigo} - ${item.nome}</strong><br>
          <small>R$ ${item.preco.toFixed(2).replace('.', ',')} cada</small>
        </div>

        <div class="d-flex align-items-center gap-1">
          <button class="btn btn-sm btn-outline-secondary"
                  onclick="alterarQtd(${i}, -1)">−</button>

          <span class="px-2">${item.qtd}</span>

          <button class="btn btn-sm btn-outline-secondary" 
                  onclick="alterarQtd(${i}, 1)" ${disableMais}>+</button>
        </div>

        <div class="ms-3 text-end" style="width:110px;">
          <small>Subtotal:</small><br>
          <strong>R$ ${subtotal.toFixed(2).replace('.', ',')}</strong>
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

function atualizarVisibilidadeBotaoCarrinho() {
  const carrinho = obterCarrinho();
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
  const usuario = obterUsuarioAtual();
  localStorage.removeItem("carrinho_" + usuario);
  atualizarCarrinhoUI();
  atualizarVisibilidadeBotaoCarrinho();
}
