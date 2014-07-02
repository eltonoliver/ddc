<?php

$retorno = $_POST["retorno"];
if (!isset($_SESSION["security_code"]) or (trim($_POST["security_code"]) != $_SESSION["security_code"])) {
    // What happens when the CAPTCHA was entered incorrectly
    $_SESSION['msgErro'] = "CAPTCHA incorreto. Tente novamente.";
    echo "<script type='text/javascript'>location.href='" . $retorno . "#form-comment';</script>";
} else {
    $acao = $_REQUEST["acao"] ? $_REQUEST["acao"] : "";
    switch ($acao) {
        case "Cadastrar":
            //Data atual formatada
            $data = dataFormatoBanco();
            //1.Gravar os dados do cliente
            //SQL
            $query = $db->insertQuery(array(
                'nmNome' => trim($_POST["nmNome"]),
                'nmEmail' => trim($_POST["nmEmail"]),
                'nmComentario' => $_POST["nmComentario"],
                'inPublicar' => 0,
                'idComentarioPai' => $_POST["idComentarioPai"],
                'inExibirContato' => $_POST["inExibirContato"],
                'idConteudo' => $_POST["idConteudo"],
                'dtDataCadastro' => $data,
                'idBairro' => $_REQUEST['idBairro'],
                'nmLocal' => $_REQUEST['nmLocal'],
                'inTipo' => $_REQUEST['inTipo'] ? $_REQUEST['inTipo'] : 1
                    ), 'tb_comentarios');
            $qry = $db->update($query);
            $idInserido = mysql_insert_id();
            $idPai = $_POST["idComentarioPai"];
            if ($_POST["idComentarioPai"] == 0) {
                $db->update("UPDATE tb_comentarios SET idRel = " . $idInserido . " WHERE idComentario = " . $idInserido);
            } else {
                $db->update("UPDATE tb_comentarios SET idRel = " . $db->clean($idPai) . " WHERE idComentario = " . $idInserido);
            }
            if ($_POST["inExibirContato"] == 0) {
                $exibir = "N&atilde;o";
            } else {
                $exibir = "Sim";
            }
            //Envia e-mail para o inscrito.
            $tipoComentario = "COMENTÁRIO";
            $assunto = '[' . $tipoComentario . '] "' . $_POST["nmTituloConteudo"] . '"';
            $mensagem = "
							==============================================================
							<p align='left'><img src='" . $url_raiz . "img/" . $geralConfig[0]["nmLinkLogoTopo"] . "' title='" . strtoupper($geralConfig[0]["nmTituloSite"]) . "' alt='" . strtoupper($geralConfig[0]["nmTituloSite"]) . "'></p>
							============================================================== 
							<br/><br/>
							Uma nova mensagem foi enviada por " . $_POST["nmNome"] . ", para publica&ccedil;&atilde;o na se&ccedil;&atilde;o de " . $tipoComentario . " do site <br/>" . $geralConfig[0]["nmTituloSite"] . "<br/>
							<br/><br/>
							Esta mensagem ser&aacute; submetida ao site assim que for aprovada pelo administrador.<br/><br/>
							<strong>DATA:</strong> " . dataTimeBarrasBR($data) . "<br/>
							<strong>NOME:</strong> " . $_POST["nmNome"] . "<br/>
							<strong>E-MAIL:</strong> " . $_POST["nmEmail"] . "<br/>
							" . ($_POST["nmLocal"] ? ("<strong>E-MAIL:</strong> " . $_POST["nmLocal"] . "<br/>") : '') . "
							<strong>EXIBIR E-MAIL:</strong> " . $exibir . "<br/><br/>
							<strong>MENSAGEM:</strong> <br/>" . $_POST["nmComentario"] . "
							<br/><br/>
							==============================================================
							<br/>
							OBS.: Esta é uma mensagem autom&aacute;tica. N&atilde;o responda.
							<br/>
							==============================================================
				";

            $config_email = $db->query("SELECT * FROM tb_contato_site WHERE inContatoPrincipal = 1");
            try {
                //fezendo o envio da news
                $mail->From = trim($_POST["nmEmail"]); //stripcslashes($_POST["email"]['nmConteudo']);
                $mail->FromName = trim($_POST["nmNome"]);
                $mail->AddAddress($config_email[0]["nmEmailContato"]);
                $mail->IsHTML(true);
                $mail->CharSet = 'utf-8';
                $mail->Subject = $assunto;
                $mail->Body = stripcslashes($mensagem);
                $resultado = ($mail->Send() ? 1 : 0);
                $mail->ClearAllRecipients();
            } catch (Exception $e) {
                //new dBug($e->getMessage());
                //exit;
            }
            //5. Retornar
            if ($qry) {
                $_SESSION['msg'] = 'Seu comentário foi enviado e estará disponível em breve.';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
            //header('Location: ' . $retorno.'#form-comment');
            echo"<script> location.href='" . $retorno . "#form-comment'; </script>";
            exit;
            break;
        default:
            echo "
				<script type='text/javascript'>
					alert('Voce não pode acessar esta página diretamente.');
					location.href='" . $url_raiz . "';
				</script>
			";
            break;
    }
}
?>