<?php 
  if (!isset($titulo) || !$titulo) {
	  
    // Define o tipo de conteúdo que vai ser carregado através do SQL
    $idTipoConteudo = '34';
    //PREPARA�AO DO PAGINADOR
    //Define o total de registros por p�gina
    $limite = 31;
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
    $sqlCharge = "SELECT idConteudo,nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo,nmListaTags FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= " . $idTipoConteudo . "  " . $filtro . " order by dtDataConteudo DESC LIMIT " . $inicio . "," . $limite;
    // Query de acordo com a requisicao do usuario
    $qryCharge = $db->query($sqlCharge)
?>

    <h2 class="title-section">
        <span class="title-section__name">
            Charge do Dia
        </span>

        <ul class="title-section__archive">
            <li>
                <a >Arquivo</a>
               <ul>
                    <?php
                    $qryArquivo = $db->query("SELECT year(dtDataConteudo) as ano,month(dtDataConteudo) as mes, count(idConteudo) as noticias FROM tb_conteudo WHERE idTipoConteudo=34 AND inPublicar = 1 group by ano,mes order by ano desc, mes DESC");
                    $meses = count($qryArquivo);
                    foreach ($qryArquivo as $i => $array) {
                        if ($i == 0 or $qryArquivo[$i - 1]["ano"] != $array["ano"]) {
                            ?>
                            <li>
                                <a href="charges/ano/<?php echo $array["ano"]; ?>"><?php echo $array["ano"]; ?></a>
                                <ul>
                                <?php } ?>
                                <li>
                                    <a href="charges/ano/<?php echo $array["ano"]; ?>/mes/<?php echo $array["mes"]; ?>"><?php echo nomeMes($array["mes"]); ?> <i>(<?php echo $array["noticias"]; ?>)</i></a>
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
    <div class="grid grid-galeria grid-charge">
        <?php foreach ($qryCharge as $charge) { ?>

        <article class="grid-item">
            <a data-modal='
                            <div class="modal-album-img">
                                <img src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $charge["nmLinkImagem"]; ?>&w=912&h=588" />
                                <h4><?php echo $charge["nmTituloConteudo"]; ?></h4>

                                <small><?php echo date('d/m/Y', strtotime($charge[data])); ?></small>
                            </div>
            ' class="fancybox" rel="group" href="#">
                <img src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $charge["nmLinkImagem"]; ?>&w=270&h=174" />  
                <div class="grid-item__about">
                    <h4><?php echo $charge["nmTituloConteudo"]; ?></h4>

                    <small><?php echo date('d/m/Y', strtotime($charge[data])); ?> </small>
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
        $stringPagina = $url_raiz . 'charges' . $filtro2;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
        ?>
    </div>

<?php } else {
    $strPagina = "SELECT idConteudo,nmTituloAmigavel,nmUsuario, dtDataConteudo as dtCompleta, day(dtDataConteudo) as dia,month(dtDataConteudo) as mes,nmLinkImagem,nmTituloConteudo,nmConteudo,inComentario,idConteudoRelacionado FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE nmTituloAmigavel= " . $db->clean($titulo) . " AND inPublicar = 1 LIMIT 1";
    $qryPagina = $db->query($strPagina);

    if (!$qryPagina) {
        redirecionar($url_raiz . '404');
    }
?>

    <article class="post">
        <h2 class="title-section">
            <span class="title-section__name">
                Charge do Dia
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
             <?php echo $qryPagina[0]["nmTituloConteudo"]; ?>
        </h3>

        <div class="post-meta">
            <b>Em:</b> <?php echo date('d/m/Y', strtotime($qryPagina[0]["dtCompleta"])); ?> |
            <b>Por:</b> <a title="Mais artigos escritas por:" href="#"><?php echo $qryPagina[0]["nmUsuario"]; ?></a> |
            <b>Tags:</b> <a href="">Durango</a>, <a href="">Leandro</a>, <a href="">Lorem</a>, <a href="">Ipsum</a>, <a href="">Dolor</a>
        </div>

        <div class="post-share"><?php include 'addThis.php'; ?></div>

        <div class="post-text">
            <?php echo $qryPagina[0]['nmConteudo']; ?>
        </div>

        <div class="post-about cf">
          <?php
                $dadosAutor = "SELECT tb1.*, COUNT(tb1.nmTituloConteudo) FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON ( tb1.idConteudo = tb2.idConteudoRelacionado ) WHERE tb2.idConteudoRelacionado = ".$qryPagina[0]['idConteudoRelacionado'].";";
                $qry = $db->query($dadosAutor);
                foreach ($qry as $autor) {
          ?>
            <img alt="<?php echo $autor["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $autor["nmLinkImagem"]; ?>&w=100&h=100" />

            <div class="post-about__autor">
                <?php echo $autor["nmTituloConteudo"]; ?>
            </div>

            <p>
                <?php echo $autor["nmResumo"]; ?>
            </p>

            <div class="post-about__social">
                <a class="facebook" title="Facebook: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkFacebook"]; ?>"></a>
                <a class="twitter" title="Twitter: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkTwitter"]; ?>"></a>
                <a class="linkedin" title="Linkedin: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkLinkedin"]; ?>"></a>
                <a class="wikipedia" title="Wikipédia: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkWikipedia"]; ?>"></a>
                <a class="instagram" title="Instagram: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkInstagram"]; ?>"></a>
                <a class="googleplus" title="Google+: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkGoogle"]; ?>"></a>
            </div>
            <?php } ?>
        </div>
    </article>

    <?php
        include 'ddcProgramas.php';
    ?>
<?php } ?>