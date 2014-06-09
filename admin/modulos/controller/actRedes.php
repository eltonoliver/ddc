<?php
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";
	switch($acao){
		case 'atualizar':
			
			//Atualiza a tabela com os dados do formulário
			$qryAcao = $db->update($db->updateQuery(array(
				'nmUsuario' 		=> trim($_REQUEST["nmUsuario"]),
				'idUsuarioRede' 	=> trim($_REQUEST["idUsuarioRede"]),
				'nmURLCompleta' 	=> trim($_REQUEST["nmURLCompleta"]),
				'dtDataAtualizacao' => dataFormatoBanco(),
				'idUsuarioCadastro' => $_SESSION["ID"]
			), 'tb_rede_social', 'idRedeSocial='.$db->clean($_POST["idRedeSocial"])));
			
			if($qryAcao){
				$_SESSION['msg'] = 'Dados atualizados';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$url_raiz.'admin/dados-redes');
		break;
		
		case "Ativar":
			$id = $_REQUEST['idRedeSocial'];
			$query = "
				UPDATE 	tb_rede_social
				SET		inAtivo = 1
				WHERE	idRedeSocial	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item ativado com sucesso!';
				header('Location: '.$url_raiz.'admin/dados-redes');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/dados-redes?idRedeSocial='.$id);
			}
		break;
		
		case "Destivar":
			$id = $_REQUEST['idRedeSocial'];
			$query = "
				UPDATE 	tb_rede_social
				SET		inAtivo = 0
				WHERE	idRedeSocial	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item desativado com sucesso!';
				header('Location: '.$url_raiz.'admin/dados-redes');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/dados-redes?idRedeSocial='.$id);
			}
		break;
		
		default:
			header('Location: ' . $url_raiz . 'admin/acesso-negado');
		break;
	}
	exit();

?>