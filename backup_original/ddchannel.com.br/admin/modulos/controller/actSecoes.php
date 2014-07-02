<?php
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";
	
	switch ($acao){
		case "Excluir":
			//Atualiza todos os itens para ficarem "Sem Categoria"
			$query1 = "SELECT idMenu FROM tb_menu WHERE idCategoria = ".$db->clean($_REQUEST["idCategoria"]);
			$qry1 = $db->select($query1);
			
			if($qry1){
				//Retorno
				$_SESSION['msgErro'] = 'Item não pode ser excluído porque já está associado a Conteúdos.';
				header('Location: '.$url_raiz.'admin/menu-secoes');
				
			} else {
				//Deleta as categorias filhas
				$query2 = "
					DELETE	FROM tb_categoria
					WHERE	idCategoriaPai	= ".$db->clean($_REQUEST["idCategoria"])."
				";
				$db->update($query2);
				
				//Deleta os relacionamentos com os tipos de conteúdo
				$query2 = "
					DELETE	FROM tb_tipo_conteudo_secao
					WHERE	idCategoria	= ".$db->clean($_REQUEST["idCategoria"])."
				";
				$db->update($query2);
				
				
				//Deleta a categoria
				$query3 = "
					DELETE	FROM tb_categoria
					WHERE	idCategoria	= ".$db->clean($_REQUEST["idCategoria"])."
				";
				$qry3 = $db->update($query3);
				
				if($qry3){
					$_SESSION['msg'] = 'Item excluído com sucesso!';
					header('Location: '.$url_raiz.'admin/menu-secoes');
				}else{
					$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
					header('Location: ' . $url_raiz . 'admin/menu-secoes');
				}
			}
			
			
		break;
		
		case "Atualizar":
			$nmListaTipoConteudo = "";
			$nmListaTipoConteudo = implode(',',$_POST["nmListaTipoConteudo"]);
			
			//Atualiza a tabela com os dados do formulário
			$qry = $db->update($db->updateQuery(array(
				'nmCategoria' 			=> trim($_POST["nmCategoria"]),
				'idCategoriaPai' 		=> $_POST["idCategoriaPai"],
				'inTipo' 				=> $_POST["inTipo"],
				'nmListaTipoConteudo' 	=> $nmListaTipoConteudo,
				'inDestaque' 			=> $_POST["inDestaque"],
				'nrOrdem' 				=> $_POST["nrOrdem"],
				'nmPalavraChave' 		=> trim($_POST["nmPalavraChave"])
			), 'tb_categoria', 'idCategoria='.$db->clean($_POST["idCategoria"])));
			
			$idInserido = $_POST["idCategoria"];
			
			
			//Deleta todos os tipos de conteúdo associados a esta categoria
			$query = "DELETE FROM tb_tipo_conteudo_secao WHERE idCategoria = ".$db->clean($idInserido)."";//Por segurança, o usuário administrador não pode ser alterado
			$db->update($query);
			
			$arrayTipos = $_POST["nmListaTipoConteudo"];
			//Insere os novos menus para o usuário
			for($i=0; $i<count($arrayTipos); $i++){
				$query = "INSERT INTO tb_tipo_conteudo_secao (idCategoria, idTipoConteudo) VALUES (".$db->clean($idInserido).",".$db->clean($arrayTipos[$i]).")";
				$teste = $db->update($query);
			}
			
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: ' . $url_raiz."admin/cad-secoes?idCategoria=".$idInserido);
			
			
		break;	
		
		case "Cadastrar":
			$nmListaTipoConteudo = "";
			$nmListaTipoConteudo = implode(',',$_POST["nmListaTipoConteudo"]);
			
			//Atualiza a tabela com os dados do formulário
			$qry = $db->update($db->insertQuery(array(
				'nmCategoria' => trim($_POST["nmCategoria"]),
				'idCategoriaPai' => $_POST["idCategoriaPai"],
				'inTipo' => $_POST["inTipo"],
				'inDestaque' => $_POST["inDestaque"],
				'nmListaTipoConteudo' => $nmListaTipoConteudo,
				'nrOrdem' => $_POST["nrOrdem"],
				'nmPalavraChave' => trim($_POST["nmPalavraChave"])
			), 'tb_categoria'));
			
			$idInserido = mysql_insert_id();
			
			$arrayTipos = $_POST["nmListaTipoConteudo"];
			//Insere os novos menus para o usuário
			for($i=0; $i<count($arrayTipos); $i++){
				$query = "INSERT INTO tb_tipo_conteudo_secao (idCategoria, idTipoConteudo) VALUES ('".$idInserido."',".$db->clean($arrayTipos[$i]).")";
				$teste = $db->update($query);
			}
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: ' . $url_raiz."admin/cad-secoes?idCategoria=".$idInserido);		
		break;	
		
		default:
			header('Location: ' . $url_raiz . 'admin/acesso-negado');
		break;
	}
?>