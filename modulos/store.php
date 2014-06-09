<?php
if (!isset($titulo) || !$titulo) {
	   // Define o tipo de conteúdo que vai ser carregado através do SQL
    $idTipoConteudo = '31';
    //PREPARA�AO DO PAGINADOR
    //Define o total de registros por p�gina
    $limite = 9;
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
    $sqlProdutos = "SELECT idConteudo,nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo,nmListaTags FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= " . $idTipoConteudo . "  " . $filtro . " order by idConteudo DESC LIMIT " . $inicio . "," . $limite;
	 // Query de acordo com a requisicao do usuario
    $qryProdutos = $db->query($sqlProdutos)
	
?>
    <h2 class="title-section">
        <span class="title-section__name">
            Store
        </span>

        <a class="title-section__more" href="javascript:history.back(-1);">
            Voltar
        </a>
    </h2>

    <div class="grid grid-store">
	<?php
    
        foreach ($qryProdutos as $produtos) {
             
    ?>
        <article class="grid-item">
            <div >
                <a title="<?php echo $produtos['nmTituloConteudo']; ?>" href="store/<?php echo $produtos['nmTituloAmigavel']; ?>">
                    <img alt="<?php echo $produtos['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $produtos['nmLinkImagem']; ?>&w=480&h=480" />
                </a>
                
                <a class="grid-item__about" href="store/<?php echo $produtos['nmTituloAmigavel']; ?>">
                    <h3><?php echo $produtos['nmTituloConteudo']; ?></h3>

                    <small><?php echo $produtos['valor']; ?></small>
                </a>
            </div>
        </article>
      <?php } ?>
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
        $stringPagina = $url_raiz . 'store' . $filtro2;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
        ?>
    </div>
    <?php
    	include 'ddcProgramas.php';
    ?>
   <?php } else { 
       $strPagina = "SELECT idConteudo,nmTituloAmigavel,nmUsuario, dtDataConteudo as dtCompleta, day(dtDataConteudo) as dia,month(dtDataConteudo) as mes,nmLinkImagem,nmTituloConteudo,nmConteudo,inComentario,idConteudoRelacionado, valor, nmResumo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE nmTituloAmigavel= " . $db->clean($titulo) . " AND inPublicar = 1 LIMIT 1";
	   $qryPagina = $db->query($strPagina);
	   if (!$qryPagina) {
			redirecionar($url_raiz . '404');
	    }
     
   ?>
      <h2 class="title-section">
        <span class="title-section__name">
            Store
          
        </span>
        <a class="title-section__more" href="javascript:history.back(-1);">
            Voltar
        </a>
      </h2>

    <article class="post">
        <div class="cf product">
            <div class="fl">
                <img alt="<?php echo $qryPagina[0]['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qryPagina[0]['nmLinkImagem']; ?>&w=500&h=500" />
            </div>

            <div class="fr">
                <h3 class="product-title"><?php echo $qryPagina[0]['nmTituloConteudo']; ?></h3>

                <div class="product-price">
                  R$  <?php echo $qryPagina[0]['valor']; ?>
                </div>

                <p>
                    <?php echo $qryPagina[0]['nmResumo']; ?>
                </p>

                <div class="product-frete">
                    <label for="p_frete">Calcular frete:</label>
                    <input id="p_frete" name="p_frete" placeholder="Digite o seu CEP" type="text" value="" />
                    <input type="submit" value="Calcular" />
                </div>
                <?php if($geralConfig[0]["inPagSeguro"] == 1){ ?>
                <a href="<?php echo $url_raiz; ?>controller/compra-controller?id=<?php echo $qryPagina[0]["idConteudo"]; ?>&acao=Adicionar">
                    <button class="btn btn-addtocart">
                        <span aria-hidden="true"></span>
                        <b>Adicionar ao carrinho</b>
                    </button>
                
				</a>
				<?php } ?>
            </div>
        </div>

        <div class="post-text">
            <p>
            <?php echo $qryPagina[0]['nmConteudo']; ?>
            </p>
        </div>
    </article>
    <?php
    	include 'ddcProgramas.php';
    ?>
<?php } ?>


