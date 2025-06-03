<div class="container mt-4" id="pagina">
    <h1>Relatórios de produtos</h1>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Tipo de embalagem</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>


        <?php

        require_once '../php/conexao.php';

        try {
            $sql = "SELECT * FROM product";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['product_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>R$ " . number_format($row['price'], 2, ',', '.') . "</td>";
                    echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['type_packaging']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>
                            <a style='color: black; cursor: pointer;' onclick=\"carregar_pagina_editar('editar-produto', " . $row["product_code"] . ")\"><i class='bi bi-pencil'></i></a> &nbsp &nbsp;
                            <a style='color: black;' href='#' onclick='excluirProduto(" . $row["product_code"] . ",   ); return false;'><i class='bi bi-trash'></i></a>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Nenhum produto cadastrado.</td></tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='5'>Erro: " . $e->getMessage() . "</td></tr>";
        }

        ?>
    </table>

</div>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="modalConfirmExclusao" tabindex="-1" aria-labelledby="modalConfirmExclusaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content user-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmExclusaoLabel">Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center">
                <br>
                <h5>Tem certeza que deseja excluir este produto?</h5>
                <p><strong id="produtoExcluirNome"></strong></p>
                <br>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarExclusao">Excluir</button>
            </div>
            <!-- <div class="modal-footer justify-content-center">
            </div> -->
        </div>
    </div>
</div>


<script>
    let produtoIdExcluir = null;

    function excluirProduto(id, nome) {
        produtoIdExcluir = id;
        document.getElementById('produtoExcluirNome').textContent = nome;
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmExclusao'));
        modal.show();
    }

    // Confirmar exclusão
    document.getElementById('btnConfirmarExclusao').addEventListener('click', function() {
        if (produtoIdExcluir) {
            // Aqui vai seu AJAX para excluir o produto
            // Exemplo:
            fetch('../php/excluir_produto.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${encodeURIComponent(produtoIdExcluir)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // ou remova a linha diretamente
                    } else {
                        alert(data.error || "Erro ao excluir.");
                    }
                })
                .catch(err => {
                    console.error("Erro:", err);
                });
        }
    });


    // function excluirProduto(id, elemento) {
    //     if (confirm('Tem certeza que deseja excluir?')) {
    //         fetch('excluir_produto.php?id=' + id, {
    //                 method: 'GET'
    //             })
    //             .then(response => response.text())
    //             .then(data => {
    //                 const linha = elemento.closest('tr');
    //                 linha.remove();
    //                 alert('Produto excluído com sucesso!');

    //                 const tabela = document.querySelector('#form table');
    //                 const linhasRestantes = tabela.querySelectorAll('tr').length;

    //                 if (linhasRestantes === 1) { // só o cabeçalho
    //                     const novaLinha = document.createElement('tr');
    //                     novaLinha.innerHTML = "<td colspan='7'>Nenhum produto encontrado.</td>";
    //                     tabela.appendChild(novaLinha);
    //                 }

    //             })
    //             .catch(error => {
    //                 alert('Erro ao excluir o produto.');
    //                 console.error(error);
    //             });
    //     }
    // }

    // function carregar_pagina_editar(pagina, id) {
    //     const main = document.getElementById('main');

    //     fetch(`${pagina}.php?id=${id}`)
    //         .then(response => response.text())
    //         .then(html => {
    //             main.innerHTML = html;
    //         })
    //         .catch(error => {
    //             main.innerHTML = `<div class="alert alert-danger">Erro ao carregar a página.</div>`;
    //             console.error('Erro:', error);
    //         });
    // }
</script>