// Inicializa o carrinho com base no localStorage
document.addEventListener("DOMContentLoaded", () => {
  atualizarVisibilidadeBotaoCarrinho();
});

// Adiciona produto ao carrinho
function adicionarAoCarrinho(produto) {
  const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
  carrinho.push(produto);
  localStorage.setItem("carrinho", JSON.stringify(carrinho));
  atualizarVisibilidadeBotaoCarrinho();

  // 1. Shake no botão do carrinho
  const btn = document.getElementById("btnCarrinho");
  btn.classList.remove("btn-animate-shake");
  void btn.offsetWidth;
  btn.classList.add("btn-animate-shake");

  // 2. Ícone flutuando até o botão
  const icon = document.createElement("div");
  icon.classList.add("floating-icon", "bi", "bi-cart-check-fill");
  document.body.appendChild(icon);

  // Posição inicial: centro do botão clicado (último botão de carrinho da tela)
  const origem = event.target.getBoundingClientRect();
  icon.style.left = `${origem.left + origem.width / 2}px`;
  icon.style.top = `${origem.top}px`;

  // Destino: botão do carrinho
  const destino = btn.getBoundingClientRect();
  setTimeout(() => {
    icon.style.transform = `translate(${destino.left - origem.left}px, ${destino.top - origem.top}px) scale(0.5)`;
    icon.style.opacity = 0;
  }, 10);

  // Remover após a animação
  setTimeout(() => {
    icon.remove();
  }, 800);
}

// Exibe o carrinho no modal
function abrirCarrinho() {
  const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
  const container = document.getElementById("carrinhoItens");
  const totalSpan = document.getElementById("carrinhoTotal");

  container.innerHTML = "";
  let total = 0;

  carrinho.forEach((item, i) => {
    total += parseFloat(item.preco);
    container.innerHTML += `
      <div class="d-flex justify-content-between align-items-center border-bottom py-2">
        <div>
          <strong>${item.nome}</strong><br>
          <small>R$ ${parseFloat(item.preco).toFixed(2).replace(".", ",")}</small>
        </div>
        <button class="btn btn-sm btn-outline-danger" onclick="removerDoCarrinho(${i})">Remover</button>
      </div>
    `;
  });

  totalSpan.textContent = total.toFixed(2).replace(".", ",");

  new bootstrap.Modal(document.getElementById("modalCarrinho")).show();
}

// Remove item
function removerDoCarrinho(index) {
  let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
  carrinho.splice(index, 1);
  localStorage.setItem("carrinho", JSON.stringify(carrinho));
  abrirCarrinho();
  atualizarVisibilidadeBotaoCarrinho();
}

// Mostra ou esconde o botão do carrinho
function atualizarVisibilidadeBotaoCarrinho() {
  const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
  const btn = document.getElementById("btnCarrinho");
  const badge = document.getElementById("carrinhoQtdBadge");

  const qtd = carrinho.length;

  if (qtd > 0) {
    btn.style.display = "block";
    badge.textContent = qtd;
  } else {
    btn.style.display = "none";
  }
}
