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
		
		//Se veio o tipo no FORM ou URL, adiciona ao filtro
		if (isset($_REQUEST["nmPerfil"])){
			$nmPerfil = $_REQUEST["nmPerfil"];
		} 
		
		if(strlen($nmPerfil) > 0){
			$filtro = $filtro .' '. $filtro = 'AND nmPerfil like '.$db->clean('%'.$nmPerfil.'%');	
		}
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT COUNT(idPerfil) as total_registros FROM tb_perfil A WHERE idPerfil > 0".$filtro;
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
		
		if (!$total_registros){
			$total_registros = 0;
		}
		
		$qryCont = "
			SELECT 		*
					
			FROM 		tb_perfil
			
			WHERE 		idPerfil > 0 
			".$filtro." 
			ORDER BY 	nmPerfil DESC
			LIMIT 		".$inicio.",".$limite."		
		";
		
		$qryPerfis = $db->query($qryCont);
?>
            
            <form name="formGeral" id="formGeral" action="menu-perfis" method="post">
            <h1>Manutenção de Perfis</h1>
            <br/>
			<?php include('sisMensagem.php'); ?>
            <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
            <p class="destaque_italico">
                &raquo; OBS.: Por questões de segurança, não é permitido nenhum tipo de alteração no perfil "Administrador";<br/>
            </p>
            <div align="left">
            	<table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="7%">Filtrar por Nome</td>
                    	<td width="93%"><input name="nmPerfil" id="nmPerfil" size="30" maxlength="200" style="top:auto" value="<?php echo $nmPerfil; ?>"/></td>
                    </tr>
                </table>
            	<button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
                <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-perfil';">Cadastrar Perfil</button>
                
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
                            //Seta um filtro vazio
                            $filtro2 = '';
                            
                            //Se est� definido o tipo, adiciona consulta do ano ao filtro
                            if (strlen($_REQUEST["nmPerfil"]) > 0){
                                $filtro2 = $filtro2 .''. $filtro2 = 'nmPerfil='.$nmPerfil;
                            } 
                            
                           
                            //Nome da p�gina 
                            $stringPagina = '?'.$filtro2;
                        
                        //Chama a fun�ao que monta a exibi�ao do paginador
                        navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
                    
                ?>
            </div>
            <fieldset>
        	<table width="100%" border="0" align="center" class="tbLista">
            	<tr class="tbTitulo">
                	<td colspan="3" align="center">Op&ccedil;&otilde;es</td>
                	<td align="left">Nome do Perfil</td>
               	</tr>
                <?php 
					$coluna = 1;
					for($i=0; $i<count($qryPerfis); $i++){
					
					if($coluna % 2 == 0){
						$classe = 'tbNormal';
					} else {
						$classe = 'tbNormalAlt';
					}
				?>
            	<tr class="<?php echo $classe; ?>">
                	<td align="center" width="3%">
                    	<?php if($qryPerfis[$i]["idPerfil"] != 1 ){?>
                            <img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                                     onclick="excluirItem('<?php echo $qryPerfis[$i]["idPerfil"]; ?>','controller/act-perfil','Excluir','idPerfil');" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Excluir"
                                     alt="Excluir"/>
                        <?php } else { ?>
                        	-
                        <?php } ?>
                    
                  </td>
                	<td align="center" width="3%">
                    	<?php if($qryPerfis[$i]["idPerfil"] != 1 ){?>
                      <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                                 onclick="javascript: location.href='cad-perfil?idPerfil=<?php echo $qryPerfis[$i]["idPerfil"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Editar"
                                 alt="Editar"/>
                        <?php } else { ?>
                      <img src="<?php echo $url_raiz; ?>admin/img/iconAmpliar.png" 
                                 onclick="javascript: location.href='cad-perfil?idPerfil=<?php echo $qryPerfis[$i]["idPerfil"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Ver detalhes"
                                 alt="Ver detalhes"/>
                        <?php } ?>
                    </td>
                	<td align="center" width="3%">
						<?php if($qryPerfis[$i]["idPerfil"] != 1 ){?>
    
                            <?php if($qryPerfis[$i]["inAtivo"] == 1){ ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                     onclick="javascript: location.href='controller/act-perfil?acao=Destivar&idPerfil=<?php echo $qryPerfis[$i]["idPerfil"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Ativo. Clique para destivar"
                                     alt="Ativo. Clique para destivar"/>
                                     
                            <?php } else { ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" 
                                     onclick="javascript: location.href='controller/act-perfil?acao=Ativar&idPerfil=<?php echo $qryPerfis[$i]["idPerfil"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Desativado. Clique para ativar."
                                     alt="Desativado. Clique para ativar"/>
                                     
                            <?php } ?>
                        <?php } else { ?>
                        	-
                        <?php } ?>
                    </td>
                	<td align="left"><?php echo $qryPerfis[$i]["nmPerfil"]; ?></td>
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