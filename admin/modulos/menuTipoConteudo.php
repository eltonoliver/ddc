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
		if (isset($_REQUEST["nmTipoConteudo"])){
			$nmTipoConteudo = $_REQUEST["nmTipoConteudo"];
		} 
		
		if(strlen($nmTipoConteudo) > 0){
			$filtro .= ' AND nmTipoConteudo like '.$db->clean('%'.$nmTipoConteudo.'%');	
		}
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT COUNT(idTipoConteudo) as total_registros FROM tb_tipo_conteudo A WHERE idTipoConteudo > 0".$filtro;
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
		
		if (!$total_registros){
			$total_registros = 0;
		}
		
		$qryCont = "
			SELECT 		*
					
			FROM 		tb_tipo_conteudo
			
			WHERE 		idTipoConteudo > 0 
			".$filtro." 
			ORDER BY 	nmTipoConteudo
			LIMIT 		".$inicio.",".$limite."		
		";
		
		$qryTipoConteudo = $db->query($qryCont);
?>        	
            
            <form name="formGeral" id="formGeral" action="menu-tipo-conteudo" method="post">
            <h1>Manutenção de Tipos de Conteúdo</h1>
            <br/>
            
            <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
            <div align="left">
            	<table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="7%">Filtrar por Nome</td>
                    	<td width="93%"><input name="nmTipoConteudo" id="nmTipoConteudo" size="30" maxlength="200" style="top:auto" value="<?php echo $nmTipoConteudo; ?>"/></td>
                    </tr>
                </table>
            	<button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
                <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-tipo-conteudo';">Cadastrar Novo</button>
                
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
                            if (strlen($_REQUEST["nmTipoConteudo"]) > 0){
                                $filtro2 = $filtro2 .''. $filtro2 = 'nmTipoConteudo='.$nmTipoConteudo;
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
                	<td colspan="3" align="center" width="9%">Op&ccedil;&otilde;es</td>
                	<td width="8%" align="center">ID</td>
                	<td width="80%" align="left">Nome</td>
               	</tr>
                <?php 
					$coluna = 1;
					for($i=0; $i<count($qryTipoConteudo); $i++){
					
					if($coluna % 2 == 0){
						$classe = 'tbNormal';
					} else {
						$classe = 'tbNormalAlt';
					}
				?>
            	<tr class="<?php echo $classe; ?>">
                	<td align="center" width="3%">
                    
                    	<?php if($_SESSION["PERFIL"] == 1 || $_SESSION["PERFIL"] == 8){ ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                                 onclick="excluirItem('<?php echo $qryTipoConteudo[$i]["idTipoConteudo"]; ?>','controller/act-tipo-conteudo','Excluir','idTipoConteudo');" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Excluir"
                                 alt="Excluir" border="0"/>
						<?php } else { ?>
                        	-
                        <?php } ?>
                    </td>
                	<td align="center" width="3%">
                    	<a href="<?php echo $url_raiz; ?>admin/cad-tipo-conteudo?idTipoConteudo=<?php echo $qryTipoConteudo[$i]["idTipoConteudo"]; ?>">
                            <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" title="Editar" alt="Editar" border="0"/>
                        </a>
                    </td>
                	<td align="center" width="3%">
                    
						<?php /*?><?php if($qryTipoConteudo[$i]["inExibir"] == 1){ ?>
                        	<a href="<?php echo $url_raiz; ?>admin/actTipoConteudo.php?acao=Destivar&idTipoConteudo=<?php echo $qryTipoConteudo[$i]["idTipoConteudo"]; ?>">
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                     title="Ativo. Clique para destivar"
                                     alt="Ativo. Clique para destivar" border="0"/>
                            </a>
                                 
                        <?php } else { ?>
                        	<a href="<?php echo $url_raiz; ?>admin/actTipoConteudo.php?acao=Ativar&idTipoConteudo=<?php echo $qryTipoConteudo[$i]["idTipoConteudo"]; ?>">
                                <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                 title="Desativado. Clique para ativar."
                                 alt="Desativado. Clique para ativar" border="0"/>
                            </a>
                        <?php } ?><?php */?> - 
                    
                    </td>
                	<td align="center" width="2%"><?php echo $qryTipoConteudo[$i]["idTipoConteudo"]; ?></td>
                	<td align="left"><?php echo $qryTipoConteudo[$i]["nmTipoConteudo"]; ?></td>
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