$(document).ready(function () {
    function carregarProdutos(pagina = 1) {
        const termo = $("#filtro").val().trim();

        $.ajax({
            url: '../php/grid_produtos.php',
            method: 'GET',
            data: { pagina: pagina, termo: termo },
            success: function (html) {
                $('#areaProdutos').html(html);

                $.ajax({
                    url: '../php/paginacao_produtos.php',
                    method: 'GET',
                    data: { pagina: pagina, termo: termo },
                    success: function (html) {
                        $('.pagination').html(html);
                    }
                });
            },
            error: function () {
                alert('Erro ao carregar os produtos.');
            }
        });
    }

    // Evento do filtro
    $("#filtro").on("keyup", function () {
        const termo = $(this).val().trim();
        $("#btnLimparPesquisa").toggle(termo.length > 0);
        carregarProdutos();
    });

    $("#btnLimparPesquisa").on("click", function () {
        $("#filtro").val('');
        $(this).hide();
        carregarProdutos();
    });

    // Paginação
    $(document).on('click', '.pagination-link', function (e) {
        e.preventDefault();
        const pagina = $(this).data('pagina');
        carregarProdutos(pagina);
    });

    // Carregar primeira página
    carregarProdutos();
});
