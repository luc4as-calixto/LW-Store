<?php
require_once '../php/conexao.php';

try {
    $limite = 10;
    $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $termo = isset($_GET['termo']) ? $_GET['termo'] : '';
    
    // Query base para contar produtos
    $sqlCount = "SELECT COUNT(*) FROM product WHERE amount > 0";
    
    if (!empty($termo)) {
        $sqlCount .= " AND (name LIKE :termo OR product_code LIKE :termo)";
    }
    
    $stmtCount = $conn->prepare($sqlCount);
    
    if (!empty($termo)) {
        $termoBusca = '%' . $termo . '%';
        $stmtCount->bindValue(':termo', $termoBusca);
    }
    
    $stmtCount->execute();
    $totalProdutos = $stmtCount->fetchColumn();
    $totalPaginas = ceil($totalProdutos / $limite);

    if ($totalPaginas > 1) {
        echo '<nav aria-label="Navegação de página">';
        echo '<ul class="pagination justify-content-center">';
        
        if ($pagina > 1) {
            echo '<li class="page-item">';
            echo '<a class="page-link pagination-link" href="#" data-pagina="' . ($pagina - 1) . '" data-termo="' . htmlspecialchars($termo) . '">Anterior</a>';
            echo '</li>';
        }
        
        for ($i = 1; $i <= $totalPaginas; $i++) {
            $active = ($i == $pagina) ? 'active' : '';
            echo '<li class="page-item ' . $active . '">';
            echo '<a class="page-link pagination-link" href="#" data-pagina="' . $i . '" data-termo="' . htmlspecialchars($termo) . '">' . $i . '</a>';
            echo '</li>';
        }
        
        if ($pagina < $totalPaginas) {
            echo '<li class="page-item">';
            echo '<a class="page-link pagination-link" href="#" data-pagina="' . ($pagina + 1) . '" data-termo="' . htmlspecialchars($termo) . '">Próxima</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erro: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>