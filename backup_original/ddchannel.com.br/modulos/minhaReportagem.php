<?php 
if (!isset($titulo) || !$titulo) {
	$strPagina2 = "SELECT idConteudo,nmTituloAmigavel,nmUsuario, dtDataConteudo as dtCompleta, day(dtDataConteudo) as dia,month(dtDataConteudo) as mes,nmLinkImagem,nmTituloConteudo,nmConteudo,inComentario FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE idTipoConteudo = 32 AND inPublicar = 1 ORDER BY idConteudo DESC LIMIT 1";
    $qryPagina2 = $db->query($strPagina2);
    if (!$qryPagina2) {
        redirecionar($url_raiz . '404');
    }
?>
    <div class="post">
        <h2 class="title-section">
            <span class="title-section__name">
                Minha Reportagem
            </span>

            <a class="title-section__more" href="javascript:history.back(-1);">
                Voltar
            </a>
        </h2>

        <div class="post-img">
             <?php
                if (is_file("arquivos/enviados/image/" . $qryPagina2[0]["nmLinkImagem"])) {
                    ?>
                    <img alt="<?php echo $qryPagina2[0]["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qryPagina2[0]["nmLinkImagem"]; ?>&w=1280&h=720" />
                    <?php
                }
                ?>   
        </div>

        <article class="post-text">
            <p>
                <?php echo resume($qryPagina2[0]["nmConteudo"], 300); ?>
                <a class="more" href="minha-reportagem-noticia/<?php echo $qryPagina2[0]['nmTituloAmigavel']; ?>">Continue lendo</a>
            </p>
        </article>
        <br />
    </div>

<?php } ?>
    

    <div id="enviadas" style="display: block;">
        <h2 class="title-section">
            <span class="title-section__name">
                Reportagens enviadas
            </span>
        </h2>
         <div class="buttons-center">
            <a class="btn btn-enviadas active" href="#">
                <span class="icon-enviadas"></span>
                <b>Reportagens enviadas</b>
            </a>
    
            <a class="btn btn-regulamento" href="pagina/regulamento">
                <span class="icon-regulamento"></span>
                <b>Como Participar</b>
            </a>
    
            <a class="btn btn-envio-noticia" href="#">
                <span class="icon-envio-noticia"></span>
                <b>Envie a sua notícia</b>
            </a>
        </div>
        <div class="grid grid-enviadas">
        <?php
		// Define o tipo de conteúdo que vai ser carregado através do SQL
		  $idTipoConteudo = '32';
		//PREPARA�AO DO PAGINADOR
		//Define o total de registros por p�gina
		  $limite = 20;
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

          $str = "SELECT * FROM tb_conteudo WHERE inPublicar=1 and idTipoConteudo= " . $idTipoConteudo . "  " . $filtro . " order by idConteudo DESC LIMIT " . $inicio . "," . $limite;
            $qry = $db->query($str);
            foreach ($qry as $notpopular) {
          ?>
            <article class="grid-item" id="item-jornalismo">
                <a class="list-item__img" href="minha-reportagem-noticia/<?php echo $notpopular['nmTituloAmigavel']; ?>" >
                <img id="img-galeria" alt="<?php echo $notpopular['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $notpopular['nmLinkImagem']; ?>&w=480&h=480" />
                    <div class="grid-item__about">
                        <h3><?php echo $notpopular['nmTituloConteudo'] ?></h3>

                        <small><?php echo date('d/m/Y', strtotime($notpopular['dtDataConteudo'])); ?></small>
                    </div>
                </a>
            </article>
         <?php } ?>
        </div>

        <div class="clearBoth">
		  <?php
          //NAVEGA�AO DO PAGINADOR 
            $srtQtotal = "
                              SELECT 		COUNT(*) as total_registros 
                              FROM 		vwconteudo
                              WHERE 		idTipoConteudo = " . $db->clean($idTipoConteudo) . "
                              AND			inPublicar = 1
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
          $stringPagina = $url_raiz . 'falaManaus' . $filtro2;
          //Chama a fun�ao que monta a exibi�ao do paginador
          navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
          ?>
        </div>
    </div>

    <article id="regulamento" class="post">
        <h2 class="title-section">
            <span class="title-section__name">
                Como Participar
            </span>
        </h2>
        <div class="buttons-center">
            <a class="btn btn-enviadas " href="#">
                <span class="icon-enviadas"></span>
                <b>Reportagens enviadas</b>
            </a>
    
            <a class="btn btn-regulamento active" href="pagina/regulamento">
                <span class="icon-regulamento"></span>
                <b>Como Participar</b>
            </a>
    
            <a class="btn btn-envio-noticia" href="#">
                <span class="icon-envio-noticia"></span>
                <b>Envie a sua notícia</b>
            </a>
        </div>
        <div class="post-text">
		    <?php
                $str = "SELECT * FROM tb_conteudo WHERE idConteudo =155 AND idTipoConteudo =4";
                $qry = $db->query($str);
                foreach ($qry as $regulamento) {
            ?>
                <?php echo stripslashes($regulamento['nmConteudo']); ?>
            <?php } ?>
        </div>
    </article>

    <div id="envio-noticia">
        <h2 class="title-section">
            <span class="title-section__name">
                Envie a sua reportagem
            </span>
        </h2>
         <div class="buttons-center">
            <a class="btn btn-enviadas" href="#">
                <span class="icon-enviadas"></span>
                <b>Reportagens enviadas</b>
            </a>
    
            <a class="btn btn-regulamento" href="pagina/regulamento">
                <span class="icon-regulamento"></span>
                <b>Como Participar</b>
            </a>
    
            <a class="btn btn-envio-noticia active" href="#">
                <span class="icon-envio-noticia"></span>
                <b>Envie a sua reportagem</b>
            </a>
        </div>
        <form class="form" action="controller/envia-noticia" method="POST" enctype="multipart/form-data">
            <fieldset class="cf">
                <div class="block-input">
                    <label for="jp_tituloNoticia">Título da sua reportagem:</label>
                    <input id="jp_tituloNoticia" name="jp_tituloNoticia" type="text" value="" />
                </div>

                <div class="envio-noticia-anexo cf">
                    <div class="block-select">
                        <label for="jp_tipoDeAnexo">Anexo:</label>
                        <select id="jp_tipoDeAnexo" name="jp_tipoDeAnexo">
                            <option value="1">Imagem</option>
                            <option value="2">Link</option>
                        </select>
                    </div>

                    <div class="block-file">
                        <input id="jp_tipoDeAnexo-arquivo" name="jp_tipoDeAnexo-arquivo" type="file" value="" />
                    </div>

                    <div class="block-input">
                        <input id="jp_tipoDeAnexo-link" name="jp_tipoDeAnexo-link" placeholder="Cole o link aqui" type="text" value="" />
                    </div>
                </div>

                <div class="block-textarea">
                    <label for="jp_textoNoticia">Texto da sua notícia:</label>
                    <textarea id="jp_textoNoticia" name="jp_textoNoticia"></textarea>
                </div>
            </fieldset>

            <fieldset class="envio-noticia__cadastro cf">
                <div class="fl">
                    <div class="block-input">
                        <label for="jp_nome">
                            <span class="required">*</span>
                            Seu nome:
                        </label>
                        <input id="jp_nome" name="jp_nome" placeholder="Seu nome completo" type="text" value="" />
                    </div>

                    <div class="block-input">
                        <label for="jp_email">
                            <span class="required">*</span>
                            Seu email:
                        </label>
                        <input id="jp_email" name="jp_email" placeholder="" type="text" value="" />
                    </div>

                    <div class="block-input">
                        <label for="jp_cidade">Cidade em que você mora:</label>
                        <input id="jp_cidade" name="jp_cidade" placeholder="" type="text" value="" />
                    </div>
                </div>

                <div class="fr">
                    <p>
                        Estou ciente que li e concordo com os termos exigidos.
                    </p>

                    <div class="block-checkbox">
                        <input id="jp_aceito" name="jp_aceito" type="checkbox" value="1" />
                        <label for="jp_aceito">
                            Li e aceito o
                            <a id="to-reg" href="">regulamento</a>
                        </label>
                    </div>
                     <input type="hidden" name="acao" value="enviarEmail" />
                    <input class="btn btn-enviar" type="submit" value="Enviar" />
                </div>
            </fieldset>
        </form>
    </div>