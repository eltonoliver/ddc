<?php
if ($arquivoModulo != 'flip') {
    ?>
    <!-- JS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')</script>
    <script src="js/audiojs/audio.min.js"></script>
    <script type="text/javascript">
        var url_raiz = '<?php echo $url_raiz ?>';
    </script>
    <script src="js/radio.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script type="text/javascript" src="js/nprogress.js"></script>
    <script type="text/javascript" src="js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="js/newsletter.js"></script>
    <script type="text/javascript" src="js/youtube.js"></script>
    <script type="text/javascript" src="js/global.js"></script>
    <script type="text/javascript" src="js/loadPage.js"></script>
    <?php /* ?>
      <script>
      /* ===========================================
     * JOGAR TUDO NUM ARQUIVO JS SEPARADO
     * =========================================== *

      //OPEN-HIDE SEARCH FORM FORM
      $(function() {
      if($('.oc-form-search').hasClass('oc-open')) {
      $('.oc-form-search').on('click',function(e) {
      $('.form-search__container').toggleClass('closed').toggleClass('opened');
      $(this).toggleClass('oc-open').toggleClass('oc-close');
      e.preventDefault();
      });
      }
      if($('.oc-form-search').hasClass('oc-close')) {
      $('.oc-form-search').on('click',function(e) {
      $('.form-search__container').toggleClass('opened').toggleClass('closed');
      $(this).toggleClass('oc-close').toggleClass('oc-open');
      e.preventDefault();
      });
      }
      });

      //CONTADOR http://keith-wood.name/countdown.html
      $(function() {
      var austDay = new Date();
      austDay = new Date(austDay.getFullYear() + 1, 1 - 1, 26);
      $('#countdown').countdown({
      until: austDay,
      format: 'oDHMS'
      });
      $('#year').text(austDay.getFullYear());
      });

      //MODAL
      $(function() {
      $('[data-modal]').on('click', function(e) {
      var content = $(this).attr('data-modal');

      e.preventDefault();

      //open
      $('.modal').fadeIn(function() {
      $(this)
      .children('.modal-container')
      .children('.modal-content')
      .prepend(content)
      .parent('.modal-container')
      .slideDown();
      });

      //close
      $('.modal-close').on('click', function(e) {
      e.preventDefault();

      $(this)
      .parent('.modal-container')
      .slideUp(function() {
      $(this)
      .parent('.modal')
      .fadeOut(function() {
      $('.modal-content').empty();
      });
      });
      });

      $('.modal-mask').on('click', function(e) {
      e.preventDefault();

      $('.modal-container')
      .slideUp(function() {
      $(this)
      .parent('.modal')
      .fadeOut(function() {
      $('.modal-content').empty();
      });
      });
      });
      });
      });

      //SCROLL TO TOP BUTTON
      $(function() {
      $(window).scroll(function(){
      if ($(this).scrollTop() > 300) {
      $('.to-top').fadeIn();
      } else {
      $('.to-top').fadeOut();
      }
      });

      $('.to-top').click(function(){
      $('html, body').animate({ scrollTop : 0 }, 300);
      return false;
      });
      });

      //JORNALISMO POPULAR TOGGLE
      $(function() {
      $('.btn-enviadas').on('click', function(e) {
      if($('#regulamento').is(":visible")) {
      $('#regulamento').fadeOut(function() {
      $('#enviadas').fadeIn();

      $('.btn-enviadas').addClass('active');
      $('.btn-regulamento').removeClass('active');
      });
      }

      if($('#envio-noticia').is(":visible")) {
      $('#envio-noticia').fadeOut(function() {
      $('#enviadas').fadeIn();

      $('.btn-enviadas').addClass('active');
      $('.btn-envio-noticia').removeClass('active');
      });
      }

      e.preventDefault();
      });

      $('.btn-regulamento').on('click', function(e) {
      if($('#enviadas').is(":visible")) {
      $('#enviadas').fadeOut(function() {
      $('#regulamento').fadeIn();

      $('.btn-regulamento').addClass('active');
      $('.btn-enviadas').removeClass('active');
      });
      }

      if($('#envio-noticia').is(":visible")) {
      $('#envio-noticia').fadeOut(function() {
      $('#regulamento').fadeIn();

      $('.btn-regulamento').addClass('active');
      $('.btn-envio-noticia').removeClass('active');
      });
      }

      e.preventDefault();
      });

      $('.btn-envio-noticia').on('click', function(e) {
      if($('#enviadas').is(":visible")) {
      $('#enviadas').fadeOut(function() {
      $('#envio-noticia').fadeIn();

      $('.btn-envio-noticia').addClass('active');
      $('.btn-enviadas').removeClass('active');
      });
      }

      if($('#regulamento').is(":visible")) {
      $('#regulamento').fadeOut(function() {
      $('#envio-noticia').fadeIn();

      $('.btn-envio-noticia').addClass('active');
      $('.btn-regulamento').removeClass('active');
      });
      }

      e.preventDefault();
      });

      $('#to-reg').on('click', function(e) {
      $('#envio-noticia').fadeOut(function() {
      $('#regulamento').fadeIn();

      $('.btn-regulamento').addClass('active');
      $('.btn-envio-noticia').removeClass('active');
      });

      e.preventDefault();
      });

      $('#jp_tipoDeAnexo').change(function() {
      var tipo = $('#jp_tipoDeAnexo').val();

      if(tipo == 1) {
      $('#jp_tipoDeAnexo-link').parent().fadeOut(function() {
      $('#jp_tipoDeAnexo-arquivo').parent().fadeIn();
      });
      } else if(tipo == 2) {
      $('#jp_tipoDeAnexo-arquivo').parent().fadeOut(function() {
      $('#jp_tipoDeAnexo-link').parent().fadeIn();
      });
      }
      });
      });

      //FADE IN IMGs
      $(function() {
      $('img').load(function() {
      $(this).fadeIn('slow');
      });
      });

      $(function() {
      $('[href="#main-menu"]').click(function(e) {
      $(this)
      .next('.main-menu')
      .toggleClass('opened');

      $(this)
      .children('.menu-close')
      .toggleClass('show');

      e.preventDefault();
      });
      });
      </script>
      <?php */ ?>
    <?php
    /* Youtube */
    /* if ($arquivoModulo == 'principal') {
      ?>
      <script type="text/javascript" src="http://gdata.youtube.com/feeds/api/users/<?php echo $redesSociais[4]["nmUsuario"]; ?>/uploads?v=2&alt=json-in-script&callback=showLastVideoPopup&format=5&max-results=1"></script>
      <?php
      } */
    if ($arquivoModulo == 'ddcProgramas' and !$_GET['v']) {
        $paginaVideo = $_GET['p'] ? (int) $_GET['p'] : 1;
        $limiteVideos = 21;
        $indexVideo = $_GET['p'] ? (int) $_GET['p'] * $limiteVideos - ($limiteVideos - 1) : 1;
        ?>
        <script>
            var paginaVideo =<?php echo $paginaVideo ?>;
            var tituloPrograma = "<?php echo $titulo ?>";
            var limiteVideos = 21;
        </script>
        <script type="text/javascript" src="http://gdata.youtube.com/feeds/api/playlists/<?php echo $titulo; ?>?&v=2&alt=json-in-script&callback=showMyVideos&format=5&max-results=<?php echo $limiteVideos; ?>&start-index=<?php echo $indexVideo ?>"></script>
        <?php
    } elseif ($arquivoModulo == 'videos' and $_GET['v']) {
        ?>
        <script type="text/javascript" src="http://gdata.youtube.com/feeds/api/videos?v=2&alt=json-in-script&callback=showMyVideo&format=5&q=<?php echo $_GET['v'] ?>"></script>
    <?php } ?>
    <?php if ($arquivoModulo == 'galeria' and $id > 0) { ?>
        <!-- Add fancyBox -->
        <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/dragtoshare.css" type="text/css" media="screen" />
        <script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="js/galeria.js"></script>
    <?php } ?>
    <?php
    $dataContador = explode("-", $qryContador[0]["dtDataConteudo"]);
    ?>
    <script>
        //CONTADOR http://keith-wood.name/countdown.html
        $(function() {
            $('#countdown').countdown({
                until: new Date(<?php echo $dataContador[0]; ?>, <?php echo $dataContador[1]; ?> - 1, <?php echo $dataContador[2]; ?>),
                format: 'oDHMS'
            });
            $('#year').text(austDay.getFullYear());
        });
    </script>
    <?php
}
?>