
<?php
if (!isset($titulo) || vazio($titulo)) {
    redirecionar($url_raiz . '404');
} else {
    $strPagina = "SELECT idConteudo,nmTituloConteudo,nmConteudo,nmLinkImagem FROM tb_conteudo WHERE inPublicar = 1 and nmTituloAmigavel=" . $db->clean($titulo);
    $qryPagina = $db->query($strPagina);
    if ($qryPagina && vazio($qryPagina[0]["nmPalavraChaveSecao"])) {
        $qryPagina[0]["nmPalavraChaveSecao"] = 'geral';
    }
    if (!$qryPagina) {
        redirecionar($url_raiz . '404');
    }
}
?>
<article class="post">
    <h2 class="title-section">
        <span class="title-section__name">
            <?php echo $qryPagina[0]["nmTituloConteudo"]; ?>
        </span>
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
       
        <div class="post-share"><?php include 'addThis.php'; ?></div>

        <div class="post-text">
			<?php echo stripslashes($qryPagina[0]["nmConteudo"]); ?>
        </div>
    </article>
</article>

