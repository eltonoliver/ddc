<?php
	
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";

	switch ($acao){
		case "buscaOrdem":
		
			$idMenu = $db->clean($_REQUEST["idMenu"]);
			$idSecao = $db->clean($_REQUEST["idMenu"]);
			
			if($idMenu == 0){
				$query = "
					SELECT	MAX(ordemMenu) AS ordemMenu
					FROM	tb_menu
					WHERE	idCategoria	= ".$idSecao."
					LIMIT 	1
				";
				
			} else {
				$query = "
					SELECT	MAX(ordemMenu) AS ordemMenu
					FROM	tb_menu
					WHERE	idMenuPai	= ".$idMenu."
					LIMIT 	1
				";
			}
		
			//Atualiza a tabela com os dados do formulário
			$qryOrdem = $db->query($query);
			
			if($qryOrdem){
				echo json_encode(
								array('ordem' => $qryOrdem[0]["ordemMenu"])
				);
				
			} else {
				echo json_encode(
								array('ordem' => 0)
				);
			}
		break;
		
		case "Ativar":
		
			$query = "
				UPDATE 	tb_menu
				SET		inExibir = 1
				WHERE	idMenu	= ".$db->clean($_REQUEST["idMenu"])."
				OR 		idMenuPai	= ".$db->clean($_REQUEST["idMenu"])."
			";
		
			//Atualiza a tabela com os dados do formulário
			$db->update($query);
			
			//Retorno
			echo "
				<script type='text/javascript'>
					alert('Menu Ativado.');
					location.href='".$url_raiz."admin/menu-menus-externos?atualizado';
				</script>
			";
		break;
		
		case "Destivar":
		
			$query = "
				UPDATE 	tb_menu
				SET		inExibir = 0
				WHERE	idMenu	= ".$db->clean($_REQUEST["idMenu"])."
				OR 		idMenuPai	= ".$db->clean($_REQUEST["idMenu"])."
			";
		
			//Atualiza a tabela com os dados do formulário
			$db->update($query);
			
			//Retorno
			echo "
				<script type='text/javascript'>
					alert('Menu Desativado.');
					location.href='".$url_raiz."admin/menu-menus-externos?atualizado';
				</script>
			";
		break;
		
		case "Excluir":
		
			//1. Busca todos os menus filhos
			$qryFilhos = $db->query("SELECT idMenu FROM tb_menu WHERE	idMenuPai = ".$db->clean($_REQUEST["idMenu"]));
			$listaFilhos = campoMatrizParaLista('',$qryFilhos,'idMenu');

				//1.1 Se possui filhos, deleta
				if($qryFilhos){
					//1.1.1 Deleta os relacionamentos dos filhos com os perfis
					$query = "
						DELETE	FROM tb_perfil_menu
						WHERE	idMenu in (".$listaFilhos.")
					";
					$db->update($query);
					
					//1.1.2 Deleta os menus filhos
					$query = "
						DELETE	FROM tb_menu
						WHERE	idMenu in (".$listaFilhos.")
					";
					$db->update($query);
					
				}
			
			//2 Deleta os relacionamentos dos menus com os perfis
			$query = "
				DELETE	FROM tb_perfil_menu
				WHERE	idMenu = ".$db->clean($_REQUEST["idMenu"])."
			";
			$db->update($query);
			
			//3. Deleta os menus
			$query = "
				DELETE	FROM tb_menu
				WHERE	idMenu = ".$db->clean($_REQUEST["idMenu"])."
			";
			$db->update($query);
			
			//Retorno
			echo "
				<script type='text/javascript'>
					alert('Menu excluído.');
					location.href='".$url_raiz."admin/menu-menus-externos?atualizado';
				</script>
			";
		break;
		
		case "Atualizar":
		
			$id = $_POST['idMenu'];
			$strCadatrar = $db->updateQuery(array(
										'nmMenu' => $_POST['nmMenu'],
										'descricaoMenu' => $_POST['descricaoMenu'],
										'idMenuPai' => $_POST['idMenuPai'],
										'inExibir' => trim($_POST['inExibir']),
										'idConteudo' => $_POST['idConteudo'],
										'linkMenu' => (($_POST['inTipoLink'] == '1')?'':trim($_POST['linkMenu'])),
										'ordemMenu' => trim($_POST['ordemMenu']),
										'idTipoMenu' => trim($_POST['idTipoMenu']),
										'inTipoLink' => trim($_POST['inTipoLink']),
										'idCategoria' => trim($_POST['idCategoria'])
										),
										
										'tb_menu', 'idMenu = '.$db->clean($id));
			$qryCadastrar = $db->update($strCadatrar);
			
			if($qryCadastrar){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: ' . $url_raiz . 'admin/cad-menu-externo?idMenu='.$id);
			
		break;	
		
		case "Cadastrar":
		
			$strCadatrar = $db->insertQuery(array(
										'nmMenu' => $_POST['nmMenu'],
										'descricaoMenu' => $_POST['descricaoMenu'],
										'idMenuPai' => $_POST['idMenuPai'],
										'inExibir' => trim($_POST['inExibir']),
										'idConteudo' => $_POST['idConteudo'],
										'linkMenu' => (($_POST['inTipoLink'] == '1')?'':trim($_POST['linkMenu'])),
										'ordemMenu' => trim($_POST['ordemMenu']),
										'idTipoMenu' => trim($_POST['idTipoMenu']),
										'inTipoLink' => trim($_POST['inTipoLink']),
										'idCategoria' => trim($_POST['idCategoria'])
										),
										
										'tb_menu');
			$qryCadastrar = $db->update($strCadatrar);
			
			$id = mysql_insert_id();
			
			if($qryCadastrar){
				$_SESSION['msg'] = 'Dados cadastrados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: ' . $url_raiz . 'admin/cad-menu-externo?idMenu='.$id);
		break;	
		
		default:
			echo "
				<script type='text/javascript'>
					alert('Você não pode acessar esta página diretamente.');
					location.href='".$url_raiz."admin/login';
				</script>
			";
		break;	
	}
	
	
	
	

?>