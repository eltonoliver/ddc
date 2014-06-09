<?php
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";
	
	switch ($acao){
		case "Excluir":
		
			//Deleta o contato
			$query = "
				DELETE	FROM tb_endereco_site
				WHERE	idEnderecoSite	= ".$db->clean($_REQUEST["idEnderecoSite"])."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-endereco');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-endereco?idEnderecoSite='.$id);
			}
		break;
		
		
		case "PrincipalOn":
			//Marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			$qry = $db->update("UPDATE tb_endereco_site SET inPrincipal = 0");
		
			$id = $db->clean($_REQUEST['idEnderecoSite']);
			$query = "
				UPDATE 	tb_endereco_site
				SET		inPrincipal = 1
				WHERE	idEnderecoSite	= ".$id."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-endereco');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-endereco?idEnderecoSite='.$id);
			}
		break;
		
		case "PrincipalOff":
			//Marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			$qry = $db->update("UPDATE tb_endereco_site SET inPrincipal = 0");
		
			$id = $db->clean($_REQUEST['idEnderecoSite']);
			$query = "
				UPDATE 	tb_endereco_site
				SET		inPrincipal = 0
				WHERE	idEnderecoSite	= ".$id."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-endereco');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-endereco?idEnderecoSite='.$id);
			}
		break;
		
		case "FormularioOn":
			$id = $_REQUEST['idEnderecoSite'];
			$query = "
				UPDATE 	tb_endereco_site
				SET		inExibir = 1
				WHERE	idEnderecoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-endereco');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-endereco?idEnderecoSite='.$id);
			}
		break;
		
		case "FormulariOff":
			$id = $_REQUEST['idEnderecoSite'];
			$query = "
				UPDATE 	tb_endereco_site
				SET		inExibir = 0
				WHERE	idEnderecoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item alterado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-endereco');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-endereco?idEnderecoSite='.$id);
			}
		break;
		
		case "Ativar":
			$id = $_REQUEST['idEnderecoSite'];
			$query = "
				UPDATE 	tb_endereco_site
				SET		inAtivo = 1
				WHERE	idEnderecoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item ativado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-endereco');
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-endereco?idEnderecoSite='.$id);
			}
		break;
		
		case "Destivar":
			$id = $_REQUEST['idEnderecoSite'];
			$query = "
				UPDATE 	tb_endereco_site
				SET		inAtivo = 0
				WHERE	idEnderecoSite	= ".$db->clean($id)."
			";
		
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Item desativado com sucesso!';
				header('Location: '.$url_raiz.'admin/menu-endereco');

			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				header('Location: ' . $url_raiz . 'admin/menu-endereco?idEnderecoSite='.$id);
			}
		break;
		
		case "Cadastrar":
		
			//Se este for o contato principal, marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			if($_POST["inPrincipal"] == 1){
				$qry = $db->update("UPDATE tb_endereco_site SET inPrincipal = 0");
			}
			
			$query = $db->insertQuery(array(
										'idTipoLogradouro' => $_POST["idTipoLogradouro"],
										'nmTituloEndereco' => trim($_POST["nmTituloEndereco"]),
										'nmLogradouro' => trim($_POST["nmLogradouro"]),
										'nrNumero' => trim($_POST["nrNumero"]),
										'nmComplemento' => trim($_POST["nmComplemento"]),
										'nmPontoReferencia' => trim($_POST["nmPontoReferencia"]),
										'nmBairro' => trim($_POST["nmBairro"]),
										'nmCEP' => trim($_POST["nmCEP"]),
										'nmEstado' => trim($_POST["nmEstado"]),
										'nmCidade' => trim($_POST["nmCidade"]),
										'inPrincipal' => $_POST["inPrincipal"],
										'inExibir' => $_POST["inExibir"],
										'inAtivo' => $_POST["inAtivo"],
										'nmTelefoneComercial' => $_POST["nmTelefoneComercial"],
										'nmTelefoneCelular' => $_POST["nmTelefoneCelular"],
										'nmTelefoneConvencional' => $_POST["nmTelefoneConvencional"],
										'nmTelefoneOutros' => $_POST["nmTelefoneOutros"],
										'nmCodigoMaps' => $_POST["nmCodigoMaps"]
										),
										
										'tb_endereco_site');
			$qry = $db->update($query);
			$idInserido = mysql_insert_id();
			
			//Upload da imagem
			if(!vazio($_FILES["nmLinkImagemMapa"]["name"])){
				
				$imagem = $_FILES["nmLinkImagemMapa"];
				$extensoes = "jpg,png,gif,jpeg";
				$caminho = $raiz.'img/';
				
				$up->setCaminho($caminho);
				$up->setArquivo($imagem);
				$up->setExtensoes($extensoes);
				$up->setAltura('195');
				$up->setLargura('316');
				
				$retorno = $up->enviarArquivo();

				if(!vazio($retorno["erro"])){
					
					$_SESSION['msgErro'] = 'Ocorreu um erro: '.$retorno["erro"];
					header('Location: ' . $url_raiz . 'admin/cad-endereco');
					exit;
					
				} else {
					$nmLinkImagemMapa = $retorno["nome_arquivo"];
					$qry = $db->update("UPDATE tb_endereco_site SET nmLinkImagemMapa = '".$nmLinkImagemMapa."' WHERE idEnderecoSite = ".$idInserido."");
				}
			}
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$url_raiz.'admin/cad-endereco?idEnderecoSite='.$idInserido);
			
		break;	
		
		case "Atualizar":
		
			$idInserido = $_POST["idEnderecoSite"];
		
			//Se este for o contato principal, marca todos os outros contatos da tabela como contatos simples
				//Somente pode existir um contato principal
			if($_POST["inPrincipal"] == 1){
				$qry = $db->update("UPDATE tb_endereco_site SET inPrincipal = 0");
			}
			
			$query = $db->updateQuery(array(
										'idTipoLogradouro' => $_POST["idTipoLogradouro"],
										'nmTituloEndereco' => trim($_POST["nmTituloEndereco"]),
										'nmLogradouro' => trim($_POST["nmLogradouro"]),
										'nrNumero' => trim($_POST["nrNumero"]),
										'nmComplemento' => trim($_POST["nmComplemento"]),
										'nmPontoReferencia' => trim($_POST["nmPontoReferencia"]),
										'nmBairro' => trim($_POST["nmBairro"]),
										'nmCEP' => trim($_POST["nmCEP"]),
										'nmEstado' => trim($_POST["nmEstado"]),
										'nmCidade' => trim($_POST["nmCidade"]),
										'inPrincipal' => $_POST["inPrincipal"],
										'inExibir' => $_POST["inExibir"],
										'inAtivo' => $_POST["inAtivo"],
										'nmTelefoneComercial' => $_POST["nmTelefoneComercial"],
										'nmTelefoneCelular' => $_POST["nmTelefoneCelular"],
										'nmTelefoneConvencional' => $_POST["nmTelefoneConvencional"],
										'nmTelefoneOutros' => $_POST["nmTelefoneOutros"],
										'nmCodigoMaps' => $_POST["nmCodigoMaps"]
										),
										
										'tb_endereco_site', 'idEnderecoSite ='.$db->clean($idInserido));
			$qry = $db->update($query);
			//Upload da imagem
			if(!vazio($_FILES["nmLinkImagemMapa"]["name"])){
				
				$imagem = $_FILES["nmLinkImagemMapa"];
				$extensoes = "jpg,png,gif,jpeg";
				$caminho = $raiz.'img/';
				
				$up->setCaminho($caminho);
				$up->setArquivo($imagem);
				$up->setExtensoes($extensoes);
				$up->setAltura('195');
				$up->setLargura('316');
				
				$retorno = $up->enviarArquivo();
				if(!vazio($retorno["erro"])){ 
					$_SESSION['msgErro'] = 'Ocorreu um erro: '.$retorno["erro"];
					header('Location: ' . $url_raiz . 'admin/cad-endereco?idInserido='.$idInserido);
					exit;
				} else {
					$nmLinkImagemMapa = $retorno["nome_arquivo"];
					$qry = $db->update("UPDATE tb_endereco_site SET nmLinkImagemMapa = '".$nmLinkImagemMapa."' WHERE idEnderecoSite = ".$idInserido."");
				}
			}
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			//new dBUg($qry);
			
			header('Location: '.$url_raiz.'admin/cad-endereco?idEnderecoSite='.$idInserido);
			
		break;
		
		
		default:
			header('Location: ' . $url_raiz . 'admin/acesso-negado');
		break;
	}
?>