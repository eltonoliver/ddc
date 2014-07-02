<h2 class="title-section">
    <span class="title-section__name">
        Acervo Fotográfico
    </span>

    <a class="title-section__more" href="javascript:history.back(-1);">
        Voltar
    </a>
</h2>

<div class="post-meta">
    <?php include($raiz . 'modulos/addThis.php'); ?>
</div>

<?php
if (!isset($id) || !$id) {
    //PREPARA�AO DO PAGINADOR
    //Define o total de registros por p�gina
    $limite = 15;
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
    $filtro .= ' AND YEAR(dtDataGaleria) = ' . $db->clean($ano);
    if ($mes)
        $filtro .= ' AND MONTH(dtDataGaleria) = ' . $db->clean($mes);
    ?>

    <div class="grid grid-galeria">
        <?php
        $str = "SELECT  c. * , g. * , i. * FROM tb_galeria AS g
                INNER JOIN tb_galeria_categoria AS c ON c.idCategoria = g.idCategoriaGaleria
                INNER JOIN tb_galeria_imagem AS i ON i.idGaleria = g.idGaleria
                WHERE g.inAtivo =1 AND g.inDestaque =1 AND i.inCapa =1 " . $filtro . " order by dtDataGaleria LIMIT " . $inicio . "," . $limite;
        $qry = $db->query($str);
        ?>

        <?php foreach ($qry as $item) { ?>
            <article class="grid-item">
                <a href="galeria/id/<?php echo $item["idGaleria"]; ?>">
                    <img id="img-galeria" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/galeria/<?php echo $item["idGaleria"]; ?>/<?php echo $item["nmImagem"]; ?>&w=270&h=174" />
                    <div class="grid-item__about">
                        <h3><?php echo resume($item["nmGaleria"], 14); ?> - <?php echo resume($item["nmCategoria"], 14); ?></h3>
                        <!-- <small><php echo dataBarrasBR($item["dtDataGaleria"]); ?></small> -->
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
                            FROM 		tb_galeria
                            WHERE		inAtivo = 1 " . $filtro . "
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
        $stringPagina = $url_raiz . 'galeria' . $filtro2;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
        ?>
    </div>
    <?php
} else {
    //PREPARA�AO DO PAGINADOR
    //Define o total de registros por p�gina
    $limite = 15;
    //Pega o n�mero da p�gina que vem pela URL
    $pagina = (int) $_GET['pag'];
    //Seta a p�gina inicial
    if (!$pagina) {
        $pagina = 1;
    }
    //Calcula os registros inicial e final as serem pesquisados no banco de dados
    $inicio = ($pagina * $limite) - $limite;
    $str = "SELECT g.idGaleria,g.nmGaleria,g.dtDataGaleria,g.nmDescricao,g.nmLocal,i.nmImagem, i.nmLegenda FROM tb_galeria g left join tb_galeria_imagem i on i.idGaleria=g.idGaleria WHERE g.inAtivo=1 and g.idGaleria=" . $id . " order by g.idGaleria LIMIT " . $inicio . "," . $limite;
    $qry = $db->query($str);
    ?>
    <div class="grid grid-galeria">
        <?php foreach ($qry as $foto) { ?>
            <article class="grid-item">
                <a data-modal='
                   <div class="modal-album-img">
                   <img src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/galeria/<?php echo $foto["idGaleria"]; ?>/<?php echo $foto["nmImagem"]; ?>&w=770&h=430" linkCurto="timthumb.php?src=<?php echo $url_raiz . "arquivos/enviados/image/" . $foto["idGaleria"] . "/" . $foto["nmImagem"]; ?>&w=912&h=588" />
                   <h3><?php echo $foto["nmGaleria"]; ?></h3>
                   <p>
                   <?php echo $foto["nmLegenda"]; ?>
                   </p>
                   </div>
                   ' class="fancybox" rel="group" href="timthumb.php?src=<?php echo $url_raiz ?>arquivos/enviados/image/<?php echo $foto["idGaleria"]; ?>/<?php echo $foto["nmImagem"]; ?>&w=912&h=588">
                    <img src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/galeria/<?php echo $foto["idGaleria"]; ?>/<?php echo $foto["nmImagem"]; ?>&w=310&h=175" linkCurto="<?php echo $url_raiz . "arquivos/enviados/image/" . $foto["idGaleria"] . "/" . $foto["nmImagem"]; ?>" />

                    <!-- <div class="grid-item__about">
                        <h3><php echo $foto["nmGaleria"]; ?></h3>
                        <small><php echo dataBarrasBR($foto["dtDataGaleria"]); ?></small> 
                    </div>-->
                </a>
            </article>
        <?php } ?>
    </div>
     <div class="clearBoth">
        <?php
        //NAVEGA�AO DO PAGINADOR 
        $srtQtotal = "
                            SELECT 		COUNT(*) as total_registros 
                            FROM 		tb_galeria_imagem
                            WHERE 		idGaleria = " . $id . "
                             
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
        $stringPagina = $url_raiz . 'galeria/id/' . $id ;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
        ?>
    </div>
    <ul id="targets">
        <li id="twitter"><a href="http://twitter.com" onclick="javascript:_gaq.push(['_trackEvent', 'outbound-article', 'twitter.com']);"></a></li>
        <li id="facebook"><a href="http://www.facebook.com" onclick="javascript:_gaq.push(['_trackEvent', 'outbound-article', 'www.facebook.com']);"></a></li>
    </ul>
<?php 
   } 

 include 'ddcProgramas.php'; 
 
 ?>