<?php  $qryRedes = $db->query('SELECT idRedeSocial, nmRedeSocial FROM tb_rede_social WHERE inVisibilidade = 1');  ?>
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
		if (isset($_REQUEST["idRedeSocial"])){
			$idRedeSocial = $_REQUEST["idRedeSocial"];
		} 
		
		if(strlen($idRedeSocial) > 0){
			$filtro .= ' AND idRedeSocial = '.$db->clean($idRedeSocial);	
		}
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT COUNT(idDadosApi) as total_registros FROM tb_dados_api WHERE idDadosApi > 0".$filtro;
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
		
		if (!$total_registros){
			$total_registros = 0;
		}
		
		$qryCont = "
			SELECT 		*
					
			FROM 		tb_dados_api
			
			WHERE	 	idDadosApi > 0
			".$filtro." 
			ORDER BY 	idRedeSocial ASC
			LIMIT 		".$inicio.",".$limite."		
		";
		
		$qryPaginada = $db->query($qryCont);
		
		//new dBUg($qryPaginada);
?>
            
            <form name="formGeral" id="formGeral" action="menu-apis" method="post">
            <h1>Manutenção de Dados de API</h1>
            <br/>
			<?php include('sisMensagem.php');  ?>        
            <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
            <p class="destaque_italico">
                &raquo; OBS.: Voc&ecirc; pode possuir v&aacute;rios dados de apis cadastrados para uma mesma rede social, mas somente uma delas poder&aacute; estar ativa (a &uacute;ltima cadastrada);<br/>
            </p>
            <div align="left">
            	<table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="7%">Filtrar por Redes</td>
                    	<td width="93%">
                              <select name="idRedeSocial" id="idRedeSocial"  style="top:auto">
                                    <option value="">Todos</option>
                                    <?php for($i=0; $i<count($qryRedes); $i++){ ?>
                                    <option value="<?php echo $qryRedes[$i]["idRedeSocial"]; ?>" <?php if($qryRedes[$i]["idRedeSocial"] == $idRedeSocial){ echo 'selected'; } ?>><?php echo $qryRedes[$i]["nmRedeSocial"]; ?></option>
                                    <?php } ?>
                              </select>
                        </td>
                    </tr>
                </table>
            	<button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
                <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-a-p-is';">Cadastrar Novo</button>
                
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
                            if (strlen($_REQUEST["idRedeSocial"]) > 0){
                                $filtro2 = $filtro2 .''. $filtro2 = 'idRedeSocial='.$idRedeSocial;
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
                	<td colspan="3"  width="9%" align="center">Op&ccedil;&otilde;es</td>
                	<td width="28%" align="center">Rede Social</td>
                	<td width="37%" align="center">Nome do Aplicativo</td>
                	<td width="27%" align="center">Detalhes</td>
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
                             onClick="excluirItem('<?php echo $qryPaginada[$i]["idDadosApi"]; ?>','controller/act-api','Excluir','idDadosApi');" 
                             onmouseover="javascript: this.style.cursor='pointer';"
                             title="Excluir"
                             alt="Excluir"/>
                    </td>
                	<td align="center" width="3%">
                    	<img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                        	 onclick="javascript: location.href='cad-a-p-is?idDadosApi=<?php echo $qryPaginada[$i]["idDadosApi"]; ?>';" 
                             onmouseover="javascript: this.style.cursor='pointer';"
                             title="Editar"
                             alt="Editar"/>
                    </td>
                	<td align="center"  width="3%">
						<?php if($qryPaginada[$i]["inAtivo"] == 1){ ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                 onclick="javascript: location.href='controller/act-api?acao=Destivar&idDadosApi=<?php echo $qryPaginada[$i]["idDadosApi"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Ativo. Clique para destivar"
                                 alt="Ativo. Clique para destivar"/>
                                 
                        <?php } else { ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" 
                                 onclick="javascript: location.href='controller/act-api?acao=Ativar&idDadosApi=<?php echo $qryPaginada[$i]["idDadosApi"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Desativado. Clique para ativar."
                                 alt="Desativado. Clique para ativar"/>
                                 
                        <?php } ?>
                    
                    </td>
                	<td align="center">
					<?php 
							$qryRedeSocial = $db->query("SELECT nmRedeSocial FROM tb_rede_social WHERE idRedeSocial = ".$qryPaginada[$i]["idRedeSocial"]." LIMIT 1");
							echo $qryRedeSocial[0]["nmRedeSocial"]; 
					
					
					?></td>
                	<td align="center"><?php echo $qryPaginada[$i]["nmNomeApp"]; ?></td>
                	<td align="center"><a href="<?php $url_raiz; ?>cad-a-p-is?idDadosApi=<?php echo $qryPaginada[$i]["idDadosApi"]; ?>">Ver detalhes</a></td>
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