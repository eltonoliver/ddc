<?php 
	$qryMenusPai = $db->query("SELECT * FROM tb_menu WHERE  idMenu > 0 AND inExibir = 1 AND idTipoMenu = 2 ORDER BY nmMenu DESC");//idMenuPai = 0 AND
	$qryPerfis = $db->query("SELECT * FROM tb_perfil ORDER BY nmPerfil");
?>

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
		
		//Seta um filtro vazio
		$filtro = '';
		
		//Se veio o tipo no FORM ou URL, adiciona ao filtro
		if (isset($_REQUEST["idMenuPai"]) && strlen($_REQUEST["idMenuPai"]) > 0){
			$idMenuPai = $_REQUEST["idMenuPai"];
			$filtro .= ' AND A.idMenuPai = '.$db->clean($idMenuPai);	
		} 
		
		if (isset($_REQUEST["nmMenu"]) && strlen($_REQUEST["nmMenu"]) > 0){
			$nmMenu = $_REQUEST["nmMenu"];
			$filtro .= ' AND A.nmMenu like '.$db->clean('%'.$nmMenu.'%');	
		} 
		
		if (isset($_REQUEST["idPerfil"]) && strlen($_REQUEST["idPerfil"]) > 0){
			$idPerfil = $_REQUEST["idPerfil"];
			
			$strMenus = "SELECT DISTINCT(idMenu) FROM tb_perfil_menu WHERE idPerfil = ".$idPerfil;
			$qryMenusFiltro = $db->query($strMenus);
			$listaMenus = campoMatrizParaLista('',$qryMenusFiltro,'idMenu');
			if(strlen($listaMenus) > 0){
				$filtro = $filtro ." ". $filtro = "AND A.idMenu in (".$listaMenus.")";	
			} else {
				$filtro = $filtro ." ". $filtro = "AND A.idMenu in (0)";	
			}
		} 
		
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT COUNT(A.idMenu) as total_registros FROM tb_menu A WHERE A.idTipoMenu = 2 ".$filtro;
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
?>

<?php 
	//QUERY PAGINADA
		//Exemplo: "SELECT * FROM nome_da_tabela LIMIT $inicio,$limite"
		
		$qryCont = "
			SELECT 		B.nmMenu as nmMenuPai,
						A.*
			FROM 		tb_menu A
			LEFT JOIN	tb_menu B ON (A.idMenuPai = B.idMenu)
			WHERE 		A.idTipoMenu = 2   
			".$filtro." 
			ORDER BY 	B.nmMenu, A.idMenu, A.nmMenu, A.ordemMenu DESC
			LIMIT 		".$inicio.",".$limite."		
		";
		//new dBug($qryCont);

		
		$qryMenus = $db->query($qryCont);
?>  
            <form name="formGeral" id="formGeral" action="menu-menus-externos" method="post">
            <h1>Manutenção de Menus Externos</h1>
            <br/>
            
            <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
            <div align="left">
            	 
            	<table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="7%">Filtrar por Nome</td>
                    	<td width="93%"><input name="nmMenu" id="nmMenu" size="30" maxlength="200" style="top:auto" value="<?php echo $nmMenu; ?>"/></td>
                    </tr>
                	<tr>
                    	<td width="7%">Filtrar por Pai</td>
                    	<td width="93%">
                              <select name="idMenuPai" id="idMenuPai"  style="top:auto">
                                    <option value="">Todos</option>
                                    <?php for($i=0; $i<count($qryMenusPai); $i++){ ?>
                                    <option value="<?php echo $qryMenusPai[$i]["idMenu"]; ?>" <?php if($qryMenusPai[$i]["idMenu"] == $idMenuPai){ echo 'selected'; } ?>><?php echo $qryMenusPai[$i]["descricaoMenu"]; ?></option>
                                    <?php } ?>
                              </select>
                        </td>
                    </tr>
                	<tr>
                    	<td width="7%">Filtrar por Perfil</td>
                    	<td width="93%">
                              <select name="idPerfil" id="idPerfil"  style="top:auto">
                                    <option value="">Todos</option>
                                    <?php for($j=0; $j<count($qryPerfis); $j++){ ?>
                                    <option value="<?php echo $qryPerfis[$j]["idPerfil"]; ?>" <?php if($qryPerfis[$j]["idPerfil"] == $idPerfil){ echo 'selected'; } ?>><?php echo $qryPerfis[$j]["nmPerfil"]; ?></option>
                                    <?php } ?>
                              </select>
                        </td>
                    </tr>
                </table>
                  
            	<button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
                <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-menu-externo';">Cadastrar Novo</button>
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
                            if (strlen($idMenuPai) > 0){
                                $filtro2 = $filtro2 .''. $filtro2 = 'idMenuPai='.$idMenuPai;
                            } 
							
                            if (strlen($nmMenu) > 0){
                                $filtro2 = $filtro2 .''. $filtro2 = '&nmMenu='.$nmMenu;
                            } 
							
                            if (strlen($idPerfil) > 0){
                                $filtro2 = $filtro2 .''. $filtro2 = '&idPerfil='.$idPerfil;
                            } 
                            
                           
                            //Nome da p�gina 
                            $stringPagina = '?atualizado'.$filtro2;
                        
                        //Chama a fun�ao que monta a exibi�ao do paginador
                        navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
                    
                ?>
            </div>
            <fieldset>
        	<table width="100%" border="0" align="center" class="tbLista">
            	<tr class="tbTitulo">
                	<td colspan="3" align="center">Op&ccedil;&otilde;es</td>
                	<td width="9%" align="center">Ativo?</td>
                	<td width="22%" align="left">Menu Pai</td>
                	<td width="59%" align="left">Nome</td>
               	</tr>
                <?php 
					$coluna = 1;
					for($i=0; $i<count($qryMenus); $i++){
					
					if($coluna % 2 == 0){
						$classe = 'tbNormal';
					} else {
						$classe = 'tbNormalAlt';
					}
				?>
            	<tr class="<?php echo $classe; ?>">
                	<td align="center" width="3%">
                    	<img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                             onClick="excluirItem('<?php echo $qryMenus[$i]["idMenu"]; ?>','controller/act-menu-externo','Excluir','idMenu');" 
                             onmouseover="javascript: this.style.cursor='pointer';"
                             title="Excluir"
                             alt="Excluir"/>
                    </td>
                	<td align="center" width="3%">
                    	<a href="cad-menu-externo?idMenu=<?php echo $qryMenus[$i]["idMenu"]; ?>&idMenuPai=<?php echo $qryMenus[$i]["idMenuPai"]; ?>">
                            <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                                 title="Editar"
                                 alt="Editar" border="0"/>
                        </a>
                    </td>
                	<td align="center" width="4%">
						<?php if($qryMenus[$i]["inExibir"] == 1){ ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                 onclick="javascript: location.href='controller/act-menu-externo?acao=Destivar&idMenu=<?php echo $qryMenus[$i]["idMenu"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Ativo. Clique para destivar"
                                 alt="Ativo. Clique para destivar"/>
                                 
                        <?php } else { ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" 
                                 onclick="javascript: location.href='controller/act-menu-externo?acao=Ativar&idMenu=<?php echo $qryMenus[$i]["idMenu"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Desativado. Clique para ativar."
                                 alt="Desativado. Clique para ativar"/>
                                 
                        <?php } ?>
                    </td>
                	<td width="9%" align="center">
                    	<?php if($qryMenus[$i]["inExibir"] == 1){ ?>
							Sim                                 
                        <?php } else { ?>
							Nao
                        <?php } ?>
                    </td>
                	<td width="22%" align="left"><?php echo $qryMenus[$i]["nmMenuPai"]; ?></td>
                	<td width="59%" align="left"><?php echo $qryMenus[$i]["nmMenu"]; ?></td>
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