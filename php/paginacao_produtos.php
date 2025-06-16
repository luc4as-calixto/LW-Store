<?php
require_once '../php/conexao.php';

try {
    $limite = 10;
    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    
    // Total de produtos (para calcular o número de páginas)
    $totalProdutos = $conn->query("SELECT COUNT(*) FROM product WHERE amount > 0")->fetchColumn();
    $totalPaginas = ceil($totalProdutos / $limite);

    if ($totalPaginas > 1) {
        echo '<nav aria-label="Navegação de página">';
        echo '<ul class="pagination justify-content-center">';
        
        if ($pagina > 1) {
            echo '<li class="page-item">';
            echo '<a class="page-link" href="?pagina=' . ($pagina - 1) . '">Anterior</a>';
            echo '</li>';
        }
        
        for ($i = 1; $i <= $totalPaginas; $i++) {
            $active = ($i == $pagina) ? 'active' : '';
            echo '<li class="page-item ' . $active . '">';
            echo '<a class="page-link" href="?pagina=' . $i . '">' . $i . '</a>';
            echo '</li>';
        }
        
        if ($pagina < $totalPaginas) {
            echo '<li class="page-item">';
            echo '<a class="page-link" href="?pagina=' . ($pagina + 1) . '">Próxima</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erro: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>