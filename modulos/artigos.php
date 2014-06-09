<?php if (!isset($titulo) || !$titulo) {
	
	// Define o tipo de conteúdo que vai ser carregado através do SQL
	$idTipoConteudo = '26';
	
	$sqlArquivo = 'SELECT year(dtDataConteudo) as ano,month(dtDataConteudo) as mes, count(idConteudo) as noticias FROM tb_conteudo WHERE idTipoConteudo= '. $idTipoConteudo .' AND inPublicar = 1 group by ano,mes order by ano desc, mes asc';
	$qryArquivo = $db->query($sqlArquivo);
	// SQL para notícias padrão
	$sqlNoticias = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." order by dtDataConteudo desc limit 4 ";
	
	// Caso vier pela URL ano ou mês, pega como parâmetro
	$anoNoticias = $_REQUEST['ano'];
	$mesNoticias = $_REQUEST['mes'];
	
	// Cria filtro por mês ou ano
	if (($anoNoticias) && ($mesNoticias)) {
		$sqlNoticias = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." AND YEAR(dtDataConteudo) = " . $db->clean($anoNoticias) . " AND MONTH(dtDataConteudo) = " . $db->clean($mesNoticias) . " order by dtDataConteudo desc limit 10 ";
	}
	else if ($_REQUEST['ano']) {
		$sqlNoticias = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." AND YEAR(dtDataConteudo) = " . $db->clean($anoNoticias) . " order by dtDataConteudo desc limit 10 ";
	} 
	
	// Query de acordo com a requisição do usuário
	$qryNoticias = $db->query($sqlNoticias)
	
?>
    
    <h2 class="title-section">
        <span class="title-section__name">
            Artigos
        </span>
		
        <ul class="title-section__archive">
            <li>
                <a href="#">Arquivo</a>
                <ul>
                    <?php
	                	$meses = count($qryArquivo);
	                	foreach ($qryArquivo as $i => $array) {
	                		if ($i == 0 || $qryArquivo[$i - 1]["ano"] != $array["ano"]) {
                	?>
                    <li>
                        <a href="artigos/ano/<?php echo $array["ano"]; ?>">
                        	<?php
                				echo $array["ano"];
                        	?>
                        </a>
                    <?php } ?> 
                        <ul>
                            <li>
                                <a href="artigos/ano/<?php echo $array["ano"]; ?>/mes/<?php echo $array["mes"]; ?>"><?php echo nomeMes($array["mes"]); ?></a>
                                <?php if ($i == $meses or $qryArquivo[$i + 1]["ano"] != $array["ano"]) { ?>
                            </li>
                    <?php
						}
					?>                     
                        </ul>
					<?php
						}
					?>      				
                    </li>
                </ul>
            </li>
        </ul>             
    </h2>

     <div class="list">

        <?php
        foreach ($qryNoticias as $articula) {
            ?>
            <article class="list-item cf">     
                <?php if (is_file("arquivos/enviados/image/" . $articula["nmLinkImagem"])) { ?>  	
                    <a class="list-item__img" href="artigos/<?php echo $articula['nmTituloAmigavel']; ?>">
                        <img alt="<?php echo $articula['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $articula['nmLinkImagem']; ?>&w=310&h=310" />
                    </a>
                    <h3>
                        <a href="artigos/<?php echo htmlspecialchars($articula['nmTituloAmigavel']); ?>"><?php echo $articula['nmTituloConteudo']; ?></a>
                    </h3>
                    <p>
                        <?php echo resume($articula['nmConteudo'], 270); ?>
                        <a class="more" href="artigos/<?php echo $articula['nmTituloAmigavel']; ?>">Continue lendo</a>
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
                        <a href="artigos/<?php echo $articula['nmTituloAmigavel']; ?>"><?php echo $articula['nmTituloConteudo']; ?></a>
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
 		
<?php } else {
    $titulo = stripslashes($titulo);
    $strPagina = "SELECT idConteudo,nmTituloAmigavel,nmUsuario, dtDataConteudo as dtCompleta, day(dtDataConteudo) as dia,month(dtDataConteudo) as mes,nmLinkImagem,nmTituloConteudo,nmConteudo,inComentario,idConteudoRelacionado FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE nmTituloAmigavel= " . $db->clean($titulo) . " AND inPublicar = 1 LIMIT 1";
    $qryPagina = $db->query($strPagina);
    if (!$qryPagina) {
        redirecionar($url_raiz . '404');
    }
    ?>

    <article class="post">
        <h2 class="title-section">
            <span class="title-section__name">
                Artigos
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
					<?php
					  $dadosAutor = "SELECT tb1.*, COUNT(tb1.nmTituloConteudo) FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON ( tb1.idConteudo = tb2.idConteudoRelacionado ) WHERE tb2.idConteudoRelacionado = " . $qryPagina[0]['idConteudoRelacionado'] . ";";
					  $qry = $db->query($dadosAutor);
					  foreach ($qry as $autor) {
                      ?>
            <b>Em:</b> <?php echo date('d/m/Y', strtotime($qryPagina[0]["dtCompleta"])); ?> |
            <b>Por:</b> <a title="Mais artigos escritos por:<?php echo $autor["nmTituloConteudo"]; ?>" href="articulistas/<?php echo $autor["nmTituloAmigavel"]; ?>"><?php echo $autor["nmTituloConteudo"]; ?></a> 
        </div>
        
        <div class="post-share"><?php include 'addThis.php'; ?></div>

        <div class="post-text">
			<?php echo stripslashes($qryPagina[0]['nmConteudo']); ?>
        </div>
        <div class="post-about cf">
          
            <img alt="<?php echo $autor["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $autor["nmLinkImagem"]; ?>&w=100&h=100" />
            <div class="post-about__autor">
                <?php echo stripslashes($autor["nmTituloConteudo"]); ?>
            </div>

            <p>
                <?php echo stripslashes($autor["nmResumo"]); ?>
            </p>

            <div class="post-about__social">
               <?php
                 if (!empty($autor["linkFacebook"])) {
                ?>
                <a class="facebook" title="Facebook: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkFacebook"]; ?>"></a>  <?php } ?>
                 <?php
                  if (!empty($autor["linkTwitter"])) {
                ?>
                <a class="twitter" title="Twitter: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkTwitter"]; ?>"></a>  <?php } ?>
                 <?php
                 if (!empty($autor["linkLinkedin"])) {
                ?>
                <a class="linkedin" title="Linkedin: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkLinkedin"]; ?>"></a> <?php } ?>
                 <?php
                 if (!empty($autor["linkWikipedia"])) {
                ?>
                <a class="wikipedia" title="Wikipédia: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkWikipedia"]; ?>"></a> <?php } ?>
                 <?php
                 if (!empty($autor["linkInstagram"])) {
                ?>
                <a class="instagram" title="Instagram: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkInstagram"]; ?>"></a> <?php } ?>
                 <?php
                 if (!empty($autor["linkGoogle"])) {
                ?>
                <a class="googleplus" title="Google+: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkGoogle"]; ?>"></a> <?php } ?>
                 <?php
                 if (!empty($autor["linkGoogle"])) {
                ?>
                <a class="googleplus" title="Google+: <?php echo $autor["nmTituloConteudo"]; ?>" target="_blank" href=" <?php echo $autor["linkGoogle"]; ?>"></a> <?php } ?>
            </div>
            <?php } ?>
        </div>
    </article>
    
    <?php
    	include 'ddcProgramas.php';
    ?>
        
       
<?php } ?>