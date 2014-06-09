<?php
	//PREPARA�AO DO PAGINADOR
		
		//Define o total de registros por p�gina
		$limite = 20;
		
		//Pega o n�mero da p�gina que vem pela URL
		$pagina = $_GET['pag'];
		
		//Seta a p�gina inicial
		if(!$pagina){
			$pagina = 1;
		}
	
		//Calcula os registros inicial e final as serem pesquisados no banco de dados
		$inicio = ($pagina * $limite) - $limite;
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT COUNT(idUsuario) as total_registros FROM tb_usuario";
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
?>

<?php 
	//QUERY PAGINADA
		//Exemplo: "SELECT * FROM nome_da_tabela LIMIT $inicio,$limite"
		$qryCont = "
			SELECT 		*
			FROM 		tb_usuario
			ORDER BY 	nmUsuario
			LIMIT 		".$inicio.",".$limite."		
		";
		
		$qryUsuarios = $db->query($qryCont);
?>
            
            <h1>Manutenção de Usuários</h1>
            <br/>
            
            <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
            <div align="left">
                <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-usuario';">Cadastrar Novo</button>
            </div>
            <br/>
            <?php if($total_registros == 0){ //Se a consulta voltou sem nenhum resultado.?> 
                <div class="msgBox">
                    <p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/> Nenhum resultado para a consulta realizada.</p>
                </div>
                
            <?php } else { //Se foram encontrados resultados. ?>
            
            <p class="destaque_italico">Por questoes de segurança, o usuário "Administrador" nao pode ser excluído nem desativado.</p>
        	<div align="center">
				<?php  
                    //NAVEGA�AO DO PAGINADOR
                    
                        //Calcula o total de p�ginas
                        $total_paginas = ceil($total_registros/$limite);
                        
                        //Define a p�gina de direcionamento dos links
						
							//Nome da p�gina 
							$stringPagina = 'menu-usuarios?temp=0';
                        
                        //Chama a fun�ao que monta a exibi�ao do paginador
                        navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
                ?>
            </div>
            <fieldset>
        	<table width="100%" border="0" align="center" class="tbLista">
            	<tr class="tbTitulo">
                	<td colspan="3" align="center" width="9%">Op&ccedil;&otilde;es</td>
                	<td width="11%" align="center">Ativo?</td>
                	<td width="10%" align="center">Data Cadastro</td>
                	<td width="30%" align="left">Nome</td>
                	<td width="15%" align="left">Login</td>
                	<td width="25%" align="left">E-mail</td>
               	</tr>
                <?php 
					$coluna = 1;
					for($i=0; $i<count($qryUsuarios); $i++){
					
					if($coluna % 2 == 0){
						$classe = 'tbNormal';
					} else {
						$classe = 'tbNormalAlt';
					}
				?>
            	<tr class="<?php echo $classe; ?>">
                	<td align="center" width="3%">
                    	<?php
							if($qryUsuarios[$i]["idPerfil"] != 1 && $qryUsuarios[$i]["idUsuario"] != $_SESSION["ID"]){
						?>
                    
                    	<img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                        	 onclick="excluirItem('<?php echo $qryUsuarios[$i]["idUsuario"]; ?>','controller/act-usuario','Excluir','idUsuario');" 
                             onmouseover="javascript: this.style.cursor='pointer';"
                             title="Excluir"
                             alt="Excluir"/>
                        <?php } else { ?>
                        	-
                        <?php } ?>
                        
                    </td>
                	<td align="center" width="3%">
                    	<img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                        	 onclick="javascript: location.href='cad-usuario?idUsuario=<?php echo $qryUsuarios[$i]["idUsuario"]; ?>';" 
                             onmouseover="javascript: this.style.cursor='pointer';"
                             title="Editar"
                             alt="Editar"/>
                    </td>
                	<td align="center" width="3%">
                    
						<?php if($qryUsuarios[$i]["idPerfil"] != 1){ ?>
                        
                            <?php if($qryUsuarios[$i]["inAtivo"] == 1){ ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                     onclick="javascript: location.href='controller/act-usuario?acao=Destivar&idUsuario=<?php echo $qryUsuarios[$i]["idUsuario"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Ativo. Clique para destivar"
                                     alt="Ativo. Clique para destivar"/>
                                     
                            <?php } else { ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" 
                                     onclick="javascript: location.href='controller/act-usuario?acao=Ativar&idUsuario=<?php echo $qryUsuarios[$i]["idUsuario"]; ?>';" 
                                     onmouseover="javascript: this.style.cursor='pointer';"
                                     title="Desativado. Clique para ativar."
                                     alt="Desativado. Clique para ativar"/>
                                     
                            <?php } ?>
                            
                        <?php } else { ?>
                        	-
                        <?php } ?>
                        
                    </td>
                	<td width="11%" align="center">
                    	<?php if($qryUsuarios[$i]["inAtivo"] == 1){ ?>
							Sim                                 
                        <?php } else { ?>
							Nao
                        <?php } ?>
                    </td>
                	<td width="10%" align="center"><?php echo date('d/m/Y', strtotime($qryUsuarios[$i]["dtDataCadastro"]));?></td>
                	<td width="30%" align="left"><?php echo $qryUsuarios[$i]["nmUsuario"]; ?></td>
                	<td width="15%" align="left"><?php echo $qryUsuarios[$i]["nmLogin"]; ?></td>
                	<td width="25%" align="left"><?php echo $qryUsuarios[$i]["nmEmailUsuario"]; ?></td> 
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
            <br/>
            
            <?php } ?>