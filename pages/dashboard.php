<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../pages/login.php');
    exit();
}
?>

<!-- HTML -->
<div class="container mt-4" id="pagina">
    <?php
    if ($_SESSION['type_user'] == 'admin') {
        echo '<h1>Gráfico de Vendas de todos Vendedores</h1>';
    } else {
        echo '<h1>Seu Gráfico de Vendas</h1>';
    }
    ?>


    <div class="mt-5">
        <canvas id="graficoVendas" class="grafico-pequeno"></canvas>
        <div id="mensagemVendas" class="text-danger mt-3 text-center"></div>
    </div>

    <div class="mt-5">
        <canvas id="graficoProdutos" class="grafico-pequeno"></canvas>
        <div id="mensagemProdutos" class="text-danger mt-3 text-center"></div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Gráfico de Vendas por Mês
    fetch('../php/dados_grafico.php')
        .then(res => res.json())
        .then(data => {
            if (data.meses.length === 0 || data.totais.length === 0) {
                document.getElementById('graficoVendas').style.display = 'none';
                document.getElementById('mensagemVendas').innerText = 'Nenhuma venda encontrada.';
                return;
            }

            const ctx = document.getElementById('graficoVendas').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.meses,
                    datasets: [{
                        label: 'Total de Vendas por Mês',
                        data: data.totais,
                        backgroundColor: '#d4f5f5',
                        borderColor: '#89c4c4',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Erro ao carregar gráfico de vendas:', error));

    // Gráfico de Produtos mais Vendidos
    fetch('../php/dados_grafico_produtos.php')
        .then(res => res.json())
        .then(data => {
            if (data.produto.length === 0 || data.quantidade.length === 0) {
                document.getElementById('graficoProdutos').style.display = 'none';
                document.getElementById('mensagemProdutos').innerText = 'Nenhum produto vendido ainda.';
                return;
            }

            const ctx = document.getElementById('graficoProdutos').getContext('2d');
            new Chart(ctx, {
                type: "pie",
                data: {
                    labels: data.produto,
                    datasets: [{
                        label: 'Produtos mais Vendidos',
                        data: data.quantidade,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56',
                            '#4BC0C0', '#9966FF', '#FF9F40'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Produtos mais Vendidos'
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Erro ao carregar gráfico de produtos:', error));
</script>