<?php
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";

	switch ($acao){
		case "Excluir":	

			//Verifica se o tipo de conte�do j� est� associado a conte�dos cadastrados
			$strVerifica = "SELECT COUNT(*) as total FROM tb_conteudo WHERE idTipoConteudo = ".$db->clean($_REQUEST['idTipoConteudo'])."'";
			$queryVerifica = $db->query($strVerifica);
			
			if($queryVerifica[0]["total"] == 0){
				//Deleta o conte�do
				$query = "
					DELETE	FROM tb_tipo_conteudo
					WHERE	idTipoConteudo	= ".$db->clean($_REQUEST['idTipoConteudo'])."
				";
			
				$qry = $db->update($query);
				
				if($qry){
					$_SESSION['msg'] = 'Item excluído';
				}else{
					$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
				}
				
			}else{//Se j� est� associado a outros conte�dos
				$_SESSION['msgErro'] = 'Este item não pode ser excluído pois já está associado a conteúdo cadastrado.';
				
			}
			
			header('Location: '.$url_raiz.'admin/menu-tipo-conteudo');
			
		break;
		
		case "Atualizar":
			
			$nmListaCampos = "";
			$nmListaCampos = implode(',',$_POST["nomeCampo"]);
			
			$query = $db->updateQuery(array(
										'nmTipoConteudo' => trim($_POST['nmTipoConteudo']),
										'idTipoPagina' => $_POST['idTipoPagina'],
										'nmPaginaConteudo' => $_POST['nmPaginaConteudo'],
										'nmListaCampos' => $nmListaCampos
										),
										
										'tb_tipo_conteudo', 'idTipoConteudo = '.$db->clean($_POST["idTipoConteudo"]));
			//$qryCadastrar = $db->update($strCadatrar);

			//Atualiza a tabela com os dados do formul�rio
			$qry = $db->update($query);
			
			if($qry){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$url_raiz.'admin/cad-tipo-conteudo?idTipoConteudo='.$_POST["idTipoConteudo"].'&atualizado');
		break;
		
		case "Cadastrar":
		
			$nmListaCampos = "";
			$nmListaCampos = implode(',',$_POST["nomeCampo"]);
			
			$query = $db->insertQuery(array(
										'nmTipoConteudo' => trim($_POST['nmTipoConteudo']),
										'idTipoPagina' => $_POST['idTipoPagina'],
										'nmPaginaConteudo' => $_POST['nmPaginaConteudo'],
										'nmListaCampos' => $nmListaCampos
										),
										
										'tb_tipo_conteudo');
			

			//Insere os dados na tabela
			$qry = $db->update($query);
			$idInserido = mysql_insert_id();
			
			if($qry){
				$_SESSION['msg'] = 'Dados cadastrados com sucesso!';
			}else{
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$url_raiz.'admin/cad-tipo-conteudo?idTipoConteudo='.$idInserido.'&atualizado');
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