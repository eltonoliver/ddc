<h2 class="title-section">
    Cursos
</h2>
<div class="post-meta">
    <?php include($raiz . 'modulos/addThis.php'); ?>
</div>
<?php
if (!isset($id) || !$id) {
    ?>
    <div class="list col-left">
        <?php
        $idTipo = 15;
        //PREPARA�AO DO PAGINADOR
        //Define o total de registros por p�gina
        $limite = 4;
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
        $strPagina = "SELECT idConteudo,nmLinkImagem,dtDataConteudo,nmTituloConteudo,nmConteudo 
				FROM vwconteudo
				WHERE idTipoConteudo = " . $db->clean($idTipo) . " AND inPublicar = 1 " . $filtro . "
				ORDER BY    dtDataConteudo DESC,dtDataCadastro desc
				LIMIT 	   " . $inicio . "," . $limite . "
		
		";
        $qryPagina = $db->query($strPagina);
        if (!$qryPagina) {
            redirecionar($url_raiz . '404');
        }
        foreach ($qryPagina as $conteudo) {
            if (vazio($conteudo["nmPaginaConteudo"])) {
                $conteudo["nmPaginaConteudo"] = 'cursos';
            }
            $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
            ?>
            <article class="list-item">
                <?php
                if (is_file($raiz . "arquivos/enviados/miniaturas/" . $conteudo["nmLinkImagem"])) {
                    ?>
                    <a class="list-item-img" href="<?php echo $nmLinkPaginaConteudo; ?>">
                        <img alt="<?php echo $conteudo["nmTituloConteudo"]; ?>" src="<?php echo $url_raiz; ?>arquivos/enviados/miniaturas/<?php echo $conteudo["nmLinkImagem"]; ?>"/>
                    </a>
                    <?php
                }
                ?>
                <h3 class="title-post">
                    <a href="<?php echo $nmLinkPaginaConteudo; ?>"><?php echo $conteudo["nmTituloConteudo"]; ?></a>
                </h3>
                <p>
                    <?php echo resume($conteudo["nmConteudo"], '300'); ?>
                    <a class="read-more" href="<?php echo $nmLinkPaginaConteudo; ?>">Continuar lendo</a>
                </p>
            </article>
        <?php } ?>
        <div class="clearBoth">
            <?php
            //NAVEGA�AO DO PAGINADOR 
            $srtQtotal = "
                            SELECT 		COUNT(*) as total_registros 
                            FROM 		vwconteudo
                            WHERE 		idTipoConteudo = " . $db->clean($idTipo) . "
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
            $filtro2 .= '/ano/' . $ano;
            if ($mes)
                $filtro2 .= '/mes/' . $mes;
            //Nome da p�gina 
            $stringPagina = $url_raiz . 'cursos' . $filtro2;
            //Chama a fun�ao que monta a exibi�ao do paginador
            navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
            ?>
        </div>
    </div>
    <?php
} else {
    $strPagina = "SELECT idConteudo,idTipoConteudo,nmConteudo,nmLinkImagem,nmTituloConteudo FROM vwconteudo WHERE idConteudo = " . $db->clean($id) . " AND inPublicar = 1 LIMIT 1";
    $qryPagina = $db->query($strPagina);
    if (!$qryPagina) {
        redirecionar($url_raiz . '404');
    }
    $idTipo = $qryPagina[0]["idTipoConteudo"];
    ?>
    <article class="col-left">
        <div class="post-text">
            <h3 class="title-post">
                <a href=""><?php echo $qryPagina[0]["nmTituloConteudo"]; ?></a>
            </h3>
            <?php
            if (is_file($raiz . "arquivos/enviados/image/" . $qryPagina[0]["nmLinkImagem"])) {
                ?>
                <img alt="<?php echo $qryPagina[0]["nmTituloConteudo"]; ?>" class="post-text-img" src="<?php echo $url_raiz . 'arquivos/enviados/image/' . $qryPagina[0]['nmLinkImagem']; ?>" />
                <?php
            }
            ?>
            <?php echo stripslashes($qryPagina[0]["nmConteudo"]); ?>
        </div>       
    </article>
<?php } ?>