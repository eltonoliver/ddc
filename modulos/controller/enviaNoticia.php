<?php
 $acao = $_REQUEST["acao"] ? $_REQUEST["acao"] : "";
     
	  $nome = $_POST["jp_nome"];
	  $email = $_POST["jp_email"];
	  $cidade = $_POST["jp_cidade"];
	  $titulo = $_POST["jp_tituloNoticia"];
	  $txtNoticia = $_POST["jp_textoNoticia"];
	  $checked = $_POST["jp_aceito"];
	  $link = $_POST["jp_tipoDeAnexo-link"];
	  if(!empty($link)){
		  $link = "<strong>LINK DA MAT&eacute;RIA: </strong>".tratastr($link);
		  }else{
			$link = "";
		}
	  
	  if(!empty($checked)){
	   switch ($acao) {
		case "enviarEmail":
         //Envia e-mail para o inscrito.
        $assunto = 'DDC - Minha Reportagem';
        $mensagem = "
							==============================================================
							<p align='left'><img src='" . $url_raiz . "img/" . $geralConfig[0]["nmLinkLogoTopo"] . "' title='" . strtoupper($geralConfig[0]["nmTituloSite"]) . "' alt='" . strtoupper($geralConfig[0]["nmTituloSite"]) . "'></p>
							============================================================== 
							<br/><br/>
							Uma nova mat&eacute;ria foi enviada atrv&eacute;s da se&ccedil;&atilde;o  MINHA REPORTAGEM do site <br/>" . $geralConfig[0]["nmTituloSite"] . "<br/>
							<br/><br/>
							<strong>DATA:</strong> " . dataTimeBarrasBR($data) . "<br/>
							<strong>NOME:</strong> " . tratastr(utf8_decode($_POST["jp_nome"])) . "<br/>
							<strong>E-MAIL:</strong> " . tratastr(utf8_decode($_POST["jp_email"])) . "<br/>
							<strong>CIDADE:</strong> " . tratastr(utf8_decode($_POST["jp_cidade"])) . "<br/>
							<strong>TITULO:</strong> " . tratastr(utf8_decode($_POST["jp_tituloNoticia"])) . "<br/>
							<strong>TEXTO DA MAT&Eacute;RIA:</strong> " . tratastr(utf8_decode($_POST["jp_textoNoticia"])) . "<br/>
							 " . $link . "<br/>
							
							<br/><br/>
							==============================================================
							<br/>
							OBS.: Esta &eacute; uma mensagem autom&aacute;tica. N&atilde;o responda.
							<br/>
							==============================================================
				";
        $config_email = $db->query("SELECT * FROM tb_contato_site WHERE inContatoPrincipal = 1");
        try {
            $arquivoEnviado = true;
            if (strlen($_FILES['jp_tipoDeAnexo-arquivo']['tmp_name']) > 0) {
                $caminho = $raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR;
                $arquivo['name'] = strtolower($_FILES['jp_tipoDeAnexo-arquivo']['name']);
                $arquivo['type'] = strtolower($_FILES['jp_tipoDeAnexo-arquivo']['type']);
                $arquivo['tmp_name'] = $_FILES['jp_tipoDeAnexo-arquivo']['tmp_name'];
                $arquivo['error'] = $_FILES['jp_tipoDeAnexo-arquivo']['error'];
                $arquivo['size'] = $_FILES['jp_tipoDeAnexo-arquivo']['size'] / 1000;
                $extensoes = "jpg,jpeg,png,gif";
                $up = new upload();
                $up->setCaminho($caminho);
                $up->setArquivo($arquivo);
                $up->setTamanho(12428800); //at� 50MB
                $up->setExtensoes($extensoes);
                $up->setRenomear(1);
                $retornoArquivo = $up->enviarArquivo();
                if (strlen($retornoArquivo['erro'])) {
                    $_SESSION['msgErro'] = $retornoArquivo['erro'];
                    $msg = 'erro';
                    $arquivoEnviado = false;
                } else {
                    $arquivoNotPopular = $retornoArquivo['nome_arquivo'];
                }
            }
			else{
				
				/*$arquivoNotPopular = tratastr($_POST['jp_tipoDeAnexo-link']);
				 ==================================== VER SE TEM LINK ==================================== */
				
				}
            if ($arquivoEnviado) {
                //fezendo o envio da news
                $mail->From = trim(tratastr($_POST["jp_email"]));
                $mail->FromName = trim(tratastr($_POST["jp_nome"]));
                $mail->AddAddress($config_email[0]["nmEmailContato"]);
				if (strlen($_FILES['jp_tipoDeAnexo-arquivo']['tmp_name']) > 0){
                $mail->AddAttachment($caminho . $arquivoNotPopular); }
                $mail->IsHTML(true);
                $mail->CharSet = 'utf-8';
                $mail->Subject = utf8_encode($assunto);
                $mail->Body = utf8_encode(stripcslashes($mensagem));
                $resultado = ($mail->Send() ? 1 : 0);
                $mail->ClearAllRecipients();
                $msg = 'sucess';
                $_SESSION['msg'] = 'Contato enviado com sucesso. Responderemos em breve.';
            }
        } catch (Exception $e) {
            //new dBug($e->getMessage());
            //exit;
            $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            $msg = 'erro';
        }
         echo "
					  <script type='text/javascript'>
							  alert('Noticia enviada com sucesso, agradecemos sua contribuição !');
							  history.back();
					  </script>
			  ";
        
        exit;
	
		default:
			  echo "
					  <script type='text/javascript'>
							  alert('Você não pode acessar esta página diretamente.');
							  location.href='" . $url_raiz . "';
					  </script>
			  ";
			  break;
		
	}
	  } else{
		  echo "<script>alert('Não aceitou'); </script>";
		  echo "<script>history.back(); </script>";
		  
	  }

   
?>