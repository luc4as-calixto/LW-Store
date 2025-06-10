<?php
require_once "conexao.php";

$termo = isset($_GET['termo']) ? $_GET['termo'] : '';
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = isset($_GET['limite']) && is_numeric($_GET['limite']) ? (int)$_GET['limite'] : 5;

try {
    if (!empty($termo)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM product 
                              WHERE (name LIKE :termo OR 
                                     product_code LIKE :termo OR 
                                     description LIKE :termo)
                              AND amount > 0");
        $termoLike = "%$termo%";
        $stmt->bindValue(':termo', $termoLike);
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE amount > 0");
    }
    
    $stmt->execute();
    $totalProdutos = $stmt->fetchColumn();
    $totalPaginas = ceil($totalProdutos / $limite);
    
    echo '<ul class="pagination justify-content-center">';
    
    // Botão Anterior - SEMPRE visível, mas desabilitado na primeira página
    if ($pagina > 1) {
        echo '<li class="page-item"><a class="page-link" href="#" onclick="pesquisarProdutos(\'' . htmlspecialchars($termo) . '\', ' . ($pagina - 1) . ')">&laquo; Anterior</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">&laquo; Anterior</span></li>';
    }
    
    // Números das páginas
    $inicio = max(1, $pagina - 2);
    $fim = min($totalPaginas, $pagina + 2);
    
    // Mostrar primeira página com "..." se necessário
    if ($inicio > 1) {
        echo '<li class="page-item"><a class="page-link" href="#" onclick="pesquisarProdutos(\'' . htmlspecialchars($termo) . '\', 1)">1</a></li>';
        if ($inicio > 2) {
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    for ($i = $inicio; $i <= $fim; $i++) {
        if ($i == $pagina) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="#" onclick="pesquisarProdutos(\'' . htmlspecialchars($termo) . '\', ' . $i . ')">' . $i . '</a></li>';
        }
    }
    
    // Mostrar última página com "..." se necessário
    if ($fim < $totalPaginas) {
        if ($fim < $totalPaginas - 1) {
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        echo '<li class="page-item"><a class="page-link" href="#" onclick="pesquisarProdutos(\'' . htmlspecialchars($termo) . '\', ' . $totalPaginas . ')">' . $totalPaginas . '</a></li>';
    }
    
    // Botão Próximo - SEMPRE visível, mas desabilitado na última página
    if ($pagina < $totalPaginas) {
        echo '<li class="page-item"><a class="page-link" href="#" onclick="pesquisarProdutos(\'' . htmlspecialchars($termo) . '\', ' . ($pagina + 1) . ')">Próximo &raquo;</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Próximo &raquo;</span></li>';
    }
    
    echo '</ul>';
    
    // Mostrar informações sobre a paginação
    echo '<div class="text-center mt-2">';
    echo 'Mostrando página ' . $pagina . ' de ' . $totalPaginas . ' - Total de produtos: ' . $totalProdutos;
    echo '</div>';
    
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erro ao carregar paginação: ' . $e->getMessage() . '</div>';
}
?>