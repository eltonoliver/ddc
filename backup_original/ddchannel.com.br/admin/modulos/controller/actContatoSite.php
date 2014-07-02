<?php
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";
	
	switch ($acao){
		case "Excluir":
		
			//Deleta o contato
			$query = "
				DELETE	FROM tb_contato_site
				WHERE	idContatoSite	= ".$db->clean($_REQUEST["idContatoSite"])."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-contato-site');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-contato-site?idContatoSite='.$id);
			}
		break;
		
		
		case "PrincipalOn":
			//Marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			$qry = $db->update("UPDATE tb_contato_site SET inContatoPrincipal = 0");
		
			$id = $_REQUEST['idContatoSite'];
			$query = "
				UPDATE 	tb_contato_site
				SET		inContatoPrincipal = 1
				WHERE	idContatoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-contato-site');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-contato-site?idContatoSite='.$id);
			}
		break;
		
		case "PrincipalOff":
			//Marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			$qry = $db->update("UPDATE tb_contato_site SET inContatoPrincipal = 0");
		
			$id = $_REQUEST['idContatoSite'];
			$query = "
				UPDATE 	tb_contato_site
				SET		inContatoPrincipal = 0
				WHERE	idContatoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-contato-site');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-contato-site?idContatoSite='.$id);
			}
		break;
		
		case "FormularioOn":
			$id = $_REQUEST['idContatoSite'];
			$query = "
				UPDATE 	tb_contato_site
				SET		inExibir = 1
				WHERE	idContatoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-contato-site');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-contato-site?idContatoSite='.$id);
			}
		break;
		
		case "FormulariOff":
			$id = $_REQUEST['idContatoSite'];
			$query = "
				UPDATE 	tb_contato_site
				SET		inExibir = 0
				WHERE	idContatoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-contato-site');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-contato-site?idContatoSite='.$id);
			}
		break;
		
		case "Ativar":
			$id = $_REQUEST['idContatoSite'];
			$query = "
				UPDATE 	tb_contato_site
				SET		inAtivo = 1
				WHERE	idContatoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item ativado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-contato-site');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-contato-site?idContatoSite='.$id);
			}
		break;
		
		case "Destivar":
			$id = $_REQUEST['idContatoSite'];
			$query = "
				UPDATE 	tb_contato_site
				SET		inAtivo = 0
				WHERE	idContatoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item desativado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-contato-site');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-contato-site?idContatoSite='.$id);
			}
		break;
		
		case "Cadastrar":
		
			//Se este for o contato principal, marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			if($_POST["inContatoPrincipal"] == 1){
				$qry = $db->update("UPDATE tb_contato_site SET inContatoPrincipal = 0");
			}
			
			$query = $db->insertQuery(array(
										'nmContato' => trim($_POST["nmContato"]),
										'nmDescricaoContato' => trim($_POST["nmDescricaoContato"]),
										'nmEmailContato' => trim($_POST["nmEmailContato"]),
										'inAtivo' => $_POST["inAtivo"],
										'inContatoPrincipal' => $_POST["inContatoPrincipal"],
										'inExibir' => $_POST["inExibir"]
										),
										
										'tb_contato_site');
			$qry = $db->update($query);
			$idInserido = mysql_insert_id();
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$url_raiz.'admin/cad-contato-site?idContatoSite='.$idInserido);
			
		break;	
		
		case "Atualizar":
			
			$idInserido = $_POST["idContatoSite"];
			//Se este for o contato principal, marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			if($_POST["inContatoPrincipal"] == 1){
				$qry = $db->update("UPDATE tb_contato_site SET inContatoPrincipal = 0");
			}
			
			$query = $db->updateQuery(array(
										'nmContato' => trim($_POST["nmContato"]),
										'nmDescricaoContato' => trim($_POST["nmDescricaoContato"]),
										'nmEmailContato' => trim($_POST["nmEmailContato"]),
										'inAtivo' => $_POST["inAtivo"],
										'inContatoPrincipal' => $_POST["inContatoPrincipal"],
										'inExibir' => $_POST["inExibir"]
										),
										
										'tb_contato_site', 'idContatoSite ='.$db->clean($idInserido));
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$url_raiz.'admin/cad-contato-site?idContatoSite='.$idInserido);
			
		break;
		
		
		default:
			header('Location: ' . $url_raiz . 'admin/acesso-negado');
		break;
	}
?>