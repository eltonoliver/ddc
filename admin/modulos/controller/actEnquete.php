<?php
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";
	
	switch ($acao){
		case "Excluir":
		
			//Busca todos os votos para as respostas desta enquete
			$qryVotos = $db->query("SELECT idRespostaEnquete FROM tb_resposta_enquete WHERE idEnquete = ".$db->clean($_REQUEST["idEnquete"]));
			$listaRespostas = campoMatrizParaLista('',$qryVotos,'idRespostaEnquete');

			//1. Deleta os votos
				$query = "
					DELETE	FROM tb_enquete_voto
					WHERE	idRespostaEnquete IN (".$listaRespostas.")
				";
				$qry = $db->update($query);
		
			//2. Deleta as respostas
				$query = "
					DELETE	FROM tb_resposta_enquete
					WHERE	idEnquete	= ".$db->clean($_REQUEST["idEnquete"])."
				";
				$qry = $db->update($query);
		
			//3. Deleta a enquete
				$query = "
					DELETE	FROM tb_enquete
					WHERE	idEnquete	= ".$db->clean($_REQUEST["idEnquete"])."
				";
			
				$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-enquete');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-enquete?idEnquete='.$id);
			}
		break;
		
		case "ExcluirResposta":
			
			//Busca a enquete destea resposta
			$qryResp = $db->query("SELECT idEnquete FROM tb_resposta_enquete WHERE idRespostaEnquete = ".$db->clean($_REQUEST["idRespostaEnquete"])." LIMIT 1");
		
			//1. Deleta os votos
				$query = "
					DELETE	FROM tb_enquete_voto
					WHERE	idRespostaEnquete = ".$db->clean($_REQUEST["idRespostaEnquete"])."
				";
				$qry = $db->update($query);
		
			//2. Deleta a resposta
				$query = "
					DELETE	FROM tb_resposta_enquete
					WHERE	idRespostaEnquete	= ".$db->clean($_REQUEST["idRespostaEnquete"])."
				";
				$qry = $db->update($query);
			
			$id = $qryResp[0]["idEnquete"];
			
			if($qry){
				$_SESSION['msg'] = 'Resposta excluída com sucesso!';
				header('Location: '.$url_raiz.'admin/cad-enquete?idEnquete='.$id.'#secaoResposta');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/cad-enquete?idEnquete='.$id.'#secaoResposta');
			}
		break;
		
		case "DestaqueOn":
			//Marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			$qry = $db->update("UPDATE tb_enquete SET inDestaque = 0");
		
			$id = $_REQUEST['idEnquete'];
			$query = "
				UPDATE 	tb_enquete
				SET		inDestaque = 1
				WHERE	idEnquete	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-enquete');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-enquete?idEnquete='.$id);
			}
		break;
		
		case "DestaqueOff":
			//Marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			$qry = $db->update("UPDATE tb_enquete SET inDestaque = 0");
		
			$id = $_REQUEST['idEnquete'];
			$query = "
				UPDATE 	tb_enquete
				SET		inDestaque = 0
				WHERE	idEnquete	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-enquete');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-enquete?idEnquete='.$id);
			}
		break;
		
		case "IpOn":
			$id = $_REQUEST['idEnquete'];
			$query = "
				UPDATE 	tb_enquete
				SET		inLimitarIp = 1
				WHERE	idEnquete	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-enquete');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-enquete?idEnquete='.$id);
			}
		break;
		
		case "IpOff":
			$id = $_REQUEST['idEnquete'];
			$query = "
				UPDATE 	tb_enquete
				SET		inLimitarIp = 0
				WHERE	idEnquete	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-enquete');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-enquete?idEnquete='.$id);
			}
		break;
		
		case "Ativar":
			$id = $_REQUEST['idEnquete'];
			$query = "
				UPDATE 	tb_enquete
				SET		inAtivo = 1
				WHERE	idEnquete	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item ativado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-enquete');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-enquete?idEnquete='.$id);
			}
		break;
		
		case "Destivar":
			$id = $_REQUEST['idEnquete'];
			$query = "
				UPDATE 	tb_enquete
				SET		inAtivo = 0
				WHERE	idEnquete	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item desativado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-enquete');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-enquete?idEnquete='.$id);
			}
		break;
		
		
	
		case "CadastrarResposta":
		
			$query = $db->insertQuery(array(
										'nmResposta' => trim($_POST["nmResposta"]),
										'idEnquete' => $_POST["idEnquete"]
										),
										
										'tb_resposta_enquete');
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$url_raiz.'admin/cad-enquete?idEnquete='.$_POST["idEnquete"].'#secaoResposta');
			
		break;	
	
		case "Cadastrar":
		
			//Se este for o contato principal, marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			if($_POST["inDestaque"] == 1){
				$qry = $db->update("UPDATE tb_enquete SET inDestaque = 0");
			}
			$dataInicio = converteData($_POST["dtDataInicio"]);
			$dataFim = converteData($_POST["dtDataFim"]);
			
			$query = $db->insertQuery(array(
										'nmPergunta' => trim($_POST["nmPergunta"]),
										'nmDescricaoEnquete' => trim($_POST["nmDescricaoEnquete"]),
										'dtDataInicio' => $dataInicio,
										'dtDataFim' => $dataFim,
										'inDestaque' => $_POST["inDestaque"],
										'inAtivo' => $_POST["inAtivo"],
										'inLimitarIp' => $_POST["inLimitarIp"],
										'inTipoEnquete' => $_POST["inTipoEnquete"],
										'idUsuarioCadastro' => $_SESSION["ID"],
										'dtDataCadastro' => dataFormatoBanco()
										),
										
										'tb_enquete');
			$qry = $db->update($query);
			$idInserido = mysql_insert_id();
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$url_raiz.'admin/cad-enquete?idEnquete='.$idInserido.'#secaoResposta');
			
		break;	
		
		case "Atualizar":
			
			$idInserido = $_POST["idEnquete"];
			//Se este for o contato principal, marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			if($_POST["inDestaque"] == 1){
				$qry = $db->update("UPDATE tb_enquete SET inDestaque = 0");
			}
			$dataInicio = converteData($_POST["dtDataInicio"]);
			$dataFim = converteData($_POST["dtDataFim"]);
			
			$query = $db->updateQuery(array(
										'nmPergunta' => trim($_POST["nmPergunta"]),
										'nmDescricaoEnquete' => trim($_POST["nmDescricaoEnquete"]),
										'dtDataInicio' => $dataInicio,
										'dtDataFim' => $dataFim,
										'inDestaque' => $_POST["inDestaque"],
										'inAtivo' => $_POST["inAtivo"],
										'inLimitarIp' => $_POST["inLimitarIp"],
										'inTipoEnquete' => $_POST["inTipoEnquete"]
										),
										
										'tb_enquete', 'idEnquete ='.$db->clean($idInserido));
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$url_raiz.'admin/cad-enquete?idEnquete='.$idInserido.'#secaoResposta');
		break;
		
		default:
			header('Location: ' . $url_raiz . 'admin/acesso-negado');
		break;
	}
?>