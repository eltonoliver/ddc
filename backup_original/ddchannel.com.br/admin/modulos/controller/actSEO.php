<?php
	$acao = $_REQUEST['acao']?$_REQUEST['acao']:'';
	
	$query = $db->updateQuery(array(
								'nmMetaAuthor' => '2012 - Ugagogo Invencionices Tecnologicas',
								'nmMetaRegistro' => trim($geralConfig[0]["nmTituloSite"]),
								'nmMetaKeywords' => trim($_POST["nmMetaKeywords"]),
								'nmMetaDescricao' => trim($_POST["nmMetaDescricao"]),
								'nmGoogleAnalytics' => trim($_POST["nmGoogleAnalytics"])
								),
								
								'tb_geral', 'idGeral > 0');
	
	//Atualiza a tabela com os dados do formulário
	$qryGeral = $db->update($query);
	
	if($qryGeral){
		$_SESSION['msg'] = 'Dados atualizados com sucesso!';
	}else{
		$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
	}
	
	header('Location: ' . $url_raiz . 'admin/dados-s-e-o');
	
?>