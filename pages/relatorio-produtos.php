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

                        <form id='formEditar' method='POST'>
                    
                            <a style='color: black; cursor: pointer;' onclick=\"carregar_pagina_editar('editar-produto', " . $row["product_code"] . ")\">
                                <i class='bi bi-pencil'></i>
                            </a>
                            
                        </form>

                        <form id='formExcluir' method='POST'>
                    
                            <a style='color: black;' href='#' onclick='excluirProduto(" . $row["product_code"] . ",   ); return false;'>
                                <i class='bi bi-trash'></i>
                            </a>

                        </form>

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
</script>