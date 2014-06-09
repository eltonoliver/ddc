<?php
	$acao = $_REQUEST['acao']?$_REQUEST['acao']:'';
	
//	new dBug($_POST);
//	exit;
	
	switch($acao){
		case 'Cadastrar':
		
			//Desativa todas as APIs cadastradas para esta rede social
			if($_POST['inAtivo'] == 1){
				$qry = $db->update("UPDATE tb_dados_api SET inAtivo = 0 WHERE idRedeSocial = ".$db->clean($_POST['idRedeSocial'])."");
			} 
		
			$data = dataFormatoBanco();
			$strCadatrar = $db->insertQuery(array(
										'idRedeSocial' 			=> $_POST['idRedeSocial'],
										'nmNomeApp' 			=> $_POST['nmNomeApp'],
										'nmAppId' 				=> trim($_POST['nmAppId']),
										'nmURLApp' 				=> trim($_POST['nmURLApp']),
										'nmCallbackURL' 		=> trim($_POST['nmCallbackURL']),
										'nmConsumerKey' 		=> trim($_POST['nmConsumerKey']),
										'nmConsumerSecret' 		=> trim($_POST['nmConsumerSecret']),
										'nmAcessToken' 			=> trim($_POST['nmAcessToken']),
										'nmAcessTokenSecret' 	=> trim($_POST['nmAcessTokenSecret']),
										'nmPermissionsList' 	=> trim($_POST['nmPermissionsList']),
										'idUsuarioCadastro' 	=> $_SESSION["ID"],
										'dtDataCadastro' 		=> $data,
										'inAtivo' 				=> $_POST['inAtivo']),
										
										'tb_dados_api');
			$qryCadastrar = $db->update($strCadatrar);
			$id = mysql_insert_id();
			
			if($qryCadastrar){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: ' . $url_raiz . 'admin/cad-a-p-is?idDadosApi='.$id);

		break;
		
		case 'Atualizar':
			//Desativa todas as APIs cadastradas para esta rede social
			if($_POST['inAtivo'] == 1){
				$qry = $db->update("UPDATE tb_dados_api SET inAtivo = 0 WHERE idRedeSocial = ".$db->clean($_POST['idRedeSocial'])."");
			} 
		
			$id = $_POST['idDadosApi'];
			$data = dataFormatoBanco();
			$strUpdate = $db->updateQuery(array(
										'idRedeSocial' => $_POST['idRedeSocial'],
										'nmNomeApp' => $_POST['nmNomeApp'],
										'nmAppId' => trim($_POST['nmAppId']),
										'nmURLApp' => trim($_POST['nmURLApp']),
										'nmCallbackURL' => trim($_POST['nmCallbackURL']),
										'nmConsumerKey' => trim($_POST['nmConsumerKey']),
										'nmConsumerSecret' => trim($_POST['nmConsumerSecret']),
										'nmAcessToken' => trim($_POST['nmAcessToken']),
										'nmAcessTokenSecret' => trim($_POST['nmAcessTokenSecret']),
										'nmPermissionsList' => trim($_POST['nmPermissionsList']),
										'idUsuarioCadastro' => $_SESSION["ID"],
										'dtDataCadastro' => $data,
										'inAtivo' => $_POST['inAtivo']),
										
										'tb_dados_api','idDadosApi = '.$db->clean($id));
			$qryUpdate = $db->update($strUpdate);
			
			if($qryUpdate){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: ' . $url_raiz . 'admin/cad-a-p-is?idDadosApi='.$id);
			
		break;
		
		case 'Excluir':
			$id = $_REQUEST['idDadosApi'];
			$strExcluir = 'DELETE FROM tb_dados_api WHERE idDadosApi = '.$db->clean($id);
			$qryExcluir = $db->update($strExcluir);
			
			if($qryExcluir){
				$_SESSION['msg'] = 'Item removido com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-apis');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/cad-a-p-is?idDadosApi='.$id);
			}
			
		break;
		
		case "Ativar":
			$id = $_REQUEST['idDadosApi'];
			
			//Busca a rede social da API que será desativada
			$strVerifica = "SELECT idRedeSocial FROM tb_dados_api WHERE idDadosApi = ".$db->clean($id)." LIMIT 1";
			$qryVerifica = $db->query($strVerifica);
			
			//Desativa todas as APIs cadastradas para esta rede social
			$qry = $db->update("UPDATE tb_dados_api SET inAtivo = 0 WHERE idRedeSocial = ".$qryVerifica[0]['idRedeSocial']."");
			
			$query = "
				UPDATE 	tb_dados_api
				SET		inAtivo = 1
				WHERE	idDadosApi	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item ativado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-apis');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-apis?idDadosApi='.$id);
			}
		break;
		
		case "Destivar":
			$id = $_REQUEST['idDadosApi'];
			$query = "
				UPDATE 	tb_dados_api
				SET		inAtivo = 0
				WHERE	idDadosApi	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item desativado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-apis');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-apis?idDadosApi='.$id);
			}
		break;
		
		default:
			header('Location: ' . $url_raiz . 'admin/acesso-negado');
		break;
	}
	exit();
?>