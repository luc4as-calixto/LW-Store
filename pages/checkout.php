<?php

session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../pages/login.php');
    exit();
}
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
                <option value="" selected disabled>-- Escolha um cliente --</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_customer'] ?>">
                        <?= htmlspecialchars($cliente['name']) ?> - CPF: <?= $cliente['cpf'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- ID do vendedor (sessão) -->
        <input type="hidden" id="vendedor" value="<?= $_SESSION['id_user'] ?>">


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

        <div id="message" style="display: none;"></div>
    </div>
</div>

<!-- Modal de Sucesso -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div class="mx-auto mb-3" style="width:80px;height:80px;">
                <svg width="80" height="80" viewBox="0 0 80 80">
                    <circle cx="40" cy="40" r="38" fill="#e6ffe6" stroke="#28a745" stroke-width="4" />
                    <polyline points="25,43 37,55 56,30" fill="none" stroke="#28a745" stroke-width="5" stroke-linecap="round" stroke-linejoin="round">
                        <animate attributeName="points" dur="0.5s" fill="freeze"
                            from="25,43 25,43 25,43" to="25,43 37,55 56,30" />
                    </polyline>
                </svg>
            </div>
            <h5 class="mb-2" id="successModalLabel">Compra realizada com sucesso!</h5>
            <p class="mb-0">O pedido foi finalizado e registrado no sistema.</p>
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

            const disableMais = item.qtd >= item.estoque ? 'disabled' : '';

            container.innerHTML += `
      <div class="d-flex justify-content-between align-items-center border-bottom py-2">
        <div class="flex-grow-1 me-2">
          <strong>${item.codigo} - ${item.nome}</strong><br>
          <small>R$ ${item.preco.toFixed(2).replace('.', ',')} cada</small>
        </div>

        <div class="d-flex align-items-center gap-1">
          <button class="btn btn-sm btn-outline-secondary" onclick="alterarQtdCheckout(${i}, -1)">−</button>
          <span class="px-2">${item.qtd}</span>
          <button class="btn btn-sm btn-outline-secondary" onclick="alterarQtdCheckout(${i}, 1)" ${disableMais}>+</button>
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

        const novaQtd = carrinho[index].qtd + delta;

        if (novaQtd > carrinho[index].estoque) {
            return;
        }

        if (novaQtd <= 0) {
            carrinho.splice(index, 1);
        } else {
            carrinho[index].qtd = novaQtd;
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
        const id_vendedor = document.getElementById('vendedor').value;

        if (!id_cliente) {
            document.getElementById("message").innerHTML = `<div class="alert alert-danger">Selecione um cliente.</div>`;
            document.getElementById("message").style.display = "block";
            return;
        }

        if (carrinho.length === 0) {
            document.getElementById("message").innerHTML = `<div class="alert alert-danger">Carrinho vazio.</div>`;
            document.getElementById("message").style.display = "block";
            return;
        }

        fetch("../php/finalizar_compra.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    itens: carrinho,
                    id_cliente: id_cliente,
                    id_vendedor: id_vendedor
                })
            })

            .then(res => res.json())
            .then(data => {
                console.log("Retorno do backend:", data);
                if (data.success) {
                    showSuccessModal();
                    setTimeout(() => {
                        window.open(`comprovante.php?id=${data.id_pedido}`, '_blank');
                    }, 2000);
                    clearCustomer();
                    limparCarrinho();
                    renderCheckoutCarrinho();
                } else {
                    document.getElementById("message").innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    document.getElementById("message").style.display = "block";
                }
            })
            .catch(error => {
                console.error("Erro ao enviar pedido:", error);
                document.getElementById("message").innerHTML = `<div class="alert alert-danger">Erro ao enviar pedido. Tente novamente mais tarde.</div>`;
                document.getElementById("message").style.display = "block";
            });
    }

    function showSuccessModal() {
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
        setTimeout(() => modal.hide(), 2000);
    }

    function clearCustomer() {
        option = document.getElementById("clienteSelect");
        option.selectedIndex = 0;
    }

    renderCheckoutCarrinho();
</script>