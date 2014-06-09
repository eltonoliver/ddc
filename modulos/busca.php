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

        //PREPARACAO DO PAGINADOR
        //Define o total de registros por pagina
        $limite = 6;

        //Pega o numero da pagina que vem pela URL
        $pagina = $_GET['pag'];

        //Seta a pagina inicial
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

        $iduser = $strPagina["idUsuarioCadastro"];
        $qryPagina = $db->query($strPagina);

        $strUsuario = "SELECT * FROM tb_usuario WHERE idUsuario = '$iduser'";
        $qryUsuario = $db->query($strUsuario);

        $srtQtotal = "
                    SELECT      COUNT(*) as total_registros 
                    FROM        vwconteudo
                    WHERE       inPublicar = 1 " . $filtro . "
                ";

        //Busca o total de registros da consulta nao paginada
        $total_registros = $db->query($srtQtotal);
        $total_registros = $total_registros[0]["total_registros"];
        //Calcula o total de paginas
        $total_paginas = ceil($total_registros / $limite);
    }
?>

<h2 class="title-section">
    <span class="title-section__name">
        Resultados da Busca
    </span>

    <ul class="title-section__archive">
        <li><?php echo $total_registros; ?></li>
    </ul>
</h2>

<?php if (!$qryPagina) { ?>

    <article>
        <h3 class="post-title">Nenhum resultado encontrado</h3>

        <div class="post-text">
            <p>
                Não encontramos nada com o termo <b><i><?php echo $termoBusca; ?></i></b>.
            </p>
        </div>
    </article>

<?php } else { ?>
    <div class="list">
        <?php foreach ($qryPagina as $conteudo) { ?>
            <?php
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $caminho  = "arquivos/enviados/file/". $conteudo['nmNomeArquivo'];
                    $nmLinkPaginaConteudo = $url_raiz . $caminho;
                } else if (vazio($conteudo['nmNomeArquivo'])) {
                    $caminho  = "";
                    $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/' . $conteudo['nmTituloAmigavel'];
                } else {
                    $caminho  = "";
                    $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/' . $conteudo['nmTituloAmigavel'];
                }
            ?>
                <article class="list-item cf">
                    <a class="list-item__img" href="<?php echo $nmLinkPaginaConteudo; ?>" target="_blank">
                        <img alt="<?php echo $conteudo['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $conteudo['nmLinkImagem']; ?>&w=310&h=310" />
                    </a>

                    <h3>
                        <a href="<?php echo $nmLinkPaginaConteudo; ?>"><?php echo $conteudo['nmTituloConteudo']; ?></a>
                    </h3>

                    <p>
                        <?php echo resume($conteudo['nmConteudo'], 270); ?>
                        <a class="more" href="<?php echo $nmLinkPaginaConteudo; ?>">Continue lendo</a>
                    </p>
                </article>
        <?php } ?>
    </div>

    <?php
        //Nome da página 
        $stringPagina = $url_raiz . 'busca/' . $filtro2;
        //Chama a função que monta a exibição do paginador
        navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
    ?>
<?php } ?>