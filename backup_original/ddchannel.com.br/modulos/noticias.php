<?php 
  if (!isset($titulo) || !$titulo) {
	  
    // Define o tipo de conteúdo que vai ser carregado através do SQL
    $idTipoConteudo = '11';
    //PREPARA�AO DO PAGINADOR
    //Define o total de registros por p�gina
    $limite = 8;
    //Pega o n�mero da p�gina que vem pela URL
    $pagina = (int) $_GET['pag'];
    //Seta a p�gina inicial
    if (!$pagina) {
        $pagina = 1;
    }
    //Calcula os registros inicial e final as serem pesquisados no banco de dados
    $inicio = ($pagina * $limite) - $limite;
    $filtro = '';
    $ano = ($_REQUEST['ano'] ? (int) $_REQUEST['ano'] : date('Y'));
    $mes = ($_REQUEST['mes'] ? (int) $_REQUEST['mes'] : false);
    $filtro .= ' AND YEAR(dtDataConteudo) = ' . $db->clean($ano);
    if ($mes)
        $filtro .= ' AND MONTH(dtDataConteudo) = ' . $db->clean($mes);

    // SQL para charges padrão
    $sqlNoticias = "SELECT idConteudo,nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo,nmListaTags FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= " . $idTipoConteudo . "  " . $filtro . " order by idConteudo DESC LIMIT " . $inicio . "," . $limite;
	 // Query de acordo com a requisicao do usuario
    $qryNoticias = $db->query($sqlNoticias)
    ?>

    <h2 class="title-section">
        <span class="title-section__name">
            Notícias
        </span>

        <ul class="title-section__archive">
            <li>
                <a >Arquivo</a>
                <ul>
                    <?php
                    $qryArquivo = $db->query("SELECT year(dtDataConteudo) as ano,month(dtDataConteudo) as mes, count(idConteudo) as noticias FROM tb_conteudo WHERE idTipoConteudo=11 AND inPublicar = 1 group by ano,mes order by ano desc, mes DESC");
                    $meses = count($qryArquivo);
                    foreach ($qryArquivo as $i => $array) {
                        if ($i == 0 or $qryArquivo[$i - 1]["ano"] != $array["ano"]) {
                            ?>
                            <li>
                                <a href="noticias/ano/<?php echo $array["ano"]; ?>"><?php echo $array["ano"]; ?></a>
                                <ul>
                                <?php } ?>
                                <li>
                                    <a href="noticias/ano/<?php echo $array["ano"]; ?>/mes/<?php echo $array["mes"]; ?>"><?php echo nomeMes($array["mes"]); ?> <i>(<?php echo $array["noticias"]; ?>)</i></a>
                                </li>
                                <?php if (($i + 1) == $meses or $qryArquivo[$i + 1]["ano"] != $array["ano"]) { ?>
                                </ul>
                            </li>
                            <?php
                        }
                    }
                    ?>            
                </ul>
            </li>
        </ul>

    </h2>
    <?php echo resume($string, $char) ?>
    <div class="list">
        <?php
        foreach ($qryNoticias as $noticia) {
            ?>

            <article class="list-item cf">
              <?php
                if (is_file("arquivos/enviados/image/" . $noticia["nmLinkImagem"])) {
                    ?>
                <a class="list-item__img" href="noticias/<?php echo $noticia['nmTituloAmigavel']; ?>">
                    <img alt="<?php echo $noticia['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $noticia['nmLinkImagem']; ?>&w=310&h=310" />
                </a>

                <h3>
                    <a href="noticias/<?php echo $noticia['nmTituloAmigavel']; ?>"><?php echo stripslashes($noticia['nmTituloConteudo']); ?></a>
                </h3>

                <p>
                    <?php echo resume($noticia['nmConteudo'], 270); ?>
                    <a class="more" href="noticias/<?php echo $noticia['nmTituloAmigavel']; ?>">Continue lendo</a>
                </p>

                <div class="list-item__meta">
                    <b>Em:</b> <?php echo date('d/m/Y', strtotime($noticia[data])); ?> |
                    <!-- <b>Por:</b> <a ><php echo $noticia['nmUsuario']; ?></a> | -->
                    <b>Tags:</b> 
                    <?php
                    $sqlTags = "SELECT tb_categoria.nmCategoria FROM tb_conteudo INNER JOIN tb_conteudo_tag ON tb_conteudo_tag.idConteudo = tb_conteudo.idConteudo INNER JOIN tb_categoria ON tb_categoria.idCategoria = tb_conteudo_tag.idCategoria WHERE tb_conteudo.idConteudo = " . $noticia['idConteudo'] . ";";
                    $qryTags = $db->query($sqlTags);

                    foreach ($qryTags as $tags) {
                        ?>
                        <a > <?php echo $tags['nmCategoria']; ?> </a>

                    <?php } ?>

                </div>
                <?php }else{ ?>
				   
                <h3>
                    <a href="noticias/<?php echo $noticia['nmTituloAmigavel']; ?>"><?php echo stripslashes($noticia['nmTituloConteudo']); ?></a>
                </h3>

                <p>
                    <?php echo resume($noticia['nmConteudo'], 270); ?>
                    <a class="more" href="noticias/<?php echo $noticia['nmTituloAmigavel']; ?>">Continue lendo</a>
                </p>

                <div class="list-item__meta">
                    <b>Em:</b> <?php echo date('d/m/Y', strtotime($noticia[data])); ?> |
                    <!-- <b>Por:</b> <a ><php echo $noticia['nmUsuario']; ?></a> | -->
                    <b>Tags:</b> 
                    <?php
                    $sqlTags = "SELECT tb_categoria.nmCategoria FROM tb_conteudo INNER JOIN tb_conteudo_tag ON tb_conteudo_tag.idConteudo = tb_conteudo.idConteudo INNER JOIN tb_categoria ON tb_categoria.idCategoria = tb_conteudo_tag.idCategoria WHERE tb_conteudo.idConteudo = " . $noticia['idConteudo'] . ";";
                    $qryTags = $db->query($sqlTags);

                    foreach ($qryTags as $tags) {
                        ?>
                        <a > <?php echo $tags['nmCategoria']; ?> </a>

                    <?php } ?>

                </div>
				
				<?php } ?>
            </article>

            <?php
          }
        ?>
    </div>     		
    <div class="clearBoth">
        <?php
        //NAVEGA�AO DO PAGINADOR 
          $srtQtotal = "
                            SELECT 		COUNT(*) as total_registros 
                            FROM 		vwconteudo
                            WHERE 		idTipoConteudo = " . $db->clean($idTipoConteudo) . "
                            AND			inPublicar = 1 " . $filtro . "
                    ";
        //Busca o total de registros da consulta nao paginada
        $total_registros = $db->query($srtQtotal);
        $total_registros = $total_registros[0]["total_registros"];
        //Calcula o total de p�ginas
        $total_paginas = ceil($total_registros / $limite);
        $filtro2 = '';
        $ano = ($_REQUEST['ano'] ? (int) $_REQUEST['ano'] : date('Y'));
        $mes = ($_REQUEST['mes'] ? (int) $_REQUEST['mes'] : false);
        if ($ano)
            $filtro2 .= '/ano/' . $ano;
        if ($mes)
            $filtro2 .= '/mes/' . $mes;
        //Nome da p�gina 
        $stringPagina = $url_raiz . 'noticias' . $filtro2;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
        ?>
    </div>
    
<?php } else {
    $titulo = html_entity_decode($titulo);
    $strPagina = "SELECT idConteudo,nmTituloAmigavel,nmUsuario, dtDataConteudo as dtCompleta, day(dtDataConteudo) as dia,month(dtDataConteudo) as mes,nmLinkImagem,nmTituloConteudo,nmConteudo,inComentario FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE nmTituloAmigavel= " . $db->clean($titulo) . " AND inPublicar = 1 LIMIT 1";
    $qryPagina = $db->query($strPagina);
    if (!$qryPagina) {
        redirecionar($url_raiz . '404');
    }
    ?>

    <article class="post">
        <h2 class="title-section">
            <span class="title-section__name">
                Notícias
            </span>

            <a class="title-section__more" href="javascript:history.back(-1);">
                Voltar
            </a>
        </h2>

        <div class="post-img">
            <?php
            if (is_file("arquivos/enviados/image/" . $qryPagina[0]["nmLinkImagem"])) {
                ?>
                <img alt="<?php echo $qryPagina[0]["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qryPagina[0]["nmLinkImagem"]; ?>&w=780&h=440" />
                <?php
            }
            ?>   
        </div>

        <h3 class="post-title">
            <?php echo stripslashes($qryPagina[0]["nmTituloConteudo"]); ?>
        </h3>

        <div class="post-meta">
            <b>Em:</b> <?php echo date('d/m/Y', strtotime($qryPagina[0]["dtCompleta"])); ?> |
                     
            <?php
            $sqlTags = "SELECT tb_categoria.nmCategoria FROM tb_conteudo INNER JOIN tb_conteudo_tag ON tb_conteudo_tag.idConteudo = tb_conteudo.idConteudo INNER JOIN tb_categoria ON tb_categoria.idCategoria = tb_conteudo_tag.idCategoria WHERE tb_conteudo.idConteudo = " . $qryPagina[0]['idConteudo'] . ";";
            $qryTags = $db->query($sqlTags);
             ?><b>Tags:</b> <?php
            foreach ($qryTags as $tags) {
                ?>
                
                <a href=""> <?php echo $tags['nmCategoria']; ?> </a>
            <?php } ?>
        </div>

        <div class="post-share"><?php include 'addThis.php'; ?></div>

        <div class="post-text">
         <?php
				
			 echo stripslashes($qryPagina[0]['nmConteudo']);	
			
            ?>
        </div>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-comments" data-href="http://clientesugagogo.com.br/ddc/noticias/<?php echo $qryPagina[0]["nmTituloAmigavel"]; ?>"   data-width="800" data-numposts="5" data-colorscheme="light"></div>
    </article>

    <?php
    include 'ddcProgramas.php';
    ?>


<?php } ?>