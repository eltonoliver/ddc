<?php
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";
	
	switch ($acao){
		case "Excluir":
			//Atualiza todos os itens para ficarem "Sem Categoria"
			$query1 = "UPDATE tb_conteudo SET idCategoria = NULL WHERE idCategoria = ".$db->clean($_REQUEST["idCategoria"]);
			$db->update($query1);
			
			//Deleta as categorias filhas
			$query2 = "
				DELETE	FROM tb_categoria
				WHERE	idCategoriaPai	= ".$db->clean($_REQUEST["idCategoria"])."
			";
			$db->update($query2);
			
			//Deleta todas as relações com conteúdos
			$query2 = "
				DELETE	FROM tb_conteudo_tag
				WHERE	idCategoria	= ".$db->clean($_REQUEST["idCategoria"])."
			";
			$db->update($query2);
			
			//Deleta todas as relações com arquivos
			$query2 = "
				DELETE	FROM tb_arquivo_categoria
				WHERE	idCategoria	= ".$db->clean($_REQUEST["idCategoria"])."
			";
			$db->update($query2);
			
			//Deleta os relacionamentos com os tipos de conteúdo
			$query2 = "
				DELETE	FROM tb_tipo_conteudo_categoria
				WHERE	idCategoria	= ".$db->clean($_REQUEST["idCategoria"])."
			";
			$db->update($query2);
			
			
			//Deleta a categoria
			$query3 = "
				DELETE	FROM tb_categoria
				WHERE	idCategoria	= ".$db->clean($_REQUEST["idCategoria"])."
			";
		
			$db->update($query3);
			
			//Retorno
			echo "
				<script type='text/javascript'>
					alert('Item excluído.');
					location.href='".$url_raiz."admin/menu-categorias?atualizado';
				</script>
			";
		break;
		
		case "Atualizar":
			$nmListaTipoConteudo = "";
			$nmListaTipoConteudo = implode(',',$_POST["nmListaTipoConteudo"]);
			
			//Atualiza a tabela com os dados do formulário
			$qry = $db->update($db->updateQuery(array(
				'nmCategoria' 			=> $_POST["nmCategoria"],
				'idCategoriaPai' 		=> $_POST["idCategoriaPai"],
				'inTipo' 				=> $_POST["inTipo"],
				'nmListaTipoConteudo' 	=> $nmListaTipoConteudo,
				'inDestaque' 			=> $_POST["inDestaque"]
			), 'tb_categoria', 'idCategoria='.$db->clean($_POST["idCategoria"])));
			
			$idInserido = $_POST["idCategoria"];
			
			$arrayTipos = $_POST["nmListaTipoConteudo"];
			//Insere os novos menus para o usuário
			for($i=0; $i<count($arrayTipos); $i++){
				$teste = $db->update($db->insertQuery(array(
					'idCategoria' => $idInserido,
					'idTipoConteudo' => $arrayTipos[$i]
				), 'tb_tipo_conteudo_categoria'));
			}
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: ' . $url_raiz."admin/cad-categoria?idCategoria=".$idInserido);
			
			
		break;	
		
		case "Cadastrar":
			$nmListaTipoConteudo = "";
			$nmListaTipoConteudo = implode(',',$_POST["nmListaTipoConteudo"]);
			
			//Atualiza a tabela com os dados do formulário
			$qry = $db->update($db->insertQuery(array(
				'nmCategoria' 			=> $_POST["nmCategoria"],
				'idCategoriaPai' 		=> $_POST["idCategoriaPai"],
				'inTipo' 				=> $_POST["inTipo"],
				'inDestaque' 			=> $_POST["inDestaque"],
				'nmListaTipoConteudo' 	=> $nmListaTipoConteudo
			), 'tb_categoria'));
			
			$idInserido = mysql_insert_id();
			
			$arrayTipos = $_POST["nmListaTipoConteudo"];
			//Insere os novos menus para o usuário
			for($i=0; $i<count($arrayTipos); $i++){				
				$teste = $db->update($db->insertQuery(array(
					'idCategoria' 		=> $idInserido,
					'idTipoConteudo' 	=> $arrayTipos[$i]
				)), 'tb_tipo_conteudo_categoria');
			}
			
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: ' . $url_raiz."admin/cad-categoria?idCategoria=".$idInserido);
		break;
		
		case 'cadastrar-json':
			
			$r = $db->select(array(
									'nmCategoria'=>trim($_POST["nmCategoria"]),
									'inTipo'=>$_POST["inTipo"]
									), 'tb_categoria');
			if($r){
				echo json_encode(array('status' => 1));
			}else{				
				$dados = array(
									'nmCategoria' 	=> $_POST["nmCategoria"],
									'idCategoriaPai'=> $_POST["idCategoriaPai"],
									'inTipo' 		=> $_POST["inTipo"],
									'inDestaque' 	=> $_POST["inDestaque"]);
				$query = $db->insertQuery($dados, 'tb_categoria');
				
				$db->update($query);
				echo json_encode(array('id' => mysql_insert_id(), 'status'=>2));
			}
			exit;
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