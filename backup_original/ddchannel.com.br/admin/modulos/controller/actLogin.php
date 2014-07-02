<?php 
$acao = strlen($_REQUEST["acao"])?$_REQUEST["acao"]:"";
switch ($acao){
	
	case "esqueciSenha":
		$email = trim($_REQUEST['email']);
		if(!preg_match("/^([[:alnum:]_.-]){3,}@([[:lower:][:digit:]_.-]{3,})(\.[[:lower:]]{2,3})(\.[[:lower:]]{2})?$/", $email)){
			$_SESSION['msgErro'] = 'Digite um e-mail válido.';
		}else{
			$resposta = $db->query('SELECT COUNT(*) as total , idUsuario FROM tb_usuario WHERE nmEmailUsuario = ' . $db->clean($email) . '');
			if(!$resposta[0]['total']){
				$_SESSION['msgErro'] = 'O e-mail digitado não consta em nosso sistema.';
			}else{
				$idUsuario = $resposta[0]['idUsuario'];
				$resposta = $db->update(
					'INSERT INTO tb_esqueceu_senha (hash, dataCadastro, idUsuario) VALUES('.
					'"'.$hash = md5(uniqid()).'", '.
					'"'.date('Y-m-d H:i:s').'", '.
					$idUsuario.')'
				);
				
				if(!$resposta){
					$_SESSION['resposta'] = 1;
				}else{
					
					$mensagem = '
					===============================================<br/>
					<br/>'.$geralConfig[0]["nmTituloSite"].'<br/><br/>
					SOLICITAÇ&Atilde;O DE RECUPERAÇ&Atilde;O DE SENHA <br/>
					<br/>
					===============================================<br/>
					<p> Voc&ecirc; solicitou a recuperaç&atilde;o de sua senha, clique <a href="'.$url_raiz.'admin/login-alterar-senha?hash='.$hash.'">aqui</a>.<br/>
					Caso n&atilde;o tenha feito essa solicitaç&atilde;o desconsidere este e-mail.
					';
					
					$mail->From = $qryRodape[0]["nmEmailPrincipal"];
					$mail->FromName = $geralConfig[0]["nmTituloSite"];
					$mail->AddAddress($email);
					$mail->IsHTML(true);
					$mail->CharSet = 'iso-8859-1';
					$mail->Subject  = '['.$geralConfig[0]["nmTituloSite"].'] ESQUECEU A SENHA';
					$mail->Body = $mensagem;
					$_SESSION['resposta'] = $resultado = ($mail ->Send()?2:1);
				}
			}
		}
		
		header("Location: ".$url_raiz."admin/login-esqueceu-senha");exit;
	
	break;
	
	case "alterarSenha":
	
		$hash = $_REQUEST['hash'];
		$senha = trim($_REQUEST['senha']);
		$senhaConfirmacao = trim($_REQUEST['senhaConfirmacao']);
		$_SESSION['hash'] = $hash;
		
		if(!strlen($senha) || !strlen($senhaConfirmacao)){
			$_SESSION['msgErro'] = 'Digite a senha e a confirmaç&atilde;o da senha';
		}elseif($senha != $senhaConfirmacao){
			$_SESSION['msgErro'] = 'As informaç&otilde;es digitadas s&atilde;o diferentes, favor digite novamente.';
		}elseif(!strlen($hash)){
			$_SESSION['msgErro'] = 'Hash inválido, favor tente novamente usando o link que voc&ecirc; recebeu pelo e-mail.';
		}else{
			$hashDados = $db->query('SELECT * FROM tb_esqueceu_senha WHERE hash = '.$db->clean($hash).'');
			if(!$hashDados){
				$_SESSION['msgErro'] = 'Hash inválido, favor tente novamente usando o link que voc&ecirc; recebeu pelo e-mail.';
			}else{
				$timediff = (int)(strtotime(date('Y-m-d H:i:s')) - strtotime($hashDados[0]['dataCadastro']));
				$duasHs = 2*60*60;
				if($timediff > $duasHs){
					$_SESSION['msgErro'] = 'Sua solicitaç&atilde; expirou, favor faça uma outra solicitaç&atilde;';
				}else{
					$db->update('UPDATE tb_usuario SET inAtivo = 1, nmSenha = '.$db->clean(md5(trim(strtolower($senha.'-uga')))).' WHERE idUsuario = '.$hashDados[0]['idUsuario']);
					$db->update('DELETE FROM tb_esqueceu_senha WHERE hash = '.$db->clean($hash).'');
					$_SESSION['resposta'] = 1;
				}
			}
		}
		
		header("Location: ".$url_raiz."admin/login-alterar-senha");exit;
	break;
	
	case 'logando':
		if($auth->logar($_POST['login'], $_POST['senha'], trim($_POST["security_code"]))){
			header("Location: ".$url_raiz."admin/admin");exit;
		}else{
			$msg = null;
			switch($auth->obterCodigoErro()){
				case auth::CREDENCIAIS_INVALIDA:
					$msg = 'Erro: Dados incorretos.';
				break;
				case auth::CODIGO_SEGURACA_INVALIDO:
					$msg = 'Erro: Código de seguranca está incorreto.';
				break;
				case auth::DADO_INVALIDO:
					$msg = 'Erro: Dados incorretos.';
				break;
				default:
					$msg = 'Erro ao tentar logar.';
				break;
			}
			
			if($auth->atingiuTentativas()){
				$_SESSION['msgErro'] =  'Voce atingiu o número de tentativas máxima,'.'<br/>'.
										' seu usuário foi bloqueao e foi enviado um e-mail para cadastrar uma nova senha.';
				$user = $db->query('SELECT * FROM tb_usuario WHERE nmLogin =' . $db->clean($_POST['login']) . '');
				if($user){
					$user = current($user);
					//---------------------------------------------
					//Cadastrando o hash
					$hash = md5(uniqid());
					
					$db->update($db->insertQuery(array(
						'hash' 			=> $hash,
						'dataCadastro' 	=> date('Y-m-d H:i:s'),
						'idUsuario' 	=> $user['idUsuario']
					), 'tb_esqueceu_senha'));
					
					//Desativando o usuário
					$db->update($db->updateQuery(array(
						'inAtivo' => 0
					), 'tb_usuario', 'idUsuario = ' . $user['idUsuario']));
					
					//---------------------------------------------
					//Enviando o e-mail
					$mensagemEmail = '';
					$mensagemEmail .= '<p align="center">==============================================================<br/>';
					$mensagemEmail .= 'AVISO DE TROCA DE SENHA<br/>';
					$mensagemEmail .= '==============================================================<br/><br/>';
					$mensagemEmail .= 'Atenç&atilde;o!<br/>Seu usuário foi bloqueda, pois exedeu o número de tentativas de login.<br/><br/>';
					$mensagemEmail .= 'Clique <a href="'.$url_raiz.'admin/login-alterar-senha?hash='.
										$hash.'">aqui</a>, para efetuar o cadastro de uma nova senha.</p>';
					
					$mail->From = $qryRodape[0]["nmEmailPrincipal"]; // Seu e-mail
					$mail->FromName = $geralConfig[0]["nmTituloSite"]; // Seu nome
					$mail->AddAddress($user['nmEmailUsuario'], $user['nmUsuario']);
					$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
					$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
					$mail->Subject  = 'AVISO DE TROCA DE SENHA'; // Assunto da mensagem
					$mail->Body = $mensagemEmail;
					$resultado = ($mail->Send()?1:0);
				}
			}else{
				$_SESSION['msgErro'] = $msg;
			}
			
			header("Location: ".$url_raiz."admin/login");exit;
		}
	break;
	
	case 'deslogando':
		$auth->deslogar();
		header("Location: ".$url_raiz."admin/login");exit;
	break;
	
	
	
	default:
		header("Location: ".$url_raiz."admin/acesso-negado");exit;
	break;	
}
?>