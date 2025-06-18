<?php
require_once '../php/conexao.php';
session_start();

if (!isset($_GET['id'])) {
    die('ID do pedido não informado.');
}

$id_pedido = intval($_GET['id']);

// 🔥 Buscar dados do pedido
$stmt = $conn->prepare("
    SELECT s.*, c.name AS cliente, c.cpf, c.address, se.name AS vendedor
    FROM sales s
    JOIN customers c ON s.id_customer = c.id_customer
    JOIN sellers se ON se.fk_id_user = s.id_user
    WHERE s.id_sale = :id
");
$stmt->execute([':id' => $id_pedido]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    die('Pedido não encontrado.');
}

// 🔥 Buscar itens do pedido
$stmtItens = $conn->prepare("
    SELECT si.*, p.name AS produto, p.product_code
    FROM sale_items si
    JOIN product p ON si.product_id = p.product_id
    WHERE si.id_sale = :id
");
$stmtItens->execute([':id' => $id_pedido]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Comprovante Pedido #<?= $id_pedido ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .user-modal-content {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn-print {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card user-modal-content p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">LWStore</h2>
                <p class="mb-0">Comprovante de Pedido</p>
            </div>
            <div class="text-end">
                <h5 class="mb-1">Pedido #<?= $id_pedido ?></h5>
                <p class="mb-0"><?= date('d/m/Y H:i', strtotime($pedido['date_sale'])) ?></p>
            </div>
        </div>

        <div class="mb-4">
            <h5>Dados do Cliente</h5>
            <p class="mb-0"><strong>Nome:</strong> <?= htmlspecialchars($pedido['cliente']) ?></p>
            <p class="mb-0"><strong>CPF:</strong> <?= htmlspecialchars($pedido['cpf']) ?></p>
            <p class="mb-0"><strong>Endereço:</strong> <?= htmlspecialchars($pedido['address']) ?></p>
        </div>

        <div class="mb-4">
            <h5>Vendedor</h5>
            <p class="mb-0"><?= htmlspecialchars($pedido['vendedor']) ?></p>
        </div>

        <div class="mb-4">
            <h5>Itens do Pedido</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-end">Valor Unit.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($itens as $item): 
                            $subtotal = $item['quantity'] * $item['price_unit'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_code']) ?></td>
                            <td><?= htmlspecialchars($item['produto']) ?></td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-end">R$ <?= number_format($item['price_unit'], 2, ',', '.') ?></td>
                            <td class="text-end">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total:</th>
                            <th class="text-end">R$ <?= number_format($total, 2, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="text-center">
            <p class="mb-0">Obrigado pela sua compra!</p>
        </div>
    </div>
</div>

<!-- Botão de Imprimir -->
<button class="btn btn-primary btn-print" onclick="window.print()">
    <i class="bi bi-printer"></i> Imprimir
</button>

</body>
</html>
