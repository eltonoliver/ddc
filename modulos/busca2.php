<?php
if (!isset($_REQUEST["nmTermoBusca"]) || vazio($_REQUEST["nmTermoBusca"])) {
    redirecionar($url_raiz . '404');
} else {
    $termoBusca = $_REQUEST["nmTermoBusca"];
    $filtro = ' AND idTipoConteudo NOT IN (7,14,17,10,4)';
    $filtro .= " AND (nmTituloConteudo like " . $db->clean("%" . $termoBusca . "%");
    $filtro .= " OR nmResumo like " . $db->clean("%" . $termoBusca . "%");
    $filtro .= " OR nmConteudo like " . $db->clean("%" . $termoBusca . "%") . ")";
    $filtro2 = 'nmTermoBusca/' . $termoBusca;
    //PREPARAÇAO DO PAGINADOR
    //Define o total de registros por página
    $limite = 6;
    //Pega o número da página que vem pela URL
    $pagina = $_GET['pag'];
    //Seta a página inicial
    if (!$pagina) {
        $pagina = 1;
    }
    //Calcula os registros inicial e final as serem pesquisados no banco de dados
    $inicio = ($pagina * $limite) - $limite;
    $strPagina = " 
                    SELECT      * 
                    FROM        vwconteudo
                    WHERE       inPublicar = 1 " . $filtro . "
                    order by dtDataConteudo desc
                    LIMIT      " . $inicio . "," . $limite . "
            ";
    $qryPagina = $db->query($strPagina);
    $srtQtotal = "
                SELECT      COUNT(*) as total_registros 
                FROM        vwconteudo
                WHERE       inPublicar = 1 " . $filtro . "
            ";
    //Busca o total de registros da consulta nao paginada
    $total_registros = $db->query($srtQtotal);
    $total_registros = $total_registros[0]["total_registros"];
    //Calcula o total de páginas
    $total_paginas = ceil($total_registros / $limite);
} ?>
<h2 class="title-section">
        <span class="title-section__name">
            Resultado da Busca
        </span>
		
        <ul class="title-section__archive">
            <li> <?php echo $total_registros; ?>   </li>
        </ul>
              
    </h2>

<?php if (!$qryPagina) { ?>

    <h3 class="title-normal">Nenhum resultado encontrado</h3>

    <div class="post-text">
        <p>Nenhum resultado encontrado para a busca por "<?php echo $termoBusca; ?>".</p>
    </div>

<?php } else { ?>

    <?php foreach ($qryPagina as $conteudo) { ?>
        <?php
            if (vazio($conteudo["nmPaginaConteudo"])) {
                $conteudo["nmPaginaConteudo"] = 'aquecimento';
            }
            $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/' . $conteudo["nmTituloAmigavel"];
        ?>
        <article class="blog-post">
            <header class="blog-post-header">
                <div class="blog-post-date">
                    <span><?php echo substr($conteudo["dtDataConteudo"], -2); ?></span>
                    <?php echo $meses[substr($conteudo["dtDataConteudo"], -5, -2) - 1]["siglaMes"]; ?>
                </div>

                <div class="blog-post-header-text">
                    <h2 class="blog-post-title">
                        <a href="<?php echo $nmLinkPaginaConteudo; ?>">
                            <?php echo $conteudo["nmTituloConteudo"]; ?>
                        </a>
                    </h2>
                </div>
            </header>

            <?php if (is_file("arquivos/enviados/image/" . $conteudo["nmLinkImagem"])) { ?>
                <div class="blog-post-img">
                    <a href="<?php echo $nmLinkPaginaConteudo; ?>">
                        <img src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $conteudo["nmLinkImagem"]; ?>&w=155&h=155" />
                    </a>
                </div>
            <?php } ?>
            <div class="blog-post-resume">
                <?php echo resume($conteudo["nmConteudo"], 480); ?>
                <a class="read-more" href="<?php echo $nmLinkPaginaConteudo; ?>">Continue lendo.</a>
            </div>
        </article>
    <?php } ?>
    <div class="clearBoth">
        <?php
            //Nome da página 
            $stringPagina = $url_raiz . 'busca/' . $filtro2;
            //Chama a função que monta a exibição do paginador
            navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
        ?>
    </div>
<?php } ?>