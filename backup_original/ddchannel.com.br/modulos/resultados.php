<?php if (!isset($titulo) || !$titulo) {
  // Define o tipo de conteúdo que vai ser carregado através do SQL
    $idTipoConteudo = '30';
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
    $filtro = '';
    $ano = ($_REQUEST['ano'] ? (int) $_REQUEST['ano'] : date('Y'));
    $mes = ($_REQUEST['mes'] ? (int) $_REQUEST['mes'] : false);
    $filtro .= ' AND YEAR(dtDataConteudo) = ' . $db->clean($ano);
    if ($mes)
        $filtro .= ' AND MONTH(dtDataConteudo) = ' . $db->clean($mes);

    // SQL para charges padrão
    $sqlNoticias = "SELECT * FROM vwconteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= " . $idTipoConteudo . "  " . $filtro . " order by idConteudo DESC LIMIT " . $inicio . "," . $limite;
	 // Query de acordo com a requisicao do usuario
    $qryPesquisa = $db->query($sqlNoticias)
    ?>
    
    <h2 class="title-section">
        <span class="title-section__name">
            Resultado das Pesquisas
        </span>
		
       <ul class="title-section__archive">
            <li>
                <a >Arquivo</a>
                <ul>
                    <?php
                    $qryArquivo = $db->query("SELECT year(dtDataConteudo) as ano,month(dtDataConteudo) as mes, count(idConteudo) as noticias FROM tb_conteudo WHERE idTipoConteudo=30 AND inPublicar = 1 group by ano,mes order by ano desc, mes DESC");
                    $meses = count($qryArquivo);
                    foreach ($qryArquivo as $i => $array) {
                        if ($i == 0 or $qryArquivo[$i - 1]["ano"] != $array["ano"]) {
                            ?>
                            <li>
                                <a href="resultados/ano/<?php echo $array["ano"]; ?>"><?php echo $array["ano"]; ?></a>
                                <ul>
                                <?php } ?>
                                <li>
                                    <a href="resultados/ano/<?php echo $array["ano"]; ?>/mes/<?php echo $array["mes"]; ?>"><?php echo nomeMes($array["mes"]); ?> <i>(<?php echo $array["noticias"]; ?>)</i></a>
                                </li>
                                <?php if (($i + 1) == $meses or $qryArquivo[$i + 1]["ano"] != $array["ano"]) { ?>
                                </ul>
                            </li>
                            <?php
                        }
                    }
                    ?>            
                </ul>
            </li>
        </ul>
              
    </h2>
    <?php echo resume($string, $char)  ?>
    <div class="list list-pesquisa">
    	<?php
	  foreach ($qryPesquisa as $pesquisa) { 
		?>
		
        <article class="list-item cf" style="margin-bottom: 15px;">
        	
            <a class="list-item__img" href="<?php echo $url_raiz; ?>arquivos/enviados/file/<?php echo $pesquisa['nmNomeArquivo']; ?>" target="_blank">
                <img alt="<?php echo $pesquisa['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $pesquisa['nmLinkImagem']; ?>&w=310&h=310" />
            </a>

            <h3 style="margin-bottom: 2px;">
                <a href="<?php echo $url_raiz; ?>arquivos/enviados/file/<?php echo $pesquisa['nmNomeArquivo']; ?>" target="_blank"><?php echo stripslashes($pesquisa['nmTituloConteudo']); ?></a>
            </h3>
            
            
                <?php echo resume($pesquisa['nmResumo'], 270); ?>
                
            

            <div class="list-item__meta" style="margin-bottom: 6px;">
                <b>Realizado em:</b> <?php echo date('d/m/Y', strtotime($pesquisa["dtDataConteudo"])); ?> |
                <b>Tags:</b> <?php 
							   $sqlTags = "SELECT tb_categoria.nmCategoria FROM tb_conteudo INNER JOIN tb_conteudo_tag ON                                              tb_conteudo_tag.idConteudo = tb_conteudo.idConteudo INNER JOIN tb_categoria ON                                              tb_categoria.idCategoria = tb_conteudo_tag.idCategoria WHERE tb_conteudo.idConteudo = " . $pesquisa['idConteudo'] . ";";
							   $qryTags = $db->query($sqlTags);
							   
							   foreach ($qryTags as $tags) { 
				
				             ?>
                           <a> <?php echo $tags['nmCategoria']; ?>, </a>
                <?php } ?> 
                
            </div>
            
            <p> 
                <a class="more" href="<?php echo $url_raiz; ?>arquivos/enviados/file/<?php echo $pesquisa['nmNomeArquivo']; ?>" target="_blank">Download</a>
            </p>
        </article>
        
       	<?php
			}
		?>
	</div>   
        <div class="clearBoth">
        <?php
        //NAVEGA�AO DO PAGINADOR 
          $srtQtotal = "
                            SELECT 		COUNT(*) as total_registros 
                            FROM 		vwconteudo
                            WHERE 		idTipoConteudo = " . $db->clean($idTipoConteudo) . "
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
        if ($ano)
            $filtro2 .= '/ano/' . $ano;
        if ($mes)
            $filtro2 .= '/mes/' . $mes;
        //Nome da p�gina 
        $stringPagina = $url_raiz . 'resultados' . $filtro2;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
        ?>
    </div>
 		
 		
<?php } ?> 