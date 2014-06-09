<?php
	
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";
	
	switch ($acao){
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
					location.href='".$url_raiz."admin/menu-menus?atualizado';
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
					location.href='".$url_raiz."admin/menu-menus?atualizado';
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
					location.href='".$url_raiz."admin/menu-menus?atualizado';
				</script>
			";
		break;
		
		case "Atualizar":
			
			//Atualiza a tabela com os dados do formulário
			$teste = $db->update($db->updateQuery(array(
				'nmMenu' 		=> $_POST["nmMenu"],
				'descricaoMenu' => $_POST["descricaoMenu"],
				'idMenuPai' 	=> $_POST["idMenuPai"],
				'ordemMenu' 	=> $_POST["ordemMenu"],
				'linkMenu' 		=> $_POST["linkMenu"],
				'inExibir' 		=> $_POST["inExibir"]
			), 'tb_menu', 'idMenu='.$db->clean($_POST["idMenu"])));

			
			$query = "DELETE FROM tb_perfil_menu WHERE idMenu = ".$db->clean($_POST["idMenu"]);
			$db->update($query);
			
			for($i=0; $i<=$_POST["totalPerfis"]; $i++){
				
				$campo = 'idPerfil_'.$i;
				$valor = $_POST[$campo];
				if(strlen($_POST[$campo]) > 0){
		
					
					$query = "INSERT INTO tb_perfil_menu (idPerfil, idMenu) VALUES ('".$valor."',".$db->clean($_POST["idMenu"]).")";
					$db->update($query);					
				}
			
			}
			
			
			echo "
				<script type='text/javascript'>
					alert('Dados atualizados com sucesso!');
					location.href='".$url_raiz."admin/cad-menu?idMenu=".$_POST["idMenu"]."&idMenuPai=".$_POST["idMenuPai"]."&atualizado';
				</script>
			";
		break;	
		
		case "Cadastrar":
			
			//Atualiza a tabela com os dados do formulário
			$teste = $db->update($db->insertQuery(array(
				'nmMenu' 		=> $_POST["nmMenu"],
				'descricaoMenu' => $_POST["descricaoMenu"],
				'idMenuPai' 	=> $_POST["idMenuPai"],
				'inExibir' 		=> $_POST["inExibir"],
				'linkMenu' 		=> $_POST["linkMenu"],
				'ordemMenu' 	=> $_POST["ordemMenu"],
				'idTipoMenu' 	=> $_POST["idTipoMenu"]
			), 'tb_menu'));
			
			$idInserido = mysql_insert_id();
			
			for($i=0; $i<=$_POST["totalPerfis"]; $i++){
				
				$campo = 'idPerfil_'.$i;
				$valor = $_POST[$campo];
				if(strlen($_POST[$campo]) > 0){

					$query = "INSERT INTO tb_perfil_menu (idPerfil, idMenu) VALUES (".$db->clean($valor).",'".$idInserido."')";
					$db->update($query);
				}
				
			
			}
			
			echo "
				<script type='text/javascript'>
					alert('Dados inseridos com sucesso!');
					location.href='".$url_raiz."admin/cad-menu?idMenuPai=".$_POST["idMenuPai"]."&idMenu=".$idInserido."&atualizado';
				</script>
			";
			
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