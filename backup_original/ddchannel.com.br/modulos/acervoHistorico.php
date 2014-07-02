<?php
if (!isset($titulo) || !$titulo) {
    // Define o tipo de conteudo que vai ser carregado atraves do SQL
    $idTipoConteudo = '27';

    $sqlArquivo = 'SELECT year(dtDataConteudo) as ano,month(dtDataConteudo) as mes, count(idConteudo) as indicadores FROM tb_conteudo WHERE idTipoConteudo= ' . $idTipoConteudo . ' AND inPublicar = 1 group by ano,mes order by ano desc, mes asc';
    $qryArquivo = $db->query($sqlArquivo);

    // SQL para noticias padrao

    $sqlRepHist = "SELECT * FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= " . $idTipoConteudo . " order by idConteudo desc ";

    // Caso vier pela URL ano ou mes pega como parametro
    $anoRepHist = $_REQUEST['ano'];
    $mesRepHist = $_REQUEST['mes'];

    // Cria filtro por mes ou ano
    if (($anoRepHist) && ($mesRepHist)) {
        $sqlRepHist = "SELECT idConteudo,nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= " . $idTipoConteudo . " AND YEAR(dtDataConteudo) = " . $db->clean($anoRepHist) . " AND MONTH(dtDataConteudo) = " . $db->clean($mesRepHist) . " order by dtDataConteudo desc limit 10 ";
    } else if ($_REQUEST['ano']) {
        $sqlRepHist = "SELECT idConteudo,nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= " . $idTipoConteudo . " AND YEAR(dtDataConteudo) = " . $db->clean($anoRepHist) . " order by dtDataConteudo desc limit 10 ";
    }

    // Query de acordo com a requisicao do usuario
    $qryRepHist = $db->query($sqlRepHist)
    ?>

    <h2 class="title-section">
        <span class="title-section__name">
            Acervo Histórico
        </span>

        <ul class="title-section__archive">
            <li>
                <a >Arquivo</a>
                <ul>
                    <?php
                    $meses = count($qryArquivo);
                    foreach ($qryArquivo as $i => $array) {
                        if ($i == 0 || $qryArquivo[$i - 1]["ano"] != $array["ano"]) {
                            ?>
                            <li>
                                <a href="acervo-historico/ano/<?php echo $array["ano"]; ?>">
                                    <?php
                                    echo $array["ano"];
                                    ?>
                                </a>
                            <?php } ?>
                            <ul>
                                <li>
                                    <a href="acervo-historico/ano/<?php echo $array["ano"]; ?>/mes/<?php echo $array["mes"]; ?>"><?php echo nomeMes($array["mes"]); ?></a>
                                    <?php if ($i == $meses or $qryArquivo[$i + 1]["ano"] != $array["ano"]) { ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                </ul>
            </li>
        </ul>
    </h2>

    <?php echo resume($string, $char) ?>

    <div class="list">
        <?php
        foreach ($qryRepHist as $rephistorica) {
            ?>
            <article class="list-item cf">
                <a class="list-item__img" href="<?php echo $rephistorica['nmLinkExterno']; ?>" target="_blank">
                    <img alt="<?php echo $rephistorica['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $rephistorica['nmLinkImagem']; ?>&w=310&h=310" />
                </a>

                <h3>
                    <a href="<?php echo $rephistorica['nmLinkExterno']; ?>"><?php echo $rephistorica['nmTituloConteudo']; ?></a>
                </h3>

                <p>
                    <?php echo resume($rephistorica['nmConteudo'], 270); ?>
                    <a class="more" href="<?php echo $rephistorica['nmLinkExterno']; ?>">Continue lendo</a>
                </p>

                <div class="list-item__meta">
                    <b>Em:</b> <?php echo date('d/m/Y', strtotime($rephistorica['dtDataConteudo'])); ?> |

                    <b>Tags:</b> <?php
                    $sqlTags = "SELECT tb_categoria.nmCategoria FROM tb_conteudo INNER JOIN tb_conteudo_tag ON                                              tb_conteudo_tag.idConteudo = tb_conteudo.idConteudo INNER JOIN tb_categoria ON                                              tb_categoria.idCategoria = tb_conteudo_tag.idCategoria WHERE tb_conteudo.idConteudo = " . $rephistorica['idConteudo'] . ";";
                    $qryTags = $db->query($sqlTags);

                    foreach ($qryTags as $tags) {
                        ?>
                        <a> <?php echo $tags['nmCategoria']; ?> </a>
                    <?php } ?>
                </div>
            </article>

        <?php } ?>
    </div>

    <?php
} else {
    $strPagina = "SELECT idConteudo,nmTituloAmigavel,nmUsuario, dtDataConteudo as dtCompleta, day(dtDataConteudo) as dia,month(dtDataConteudo) as mes,nmLinkImagem,nmTituloConteudo,nmConteudo,inComentario FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE nmTituloAmigavel= " . $db->clean($titulo) . " AND inPublicar = 1 LIMIT 1";
    $qryPagina = $db->query($strPagina);
    if (!$qryPagina) {
        redirecionar($url_raiz . '404');
    }
    ?>
    <article class="post">
        <h2 class="title-section">
            <span class="title-section__name">
                Acervo Histórico
            </span>

            <a class="title-section__more" href="javascript:history.back(-1);">
                Voltar
            </a>
        </h2>

        <div class="post-img">
            <?php if (is_file("arquivos/enviados/image/" . $qryPagina[0]["nmLinkImagem"])) { ?>
                <img alt="<?php echo $qryPagina[0]["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qryPagina[0]["nmLinkImagem"]; ?>&w=780&h=440" />
            <?php } ?>
        </div>

        <h3 class="post-title">
            <?php echo $qryPagina[0]["nmTituloConteudo"]; ?>
        </h3>

        <div class="post-meta">
            <b>Em:</b> <?php echo date('d/m/Y', strtotime($qryPagina[0]["dtCompleta"])); ?> |

            <b>Tags:</b> 
            <?php
            $sqlTags = "SELECT tb_categoria.nmCategoria FROM tb_conteudo INNER JOIN tb_conteudo_tag ON                                              tb_conteudo_tag.idConteudo = tb_conteudo.idConteudo INNER JOIN tb_categoria ON                                              tb_categoria.idCategoria = tb_conteudo_tag.idCategoria WHERE tb_conteudo.idConteudo = " . $qryPagina[0]['idConteudo'] . ";";
            $qryTags = $db->query($sqlTags);

            foreach ($qryTags as $tags) {
                ?>
                <a> <?php echo $tags['nmCategoria']; ?> </a>
            <?php } ?>
        </div>

        <div class="post-share"><?php include 'addThis.php'; ?></div>

        <div class="post-text">
            <?php echo stripslashes($qryPagina[0]['nmConteudo']); ?>
        </div>
    </article>

    <?php include 'ddcProgramas.php'; ?>

<?php } ?> 