<?php
require_once '../php/conexao.php';

// Buscar todos os clientes
$stmt = $conn->query("SELECT id_customer, name, cpf FROM customers ORDER BY name");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <div class="card user-modal-content p-4">
        <h4 class="mb-4">Finalizar Pedido</h4>

        <!-- Seleção de Cliente -->
        <div class="mb-4">
            <label for="clienteSelect" class="form-label">Selecione o Cliente:</label>
            <select id="clienteSelect" class="form-select">
                <option value="">-- Escolha um cliente --</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_customer'] ?>">
                        <?= htmlspecialchars($cliente['name']) ?> - CPF: <?= $cliente['cpf'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Lista de produtos do carrinho (JS) -->
        <div class="mb-4">
            <h5>Produtos no Carrinho</h5>
            <div id="checkoutCarrinho"></div>
            <p class="mt-3 text-end">Total: R$ <strong id="checkoutTotal">0,00</strong></p>
        </div>

        <!-- Botão finalizar -->
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-danger me-auto" onclick="limparCheckout()">
                <i class="bi bi-trash"></i> Limpar Carrinho
            </button>
            <button class="btn btn-success" onclick="enviarPedido()">Finalizar Compra</button>
        </div>
    </div>
</div>

<!-- <script src="../js/carrinho.js"></script> -->

<script>
    function renderCheckoutCarrinho() {
        const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
        const container = document.getElementById("checkoutCarrinho");
        const totalSpan = document.getElementById("checkoutTotal");

        container.innerHTML = "";
        let total = 0;

        carrinho.forEach((item, i) => {
            const subtotal = item.preco * item.qtd;
            total += subtotal;

            container.innerHTML += `
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                  <div class="flex-grow-1 me-2">
                    <strong>${item.nome}</strong><br>
                    <small>R$ ${item.preco.toFixed(2).replace('.', ',')} cada</small>
                  </div>

                  <div class="d-flex align-items-center gap-1">
                    <button class="btn btn-sm btn-outline-secondary" onclick="alterarQtdCheckout(${i}, -1)">−</button>
                    <span class="px-2">${item.qtd}</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="alterarQtdCheckout(${i}, 1)">+</button>
                  </div>

                  <div class="ms-3 text-end" style="width:110px;">
                    <small>Subtotal:</small><br>
                    <strong>R$ ${subtotal.toFixed(2).replace('.', ',')}</strong>
                  </div>
                </div>
            `;
        });


        totalSpan.textContent = total.toFixed(2).replace('.', ',');
    }

    function alterarQtdCheckout(index, delta) {
        let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

        if (!carrinho[index]) return;

        carrinho[index].qtd += delta;

        if (carrinho[index].qtd <= 0) {
            carrinho.splice(index, 1);
        }

        localStorage.setItem("carrinho", JSON.stringify(carrinho));
        renderCheckoutCarrinho();
        atualizarVisibilidadeBotaoCarrinho();
    }

    function limparCheckout() {
        limparCarrinho();
        renderCheckoutCarrinho();
    }

    function enviarPedido() {
        const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
        const id_cliente = document.getElementById("clienteSelect").value;

        if (!id_cliente) {
            alert("Selecione um cliente.");
            return;
        }

        if (carrinho.length === 0) {
            alert("Carrinho vazio.");
            return;
        }

        fetch("../php/finalizar_compra.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    itens: carrinho,
                    id_cliente: id_cliente
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    localStorage.removeItem("carrinho");
                    window.location.href = "pedidos.php"; // ou dashboard
                } else {
                    alert("Erro: " + data.error);
                }
            })
            .catch(err => {
                console.error("Erro:", err);
                alert("Erro na comunicação com o servidor.");
            });
    }

    renderCheckoutCarrinho();
</script>