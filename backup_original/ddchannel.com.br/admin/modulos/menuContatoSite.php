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
		if (isset($_REQUEST["nmContato"])){
			$nmContato = $_REQUEST["nmContato"];
			if(!vazio($nmContato)){
				$filtro .= ' AND nmContato like '.$db->clean('%'.$nmContato.'%');
				$filtro2 .= ' &nmContato='.$nmContato;
			}
		} 
		
		if (isset($_REQUEST["nmDescricaoContato"])){
			$nmDescricaoContato = $_REQUEST["nmDescricaoContato"];
			if(!vazio($nmDescricaoContato)){
				$filtro .= ' AND nmDescricaoContato like '.$db->clean('%'.$nmDescricaoContato.'%');
				$filtro2 .= '&nmDescricaoContato='.$nmDescricaoContato;
			}
		} 
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT COUNT(idContatoSite) as total_registros FROM tb_contato_site A WHERE idContatoSite > 0".$filtro;
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
		
		if (!$total_registros){
			$total_registros = 0;
		}
		
		$qryCont = "
			SELECT 		*
					
			FROM 		tb_contato_site
			
			WHERE 		idContatoSite > 0 
			".$filtro." 
			ORDER BY 	nmContato DESC
			LIMIT 		".$inicio.",".$limite."		
		";
		
		$qryContatos = $db->query($qryCont);
?>
            
            <form name="formGeral" id="formGeral" action="menu-contato-site" method="post">
            <h1>Manutenção de Contatos</h1>
            <br/>
			<?php include('sisMensagem.php'); ?>
            
            <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
            <div align="left">
            	<table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="7%">Filtrar por Nome</td>
                    	<td width="93%"><input name="nmContato" id="nmContato" size="30" maxlength="200" style="top:auto" value="<?php echo $nmContato; ?>"/></td>
                    </tr>
                	<tr>
                    	<td width="7%">Filtrar por Descrição</td>
                    	<td width="93%"><input name="nmDescricaoContato" id="nmDescricaoContato" size="30" maxlength="200" style="top:auto" value="<?php echo $nmDescricaoContato; ?>"/></td>
                    </tr>
                </table>
            	<button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
                <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-contato-site';">Cadastrar Contato</button>
                
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
                	<td align="left">Nome</td>
                	<td width="27%" align="left">E-mail</td>
                	<td width="24%" align="left">Descri&ccedil;&atilde;o</td>
               	</tr>
                <?php 
					$coluna = 1;
					for($i=0; $i<count($qryContatos); $i++){
					
					if($coluna % 2 == 0){
						$classe = 'tbNormal';
					} else {
						$classe = 'tbNormalAlt';
					}
				?>
            	<tr class="<?php echo $classe; ?>">
                	<td align="center" width="3%">
                        <img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                                 onclick="excluirItem('<?php echo $qryContatos[$i]["idContatoSite"]; ?>','controller/act-contato-site','Excluir','idContatoSite');" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Excluir"
                                 alt="Excluir"/>
                  </td>
                	<td align="center" width="3%">
                      <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                                 onclick="javascript: location.href='cad-contato-site?idContatoSite=<?php echo $qryContatos[$i]["idContatoSite"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Ver detalhes"
                                 alt="Ver detalhes"/>
                    </td>
                	<td align="center" width="3%">
    
                            <?php if($qryContatos[$i]["inAtivo"] == 1){ ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                     onclick="javascript: location.href='controller/act-contato-site?acao=Destivar&idContatoSite=<?php echo $qryContatos[$i]["idContatoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Ativo. Clique para destivar"
                                     alt="Ativo. Clique para destivar"/>
                                     
                            <?php } else { ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" 
                                     onclick="javascript: location.href='controller/act-contato-site?acao=Ativar&idContatoSite=<?php echo $qryContatos[$i]["idContatoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Desativado. Clique para ativar."
                                     alt="Desativado. Clique para ativar"/>
                                     
                            <?php } ?>
                    </td>
                	<td align="center" width="3%">
                            <?php if($qryContatos[$i]["inContatoPrincipal"] == 1){ ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconPrincipal.png"
                                     onclick="javascript: location.href='controller/act-contato-site?acao=PrincipalOff&idContatoSite=<?php echo $qryContatos[$i]["idContatoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Este é o contato principal do site"
                                     alt="Sim" id="helpContatoPrincipal"/>
                                     
                            <?php } else { ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconePrincipal_off.png"
                                     onclick="javascript: location.href='controller/act-contato-site?acao=PrincipalOn&idContatoSite=<?php echo $qryContatos[$i]["idContatoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Este é um contato comum do site"
                                     alt="N�o" id="helpContatoPrincipal"/>
                            <?php } ?>
                    </td>
                	<td align="center" width="3%">
                            <?php if($qryContatos[$i]["inExibir"] == 1){ ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconeOrcamento.png"
                                     onclick="javascript: location.href='controller/act-contato-site?acao=FormulariOff&idContatoSite=<?php echo $qryContatos[$i]["idContatoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Este contato Não está sendo exibido no Formulário de Contato."
                                     alt="Sim" id="helpContatoPrincipal"/>
                                     
                            <?php } else { ?>
                               <img src="<?php echo $url_raiz; ?>admin/img/iconeOrcamento_off.png"
                                     onclick="javascript: location.href='controller/act-contato-site?acao=FormularioOn&idContatoSite=<?php echo $qryContatos[$i]["idContatoSite"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Este contato está sendo exibido no Formulário de Contato."
                                     alt="N�o" id="helpContatoPrincipal"/>
                            <?php } ?>
                    </td>
                	<td align="left"><?php echo $qryContatos[$i]["nmContato"]; ?></td>
                	<td align="left"><?php echo $qryContatos[$i]["nmEmailContato"]; ?></td>
                	<td align="left"><?php echo $qryContatos[$i]["nmDescricaoContato"]; ?></td>
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
