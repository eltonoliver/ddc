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
//		if (isset($_REQUEST["nmContato"])){
//			$nmContato = $_REQUEST["nmContato"];
//			if(!vazio($nmContato)){
//				$filtro = $filtro .' '. $filtro = 'AND nmContato like "%'.$nmContato.'%"';
//				$filtro2 = $filtro2 .''. $filtro2 = '&nmContato='.$nmContato;
//			}
//		} 
		
//		if (isset($_REQUEST["nmDescricaoContato"])){
//			$nmDescricaoContato = $_REQUEST["nmDescricaoContato"];
//			if(!vazio($nmDescricaoContato)){
//				$filtro = $filtro .' '. $filtro = 'AND nmDescricaoContato like "%'.$nmDescricaoContato.'%"';
//				$filtro2 = $filtro2 .''. $filtro2 = '&nmDescricaoContato='.$nmDescricaoContato;
//			}
//		} 
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT COUNT(idEnderecoSite) as total_registros FROM tb_endereco_site A WHERE idEnderecoSite > 0".$filtro;
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
		
		if (!$total_registros){
			$total_registros = 0;
		}
		
		$qryCont = "
			SELECT 		*
					
			FROM 		tb_endereco_site
			
			WHERE 		idEnderecoSite > 0 
			".$filtro." 
			ORDER BY 	idEnderecoSite DESC
			LIMIT 		".$inicio.",".$limite."		
		";
		
		$qryPaginada = $db->query($qryCont);
?>
            
            <form name="formGeral" id="formGeral" action="menu-contato-site" method="post">
            <h1>Manutenção de Endereços</h1>
            <br/>
			<?php include('sisMensagem.php'); ?>
            
            <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
            <div align="left">
                <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-endereco';">Cadastrar Novo</button>
                
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
                	<td colspan="5" align="center" width="12%">Op&ccedil;&otilde;es</td>
                	<td align="left">Endere&ccedil;o</td>
               	</tr>
                <?php 
					$coluna = 1;
					for($i=0; $i<count($qryPaginada); $i++){
					
					if($coluna % 2 == 0){
						$classe = 'tbNormal';
					} else {
						$classe = 'tbNormalAlt';
					}
				?>
            	<tr class="<?php echo $classe; ?>">
                	<td align="center" width="3%">
                        <img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                                 onclick="excluirItem('<?php echo $qryPaginada[$i]["idEnderecoSite"]; ?>','controller/act-endereco','Excluir','idEnderecoSite');" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Excluir"
                                 alt="Excluir"/>
                  </td>
                	<td align="center" width="3%">
                      <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                                 onclick="javascript: location.href='cad-endereco?idEnderecoSite=<?php echo $qryPaginada[$i]["idEnderecoSite"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Ver detalhes"
                                 alt="Ver detalhes"/>
                    </td>
                	<td align="center" width="3%">
    
                            <?php if($qryPaginada[$i]["inAtivo"] == 1){ ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                     onclick="javascript: location.href='controller/act-endereco?acao=Destivar&idEnderecoSite=<?php echo $qryPaginada[$i]["idEnderecoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Ativo. Clique para destivar"
                                     alt="Ativo. Clique para destivar"/>
                                     
                            <?php } else { ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" 
                                     onclick="javascript: location.href='controller/act-endereco?acao=Ativar&idEnderecoSite=<?php echo $qryPaginada[$i]["idEnderecoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Desativado. Clique para ativar."
                                     alt="Desativado. Clique para ativar"/>
                                     
                            <?php } ?>
                    </td>
                	<td align="center" width="3%">
                            <?php if($qryPaginada[$i]["inPrincipal"] == 1){ ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconPrincipal.png"
                                     onclick="javascript: location.href='controller/act-endereco?acao=PrincipalOff&idEnderecoSite=<?php echo $qryPaginada[$i]["idEnderecoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Este � o contato principal do site"
                                     alt="Sim" id="helpContatoPrincipal"/>
                                     
                            <?php } else { ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconePrincipal_off.png"
                                     onclick="javascript: location.href='controller/act-endereco?acao=PrincipalOn&idEnderecoSite=<?php echo $qryPaginada[$i]["idEnderecoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Este � um contato comum do site"
                                     alt="Nao" id="helpContatoPrincipal"/>
                            <?php } ?>
                    </td>
                	<td align="center" width="3%">
                            <?php if($qryPaginada[$i]["inExibir"] == 1){ ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconeOrcamento.png"
                                     onclick="javascript: location.href='controller/act-endereco?acao=FormulariOff&idEnderecoSite=<?php echo $qryPaginada[$i]["idEnderecoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Este contato NAO est� sendo exibido no Formul�rio de Contato."
                                     alt="Sim" id="helpContatoPrincipal"/>
                                     
                            <?php } else { ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconeOrcamento_off.png"
                                     onclick="javascript: location.href='controller/act-endereco?acao=FormularioOn&idEnderecoSite=<?php echo $qryPaginada[$i]["idEnderecoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Este contato est� sendo exibido no Formul�rio de Contato."
                                     alt="Nao" id="helpContatoPrincipal"/>
                            <?php } ?>
                    </td>
                	<td align="left">
						<?php
                            echo $qryPaginada[$i]["nmSigla"].' '.$qryPaginada[$i]["nmLogradouro"].', '.$qryPaginada[$i]["nrNumero"];
                            if(!vazio($qryPaginada[$i]["nmComplemento"])){ 
                                echo ' - '.$qryPaginada[$i]["nmComplemento"];
                            }
                            if(!vazio($qryPaginada[$i]["nmPontoReferencia"])){ 
                                echo ' - '.$qryPaginada[$i]["nmPontoReferencia"];
                            }
                            echo '<br/>';
                            echo 'Bairro: '.$qryPaginada[$i]["nmBairro"].' - CEP: '.$qryPaginada[$i]["nmCEP"].'<br/>';
                            echo $qryPaginada[$i]["nmCidade"].' - '.$qryPaginada[$i]["nmEstado"].' -  Brasil<br/>';
                        ?>
                    </td>
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
	ugaAlert('helpContatoPrincipal', 	
'&raquo; O contato que for sinalizado como "Contato Principal" receber&aacute; todas as notifica&ccedil;&otilde;es de e-mail autom&aacute;ticas, como modera&ccedil;&atilde;o de coment&aacute;rios, pore exemplo;<br/>'+
'&raquo; Somente um contato poder&aacute; ser sinalizado como principal;<br/>'+
'&raquo; Ao sinalizar um contato como principal, todos os outros ser&atilde;o automaticamente sinalizados como contatos simples;<br/>'
	);
</script>
