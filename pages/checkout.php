<?php

session_start();
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
                alert(JSON.stringify(data));
                if (data.success) {
                    document.getElementById("message").innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    document.getElementById("message").style.display = "block";
                    window.open(`comprovante.php?id=${data.id_pedido}`, '_blank');

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

    renderCheckoutCarrinho();
</script>