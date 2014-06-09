<?php
	//PREPARA�AO DO PAGINADOR
		
		//Define o total de registros por p�gina
		$limite = 10;
		
		//Pega o n�mero da p�gina que vem pela URL
		$pagina = $_GET['pag'];
		
		//Seta a p�gina inicial
		if(!$pagina){
			$pagina = 1;
		}
	
		//Calcula os registros inicial e final as serem pesquisados no banco de dados
		$inicio = ($pagina * $limite) - $limite;
		
?>

<?php 
	//QUERY PAGINADA
		//Exemplo: "SELECT * FROM nome_da_tabela LIMIT $inicio,$limite"
		
		//Seta um filtro vazio
		$filtro = '';
		$filtro2 = '';
		
		//Se veio o tipo no FORM ou URL, adiciona ao filtro
		if (isset($_REQUEST["nmPergunta"])){
			$nmPergunta = $_REQUEST["nmPergunta"];
			if(!vazio($nmPergunta)){
				$filtro .= ' AND nmPergunta like '.$db->clean('%'.$nmPergunta.'%');
				$filtro2 .= ' &nmPergunta='.$nmPergunta;
			}
		} 
		
		if (isset($_REQUEST["nmDescricaoContato"])){
			$nmDescricaoContato = $_REQUEST["nmDescricaoContato"];
			if(!vazio($nmDescricaoContato)){
				$filtro .= 'AND nmDescricaoContato like '.$db->clean('%'.$nmDescricaoContato.'%');
				$filtro2 .= '&nmDescricaoContato='.$nmDescricaoContato;
			}
		} 
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT COUNT(idEnquete) as total_registros FROM tb_enquete A WHERE idEnquete > 0".$filtro;
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
		
		if (!$total_registros){
			$total_registros = 0;
		}
		
		$qryCont = "
			SELECT 		*
					
			FROM 		tb_enquete
			
			WHERE 		idEnquete > 0 
			".$filtro." 
			ORDER BY 	nmPergunta DESC
			LIMIT 		".$inicio.",".$limite."		
		";
		
		$qryEnquetes = $db->query($qryCont);
?>
            
            <form name="formGeral" id="formGeral" action="menu-enquete" method="post">
            <h1>Manutenção de Enquetes</h1>
            <br/>
			<?php include('sisMensagem.php'); ?>
            
            <h2 class="separadador">Últimas Cadastradas (<?php echo $total_registros; ?>)</h2>
            <div align="left">
            	<table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="7%">Filtrar por Nome</td>
                    	<td width="93%"><input name="nmPergunta" id="nmPergunta" size="30" maxlength="200" style="top:auto" value="<?php echo $nmPergunta; ?>"/></td>
                    </tr>
                </table>
            	<button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
                <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-enquete';">Cadastrar Enquete</button>
                
            </div>
            <br/>
            </form>
            
            <?php if($total_registros == 0){ //Se a consulta voltou sem nenhum resultado.?>
                <div class="msgBox">
                    <p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/> Nenhum resultado para a consulta realizada.</p>
                </div>
                
            <?php } else { //Se foram encontrados resultados. ?>
            
            <div align="center">
				<?php  
                    //NAVEGA�AO DO PAGINADOR
                    
                        //Calcula o total de p�ginas
                        $total_paginas = ceil($total_registros/$limite);
                        
                        //Define a p�gina de direcionamento dos links
							
                            //Nome da p�gina 
                            $stringPagina = '?filtrar=sim'.$filtro2;
                        
                        //Chama a fun�ao que monta a exibi�ao do paginador
                        navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
                    
                ?>
            </div>
            <fieldset>
        	<table width="100%" border="0" align="center" class="tbLista">
            	<tr class="tbTitulo">
                	<td colspan="6" align="center" width="18%">Op&ccedil;&otilde;es</td>
                	<td width="34%" align="left">Pergunta?</td>
                	<td width="28%" align="left">Descri&ccedil;&atilde;o</td>
                	<td width="22%" align="left">Per&iacute;odo</td>
               	</tr>
                <?php 
					$coluna = 1;
					for($i=0; $i<count($qryEnquetes); $i++){
					
					if($coluna % 2 == 0){
						$classe = 'tbNormal';
					} else {
						$classe = 'tbNormalAlt';
					}
				?>
            	<tr class="<?php echo $classe; ?>">
                	<td align="center" width="3%">
                        <img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                                 onclick="excluirItem('<?php echo $qryEnquetes[$i]["idEnquete"]; ?>','controller/act-enquete','Excluir','idEnquete');" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Excluir"
                                 alt="Excluir"/>
                  </td>
                	<td align="center" width="3%">
                      <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                                 onclick="javascript: location.href='cad-enquete?idEnquete=<?php echo $qryEnquetes[$i]["idEnquete"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Ver detalhes"
                                 alt="Ver detalhes"/>
                    </td>
                	<td align="center" width="3%">
    
                            <?php if($qryEnquetes[$i]["inAtivo"] == 1){ ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                     onclick="javascript: location.href='controller/act-enquete?acao=Destivar&idEnquete=<?php echo $qryEnquetes[$i]["idEnquete"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Ativo. Clique para destivar"
                                     alt="Ativo. Clique para destivar"/>
                                     
                            <?php } else { ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" 
                                     onclick="javascript: location.href='controller/act-enquete?acao=Ativar&idEnquete=<?php echo $qryEnquetes[$i]["idEnquete"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Desativado. Clique para ativar."
                                     alt="Desativado. Clique para ativar"/>
                                     
                            <?php } ?>
                    </td>
                	<td align="center" width="3%">
                            <?php if($qryEnquetes[$i]["inDestaque"] == 1){ ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconPrincipal.png"
                                     onclick="javascript: location.href='controller/act-enquete?acao=DestaqueOff&idEnquete=<?php echo $qryEnquetes[$i]["idEnquete"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     alt="Destaque" id="helpEnqueteDestaque"/>
                                     
                            <?php } else { ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconePrincipal_off.png"
                                     onclick="javascript: location.href='controller/act-enquete?acao=DestaqueOn&idEnquete=<?php echo $qryEnquetes[$i]["idEnquete"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     alt="N�o destacado" id="helpEnqueteDestaque" title="N�o destacado"/>
                            <?php } ?>
                    </td>
                	<td align="center" width="3%">
                            <?php if($qryEnquetes[$i]["inLimitarIp"] == 1){ ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconeUserBlock.png"
                                     onclick="javascript: location.href='controller/act-enquete?acao=IpOff&idEnquete=<?php echo $qryEnquetes[$i]["idEnquete"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     alt="Bloqueio por IP Ativado" id="helpeEnqueteLimitarIP"/>
                                     
                            <?php } else { ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconeUserBlock_off.png"
                                     onclick="javascript: location.href='controller/act-enquete?acao=IpOn&idEnquete=<?php echo $qryEnquetes[$i]["idEnquete"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     alt="Bloqueio por IP Desativado" id="helpeEnqueteLimitarIP" title="Bloqueio por IP Desativado"/>
                            <?php } ?>
                    </td>
                	<td align="center" width="3%">
                           <img src="<?php echo $url_raiz; ?>admin/img/iconeOrcamento.png"
                                 onclick="javascript: location.href='cad-enquete?idEnquete=<?php echo $qryEnquetes[$i]["idEnquete"]; ?>#resultados';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Ver resultados"
                                 alt="Ver resultados"/>
                    </td>
                	<td align="left"><?php echo $qryEnquetes[$i]["nmPergunta"]; ?></td>
                	<td align="left"><?php echo $qryEnquetes[$i]["nmDescricaoEnquete"]; ?></td>
                	<td align="left"><?php echo dataBarrasBR($qryEnquetes[$i]["dtDataInicio"]).' a '.dataBarrasBR($qryEnquetes[$i]["dtDataFim"]); ?></td>
               	</tr>
                <?php $coluna++; } ?>
            </table>
            </fieldset>
			<br/>            
            <div align="center">
				<?php  
                    //NAVEGA�AO DO PAGINADOR
                        //Chama a fun�ao que monta a exibi�ao do paginador
                        navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
                ?>
            </div>
            
            <?php } ?>
            
            <br/>
<script type="text/javascript">
	ugaAlert('helpEnqueteDestaque', 
'<strong>Destacar na P�gina Principal</strong><br/>'+		
'&raquo;Sinalizar uma enquete como destaque � a��o que faz com que ela seja exibida na p�gina inicial do site;<br/>'+
'&raquo; Somente uma enquete pode ser sinalizada como destaque;<br/>'+
'&raquo; Ao sinalizar o destaque para esta enquete, todos as outras ser&atilde;o automaticamente sinalizados como n�o destacadas;<br/>'
	);
	
	ugaAlert('helpeEnqueteLimitarIP', 
'<strong>Bloqueio por IP</strong><br/>'+	
'&raquo; Sinalizando esta op��o, o visitante n�o poder� votar mais de uma vez no mesmo computador, durante todo o tempo de dura��o da enquete;<br/>'+
'&raquo; Por padr�o, o �nico bloqueio feito � que o visitante n�o possa votar mais de uma vez durante uma se��o (ou seja: uma visita ao site);'
	);
</script>
