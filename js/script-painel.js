$(document).ready(function() {
    // Abre/fecha menus
    $('.dropdown-toggle').click(function(e) {
      e.preventDefault();
      var $this = $(this);
      var $parent = $this.parent();
      var $submenu = $parent.find('ul');
  
      // Fecha outros submenus abertos
      $('.menu-content ul.collapse.show').not($submenu).collapse('hide');
  
      // Alterna seta
      $('.dropdown-toggle .arrow').not($this.find('.arrow')).removeClass('rotate');
      $this.find('.arrow').toggleClass('rotate');
    });
  
    // Carregamento de página
    $('.submenu').click(function(e) {
      e.preventDefault();
  
      var page = $(this).data('page');
      alert('Carregando ' + page + '...');
  
      // Marcar item ativo
      $('.submenu').removeClass('active');
      $(this).addClass('active');
  
      // Mostra loader
      $('#pagina').html('<div class="loader"><i class="fa fa-spinner"></i> Carregando ' + page + '...</div>');
  
      // Simula tempo de carregamento
      setTimeout(function() {
        $('#pagina').html('<h2>' + page + '</h2><p>Conteúdo da página "' + page + '" carregado com sucesso!</p>');
      }, 1500); // 1.5 segundos
    });
  });