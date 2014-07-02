<?php if (!isset($titulo) || !$titulo) {
	
	// Define o tipo de conteúdo que vai ser carregado através do SQL
	$idTipoConteudo = '29';
	
	$sqlArquivo = 'SELECT year(dtDataConteudo) as ano,month(dtDataConteudo) as mes, count(idConteudo) as indicadores FROM tb_conteudo WHERE idTipoConteudo= '. $idTipoConteudo .' AND inPublicar = 1 group by ano,mes order by ano desc, mes asc';
	$qryArquivo = $db->query($sqlArquivo);
	
	// SQL para notícias padrão
	$sqlIndicSoc = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." order by dtDataConteudo desc limit 4 ";
	
	$sqlIndicSoc = "SELECT * FROM vwconteudo WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." ";
	
	// Caso vier pela URL ano ou mês pega como parâmetro
	$anoIndicSoc = $_REQUEST['ano'];
	$mesIndicSoc = $_REQUEST['mes'];
	
	// Cria filtro por mês ou ano
	if (($anoIndicSoc) && ($mesIndicSoc)) {
		$sqlIndicSoc = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." AND YEAR(dtDataConteudo) = " . $db->clean($anoIndicSoc) . " AND MONTH(dtDataConteudo) = " . $db->clean($mesIndicSoc) . " order by dtDataConteudo desc limit 10 ";
	}
	else if ($_REQUEST['ano']) {
		$sqlIndicSoc = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= ". $idTipoConteudo ." AND YEAR(dtDataConteudo) = " . $db->clean($anoIndicSoc) . " order by dtDataConteudo desc limit 10 ";
	} 
	
	// Query de acordo com a requisição do usuário
	$qryIndicSoc = $db->query($sqlIndicSoc)
 	
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
    	<?php
			foreach ($qryIndicSoc as $indicsoc) { 
		?>
		
        <article class="list-item cf">
        	
            <a class="list-item__img" href="<?php echo $url_raiz; ?>arquivos/enviados/file/<?php echo $indicsoc['nmNomeArquivo']; ?>" target="_blank">
                <img alt="<?php echo $indicsoc['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $indicsoc['nmLinkImagem']; ?>&w=310&h=310" />
            </a>

            <h3>
                <a href="<?php echo $url_raiz; ?>arquivos/enviados/file/<?php echo $indicsoc['nmNomeArquivo']; ?>" target="_blank"><?php echo $indicsoc['nmTituloConteudo']; ?></a>
            </h3>

            <div class="list-item__meta">
                <b>Realizado em:</b> <?php echo date('d/m/Y', strtotime($indicsoc['dtDataConteudo'])); ?> |
                <b>Tags:</b> <a href="">Durango</a>, <a href="">Leandro</a>, <a href="">Lorem</a>, <a href="">Ipsum</a>, <a href="">Dolor</a>
            </div>
            
            <p> 
                <a class="more" href="<?php echo $url_raiz; ?>arquivos/enviados/file/<?php echo $indicsoc['nmNomeArquivo']; ?>" target="_blank">Download</a>
            </p>
        </article>
        
       	<?php
			}
		?>
	</div>     		
 		
 		
<?php } ?> 