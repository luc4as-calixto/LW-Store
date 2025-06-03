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
                            <a style='color: black; cursor: pointer;' onclick=\"carregar_pagina_editar('editar-produto', " . $row["id"] . ")\"><i class='bi bi-pencil'></i></a> &nbsp &nbsp;
                            <a style='color: black;' href='#' onclick='excluirProduto(" . $row["id"] . ",   ); return false;'><i class='bi bi-trash'></i></a>
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

<script>
    function excluirProduto(id, elemento) {
        if (confirm('Tem certeza que deseja excluir?')) {
            fetch('excluir_produto.php?id=' + id, {
                    method: 'GET'
                })
                .then(response => response.text())
                .then(data => {
                    const linha = elemento.closest('tr');
                    linha.remove();
                    alert('Produto excluído com sucesso!');

                    const tabela = document.querySelector('#form table');
                    const linhasRestantes = tabela.querySelectorAll('tr').length;

                    if (linhasRestantes === 1) { // só o cabeçalho
                        const novaLinha = document.createElement('tr');
                        novaLinha.innerHTML = "<td colspan='7'>Nenhum produto encontrado.</td>";
                        tabela.appendChild(novaLinha);
                    }

                })
                .catch(error => {
                    alert('Erro ao excluir o produto.');
                    console.error(error);
                });
        }
    }

    function carregar_pagina_editar(pagina, id) {
        const main = document.getElementById('main');

        fetch(`${pagina}.php?id=${id}`)
            .then(response => response.text())
            .then(html => {
                main.innerHTML = html;
            })
            .catch(error => {
                main.innerHTML = `<div class="alert alert-danger">Erro ao carregar a página.</div>`;
                console.error('Erro:', error);
            });
    }
</script>