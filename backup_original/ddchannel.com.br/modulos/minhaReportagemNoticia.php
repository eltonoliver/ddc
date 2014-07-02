<?php if (!isset($titulo) || !$titulo) {
	
	// Define o tipo de conteúdo que vai ser carregado através do SQL
	$idTipoConteudo = '32';
	
	$sqlArquivo = 'SELECT year(dtDataConteudo) as ano,month(dtDataConteudo) as mes, count(idConteudo) as indicadores FROM tb_conteudo WHERE idTipoConteudo= '. $idTipoConteudo .' AND inPublicar = 1 group by ano,mes order by ano desc, mes asc';
	$qryArquivo = $db->query($sqlArquivo);
	
	// SQL para notícias padrão
	$sqlIndicSoc = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." order by dtDataConteudo desc limit 4 ";
	
	$sqlPesquisa = "SELECT * FROM vwconteudo WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." ";
	
	// Caso vier pela URL ano ou mês pega como parâmetro
	$anoPesquisa = $_REQUEST['ano'];
	$mesPesquisa = $_REQUEST['mes'];
	
	// Cria filtro por mês ou ano
	if (($anoPesquisa) && ($mesPesquisa)) {
		$sqlPesquisa = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." AND YEAR(dtDataConteudo) = " . $db->clean($anoPesquisa) . " AND MONTH(dtDataConteudo) = " . $db->clean($mesPesquisa) . " order by dtDataConteudo desc limit 10 ";
	}
	else if ($_REQUEST['ano']) {
		$sqlPesquisa = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." AND YEAR(dtDataConteudo) = " . $db->clean($anoPesquisa) . " order by dtDataConteudo desc limit 10 ";
	} 
	
	// Query de acordo com a requisição do usuário
	$qryPesquisa = $db->query($sqlPesquisa)
 	
?>
    
    <h2 class="title-section">
        <span class="title-section__name">
            Resultado das Pesquisas
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
                        <a href="noticias/ano/<?php echo $array["ano"]; ?>">
                        	<?php
                				echo $array["ano"];
                        	?>
                        </a>
                    <?php } ?> 
                        <ul>
                            <li>
                                <a href="noticias/ano/<?php echo $array["ano"]; ?>/mes/<?php echo $array["mes"]; ?>"><?php echo nomeMes($array["mes"]); ?></a>
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

    <?php echo resume($string, $char)  ?>

    <div class="list list-pesquisa">
    	<?php foreach ($qryPesquisa as $pesquisa) { ?>
            <article class="list-item cf">
                <a class="list-item__img" href="reportagem-historica/<?php echo $pesquisa['nmTituloAmigavel']; ?>" >
                    <img alt="<?php echo $pesquisa['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $pesquisa['nmLinkImagem']; ?>&w=310&h=310" />
                </a>

                <h3>
                    <a href="reportagem-historica/<?php echo $pesquisa['nmTituloAmigavel']; ?>" ><?php echo $pesquisa['nmTituloConteudo']; ?></a>
                </h3>

                <div class="list-item__meta">
                    <b>Realizado em:</b> <?php echo date('d/m/Y', strtotime($pesquisa['dtDataConteudo'])); ?> |
                    <b>Tags:</b> <a href="">Durango</a>, <a href="">Leandro</a>, <a href="">Lorem</a>, <a href="">Ipsum</a>, <a href="">Dolor</a>
                </div>

                <p></p>
            </article>
       	<?php } ?>
	</div>

<?php } else { ?> 

   <?php
        $strPagina = "SELECT idConteudo,nmTituloAmigavel,nmUsuario, dtDataConteudo as dtCompleta, day(dtDataConteudo) as dia,month(dtDataConteudo) as mes,nmLinkImagem,nmTituloConteudo,nmConteudo,inComentario FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE nmTituloAmigavel= " . $db->clean($titulo) . " AND inPublicar = 1 LIMIT 1";
        $qryPagina = $db->query($strPagina);
        if (!$qryPagina) {
            redirecionar($url_raiz . '404');
        }
    ?>

        <article class="post">
            <h2 class="title-section">
                <span class="title-section__name">
                    Minha Reportagem
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
                <b>Por:</b> <a title="Mais notícias escritas por:" href="#"><?php echo $qryPagina[0]["nmUsuario"]; ?></a> |
                <b>Tags:</b> <a href="">Durango</a>, <a href="">Leandro</a>, <a href="">Lorem</a>, <a href="">Ipsum</a>, <a href="">Dolor</a>
            </div>
            
            <div class="post-share"><?php include 'addThis.php'; ?></div>

            <div class="post-text">
    			<?php echo $qryPagina[0]['nmConteudo']; ?>
            </div>
        </article>

        <?php include 'ddcProgramas.php'; ?>
<?php } ?> 