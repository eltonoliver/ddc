<?php

if (!isset($_SESSION["security_code"]) or (trim($_POST["security_code"]) != $_SESSION["security_code"])) {
    $_SESSION['msgErro'] = 'C&oacute;digo de seguran&ccedil;a incorreto!';
    echo "<script type='text/javascript'>location.href='" . $url_raiz . "contato';</script>";
} else {
    $acao = $_REQUEST["acao"] ? $_REQUEST["acao"] : "";
    switch ($acao) {
        case "Enviar":
            foreach ($_POST as $key => $value) {
                $$key = mysql_real_escape_string(strip_tags($value));
            }
            $email = trim(strtolower($email));
            //Data atual formatada
            $data = dataFormatoBanco();
            //Envia e-mail para o inscrito.
            $retorno = $url_raiz . "contato";
            $assunto = '[CONTATO - ' . $_POST["estado"] . '] ' . ($_POST["assunto"] ? '"' . $_POST["assunto"] . '"' : '');
            $mensagem = "
                        ==============================================================
                        <p align='left'><img src='" . $url_raiz . "img/" . $geralConfig[0]["nmLinkLogoTopo"] . "' title='" . strtoupper($geralConfig[0]["nmTituloSite"]) . "' alt='" . strtoupper($geralConfig[0]["nmTituloSite"]) . "'></p>
                        ============================================================== 
                        <br/><br/>
                        Um novo contato foi enviado pelo formulário de CONTATO do site " . $geralConfig[0]["nmTituloSite"] . "<br/>
                        <br/><br/>
                        <strong>DATA:</strong> " . dataTimeBarrasBR($data) . "<br/>
                        <strong>NOME:</strong> " . $nome . "<br/>
                        <strong>E-MAIL:</strong> " . $email . "<br/>
                        
                        <strong>CIDADE:</strong> " . $cidade . "<br/>
                        <strong>ESTADO:</strong> " . $estado . "<br/>
                        <strong>MENSAGEM:</strong> &nbsp;" . $_POST["mensagem"] . "
                        <br/><br/>
                        ==============================================================
                        <br/>
                        OBS.: Esta é uma mensagem automática. Não responda.
                        <br/>
                        ==============================================================
                    ";

            try {
                if (!strlen($_POST["nmEmailDestino"])) {
                    $contado = $db->query('SELECT nmContato,nmEmailContato FROM tb_contato_site WHERE inAtivo=1 AND inContatoPrincipal=1 AND inExibir=1');
                    $nmContato = $contado[0]['nmContato'];
                    $nmEmailDestino = $contado[0]['nmEmailContato'];
                } else {
                    $nmEmailDestino = $emailDestino;
                }

                //fezendo o envio da news
                $mail->From = $email; //stripcslashes($_POST["email"]['nmConteudo']);
                $mail->FromName = trim($nome);
                $mail->AddAddress($nmEmailDestino, $nmContato);
                $mail->IsHTML(true);
                $mail->CharSet = 'utf-8';
                $mail->Subject = $assunto;
                $mail->Body = stripslashes($mensagem);
                $resultado = ($mail->Send() ? 1 : 0);
                $mail->ClearAllRecipients();
                if ($resultado){
                   // echo "<script>alert('Mensagem enviada com sucesso'); location.href='" . $url_raiz . "contato';</script>";
                    $_SESSION['msgOk'] = 'Mensagem enviada com sucesso. Responderemos em breve.';
                    //$_SESSION['msgOk'] = 'Mensagem enviada com sucesso. Responderemos em breve.';
                    // echo "<script type='text/javascript'>location.href='" . $url_raiz . "contato';</script>";
                }else{
                    $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
                }
            } catch (Exception $e) {
                //new dBug($e->getMessage());
                //exit;
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
             echo "<script type='text/javascript'>location.href='" . $url_raiz . "contato';</script>";
           // header('Location: ' . $retorno);
            exit;
            break;
        default:
            echo "
                    <script type='text/javascript'>
                            alert('Você não pode acessar esta página diretamente.');
                            location.href='" . $url_raiz . "';
                    </script>
            ";
            break;
    }
}
?>