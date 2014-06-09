<?php
$autor = mysql_real_escape_string(strip_tags($_GET['autor']));
if ((!isset($autor) || !$autor)) {
    // Define o tipo de conteúdo que vai ser carregado através do SQL
    $idTipoConteudo = '15';
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
    // SQL para notícias padrão
    /* $sqlArticulista = "SELECT DISTINCT tb1.* FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON tb1.idConteudo = tb2.idConteudoRelacionado WHERE tb1.nmTituloAmigavel!='durango-duarte' and tb1.idTipoConteudo= " . $idTipoConteudo . "  order by tb1.idConteudo DESC LIMIT " . $inicio . "," . $limite; */
    $sqlArticulista = "SELECT DISTINCT tb1 . * FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON tb1.idConteudo = tb2.idConteudoRelacionado WHERE tb1.idConteudo <>  62 AND tb1.idTipoConteudo= 15 ORDER BY idConteudo ASC LIMIT " . $inicio . "," . $limite ."";

    // Query de acordo com a requisição do usuário
    $qryArticulista = $db->query($sqlArticulista);
    ?>

    <h2 class="title-section">
        <span class="title-section__name">
            Articulistas
        </span> 
    </h2>

    <div class="list">
        <?php
        foreach ($qryArticulista as $articulista) {
            ?>

            <article class="list-item cf">       	
                <a class="list-item__img" href="articulistas/autor/<?php echo $articulista['nmTituloAmigavel']; ?>">
                    <img alt="<?php echo $articulista['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $articulista['nmLinkImagem']; ?>&w=310&h=310" />
                </a>

                <h3 style="margin-bottom:0;">
                  <a href="articulistas/autor/<?php echo $articulista['nmTituloAmigavel']; ?>"><?php echo $articulista['nmTituloConteudo']; ?></a>
                </h3>

                <p style="margin-bottom:5px;">
                    <span > <?php echo stripslashes(resume($articulista["nmResumo"], 50)); ?></span>
                    <?php echo stripslashes(resume($articulista['nmConteudo'], 380)); ?> 
                </p>

                <div class="list-item__meta">
                    <b>Em:</b> <?php echo date('d/m/Y', strtotime($articulista['dtDataConteudo'])); ?>  
                </div>
            </article>

            <?php
        }
        ?>
    </div>     		
    <div class="clearBoth">
        <?php
        //NAVEGA�AO DO PAGINADOR 
        $srtQtotal = "SELECT COUNT(*) AS total_registros FROM ( SELECT DISTINCT tb1 . * FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON tb1.idConteudo = tb2.idConteudoRelacionado WHERE tb1.idConteudo = 62 UNION SELECT DISTINCT tb1 . * FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON tb1.idConteudo = tb2.idConteudoRelacionado WHERE tb1.idConteudo <>  62 AND tb1.idTipoConteudo= 15) QUERY; ";
        //Busca o total de registros da consulta nao paginada
        $total_registros = $db->query($srtQtotal);
        $total_registros = $total_registros[0]["total_registros"];
        //Calcula o total de p�ginas
        $total_paginas = ceil($total_registros / $limite);
        $filtro2 = '';
        //Nome da p�gina 
        $stringPagina = $url_raiz . 'articulistas' . $filtro2;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
        ?>
    </div>

    <?php
} else {
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
    $strPagina = "SELECT DISTINCT tb2.* FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON tb1.idConteudo = tb2.idConteudoRelacionado WHERE tb1.nmTituloAmigavel = " . $db->clean($autor) . " AND tb2.inPublicar = 1 ORDER BY tb2.idConteudo DESC LIMIT " . $inicio . "," . $limite;
    $qryPagina = $db->query($strPagina);
    $idConteudoRel = $qryPagina[0]['idConteudoRelacionado'];
    if (!$qryPagina) {
        redirecionar($url_raiz . '404');
    }
    ?>
    <h2 class="title-section">
        <span class="title-section__name">
            Artigos
        </span> 
    </h2>
    <div class="list">

        <?php
        foreach ($qryPagina as $articula) {
            ?>
            <article class="list-item cf">     
                <?php if (is_file("arquivos/enviados/image/" . $articula["nmLinkImagem"])) { ?>  	
                <a class="list-item__img" href="artigos/<?php echo $articula['nmTituloAmigavel']; ?>">
                        <img alt="<?php echo $articula['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $articula['nmLinkImagem']; ?>&w=310&h=310" />
                    </a>
                    <h3>
                        <a href="artigos/<?php echo stripslashes($articula['nmTituloAmigavel']); ?>"><?php echo stripslashes($articula['nmTituloConteudo']); ?></a>
                    </h3>
                    <p>
                        <?php echo resume($articula['nmConteudo'], 270); ?>
                        <a class="more" href="artigos/<?php echo stripslashes($articula['nmTituloAmigavel']); ?>">Continue lendo</a>
                    </p>
                    <div class="list-item__meta">
                        <b>Em:</b> <?php echo date('d/m/Y', strtotime($articula['dtDataConteudo'])); ?> |
                        <b>Por:</b> <?php
                        $dadosAutor = "SELECT tb1.*, COUNT(tb1.nmTituloConteudo) FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON (tb1.idConteudo = tb2.idConteudoRelacionado ) WHERE tb2.idConteudoRelacionado = " . $articula['idConteudoRelacionado'] . ";";
                        $qry = $db->query($dadosAutor);
                        foreach ($qry as $autor) {
                            ?>
                            <a> <?php echo $autor["nmTituloConteudo"]; ?> </a> 
                        <?php } ?>
                    </div>

                <?php } else { ?>
                    <h3>
                        <a href="artigos/<?php echo stripslashes($articula['nmTituloAmigavel']); ?>"><?php echo stripslashes($articula['nmTituloConteudo']); ?></a>
                    </h3>
                    <p>
                        <?php echo resume($articula['nmConteudo'], 270); ?>
                        <span> <?php echo $articula["nmResumo"]; ?></span>
                    </p>
                    <div class="list-item__meta">
                        <b>Em:</b>  <?php echo date('d/m/Y', strtotime($articula['dtDataConteudo'])); ?> |
                        <b>Por:</b> <?php
                        $dadosAutor = "SELECT tb1.*, COUNT(tb1.nmTituloConteudo) FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON (                                              tb1.idConteudo = tb2.idConteudoRelacionado ) WHERE tb2.idConteudoRelacionado = " . $articula['idConteudoRelacionado'] . ";";
                        $qry = $db->query($dadosAutor);
                        foreach ($qry as $autor) {
                            ?>
                            <a> <?php echo $autor["nmTituloConteudo"]; ?> </a> 
                        <?php } ?>
                    </div>

                <?php } ?> 
            </article>
        <?php } ?>
    </div>     
    <div class="clearBoth">
        <?php
        //NAVEGA�AO DO PAGINADOR 
        $autor = mysql_real_escape_string(strip_tags($_GET['autor']));
        $srtQtotal = "SELECT COUNT(*) as total_registros FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON tb1.idConteudo = tb2.idConteudoRelacionado WHERE tb1.nmTituloAmigavel = " . $db->clean($autor) . " AND tb2.inPublicar = 1";
        //Busca o total de registros da consulta nao paginada
        $total_registros = $db->query($srtQtotal);
        $total_registros = $total_registros[0]["total_registros"];
        //Calcula o total de p�ginas
        $total_paginas = ceil($total_registros / $limite);
        $filtro2 = '';
        if ($autor)
            $filtro2 .= '/autor/' . $autor;
        //Nome da p�gina 
        $stringPagina = $url_raiz . 'articulistas' . $filtro2;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
        ?>
    </div>		

    <?php
    include 'ddcProgramas.php';
    ?>
<?php } ?>