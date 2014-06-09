<?php
//include('../header.php');
//TIPOS DE PEDIDO
//1 - Orçamento Aberto / 2 - Orçamento Enviado / 3 - Compra
//	new dBug($_SESSION);
//	new dBug(session_id());

if ($_REQUEST["acao"]) {
    $acao = $_REQUEST["acao"];
} else {
    $acao = "";
}

switch ($acao) {
     case "Adicionar":

        //1. Verifica se existe pedido em aberto para esta sessão
        $query = "SELECT idPedido FROM tb_pedido WHERE idTipoPedido = 1 AND idSessao = '" . session_id() . "' ORDER BY idPedido DESC LIMIT 1";
        $qryVerifica = $db->query($query);

        $data = dataFormatoBanco(); //$data = converteData(date("d/m/Y")); 

        if ($qryVerifica[0]["idPedido"] <= 0) { //1.1 Se não achou nenhum pedido em aberto para esta sessão, cria um pedido.
            
            $qry = "
			SELECT 	max(nrPedido) as nrPedido
			FROM	tb_pedido
			WHERE 	year(dtDataPedido) = '".date("Y")."'
				
			";
			$qryNrPedido = $db->query($qry);
				
			$nrPedido = 0;
			if($qryNrPedido[0]["nrPedido"] > 0){
				$nrPedido = $qryNrPedido[0]["nrPedido"] + 1;

			} else {
				$nrPedido = 1;
			}
				
			$GLOBALS["ID_SESSAO"] =  '';

            //SQL
            $query = "
						INSERT INTO tb_pedido(dtDataPedido,
												idTipoPedido,
												idSessao,
												ipUsuario,
												nrPedido,
												nmBrowser)
												
						VALUES					('" . $data . "',
												 '1',
												 '" . session_id() . "',
												 '" . $_SERVER["REMOTE_ADDR"] . "',
												 '" . $nrPedido . "',
												 '" . $_SERVER["HTTP_USER_AGENT"] . "')
					";

            //Executa SQL
            $db->update($query);

            //Guarda o ID da sessão que iniciou o processo em uma variável global
            $GLOBALS["ID_SESSAO"] = session_id();


            //ID inserido
            $idPedido = mysql_insert_id();
        } else {//Se achou um pedido em aberto, recebe o ID que já existe
            $idPedido = $qryVerifica[0]["idPedido"];
        }

        //2. Busca os dados do produto para inserir na tb_pedido_produto
        $query = "	SELECT idConteudo as idProduto,
									valor,  
									nrPeso, 
									nmUnidade, 
									nrValorFrete 
									
							FROM 	tb_conteudo 
							WHERE	idTipoConteudo = 31
							AND		inPublicar = 1
							AND		idConteudo = '" . $_REQUEST["id"] . "'";

        $qryProduto = $db->query($query);

        //2.1. Insere o produto na tb_pedido_produto
        if ($qryProduto) { //Se o produto foi localizado, faz a inserção
            if ($_REQUEST["nrQuantidade"]) {//Se a quantidade veio por parâmetro, atribui
                $quantidade = $_REQUEST["nrQuantidade"];
            } else {
                $quantidade = 1; //Se a quantidade não veio, atribui 1 por padrão
            }


            //2.1.1 Verifica se o produto já está no pedido do cliente
            //SQL
            $query = "
						
							SELECT 	idProduto
							FROM 	tb_pedido_produto 
							WHERE	idPedido = '" . $idPedido . "'
							AND	idProduto = '" . $_REQUEST["id"] . "'
							
							";

            //Executa SQL
            $qryVerificaProduto = $db->query($query);

            if ($qryVerificaProduto) {//Se achou este produto no pedido do cliente, retorna uma mensagem
                echo "
								<script type='text/javascript'>
									alert('Produto ja inserido. Atualize as quantidades, se necessario.');
									location.href='" . $url_raiz . "comprar';
								</script>
							";
            } else {//Se não achou o produto, adiciona, e retorna par ao orçamento.
                //SQL
                $query = "
									INSERT INTO tb_pedido_produto	(idPedido,
																	idProduto,
																	valor,
																	nrQuantidade,
																	nrPeso,
																	nmUnidade,
																	nrValorFrete)
															
									VALUES							('" . $idPedido . "',
																	 '" . $qryProduto[0]["idProduto"] . "',
																	 '" . $qryProduto[0]["valor"] . "',
																	 '" . $quantidade . "',
																	 '" . $qryProduto[0]["nrPeso"] . "',
																	 '" . $qryProduto[0]["nmUnidade"] . "',
																	 '" . $qryProduto[0]["nrValorFrete"] . "')
								";

                //Executa SQL
                $db->update($query);

                //Retorno
                echo "
									<script type='text/javascript'>
										location.href='" . $url_raiz . "comprar';
									</script>
								";
            }
        } else {//Se o produto não foi localizado, retorna um erro par ao usuário
            echo "
								<script type='text/javascript'>
									alert('Produto indisponivel no momento.');
									location.href='" . $url_raiz . "store';
								</script>
							";
        }

        break;

    case "Excluir":

        //1. Excluir o item do pedido
        //SQL
        $query = "
		DELETE	FROM tb_pedido_produto
		WHERE	idProduto		= " . $_REQUEST["id"] . "
		AND		idPedido		= " . $_REQUEST["pedido"] . "
		";

        $db->update($query);

        //Retorno
        if ($_REQUEST["retorno"] == 'admin') {
            $caminho = '../admin/cadPedido.php?idPedido=' . $_REQUEST["pedido"];
        } else {
            $caminho = ''.$url_raiz.'comprar';
        }

        echo "
		<script type='text/javascript'>
		location.href='" . $caminho . "';
		</script>
		";

        break;

    case "AdicionarAdmin":

        //1. Busca os dados do produto para inserir na tb_pedido_produto
        $query = "	SELECT idConteudo as idProduto,
		valor,
		nrPeso,
		nmUnidade,
		nrValorFrete
			
		FROM 	tb_conteudo
		WHERE	idTipoConteudo = 31
		AND		inPublicar = 1
		AND		idConteudo = '" . $_REQUEST["idProduto"] . "'";

        $qryProduto = $db->query($query);


        //2.Verifica se o produto já está no pedido do cliente
        //SQL
        $query = "

		SELECT 	idProduto
		FROM 	tb_pedido_produto
		WHERE	idPedido = '" . $_REQUEST["idPedido"] . "'
		AND		idProduto = '" . $_REQUEST["idProduto"] . "'
			
		";

        //Executa SQL
        $qryVerificaProduto = $db->query($query);

        if ($qryVerificaProduto) {//Se achou este produto no pedido do cliente, retorna uma mensagem
            $caminho = '../admin/cadPedido.php?idPedido=' . $_REQUEST["idPedido"];

            echo "
			<script type='text/javascript'>
			alert('Produto ja inserido. Atualize as quantidades, se necessario.');
			location.href='" . $caminho . "';
			</script>
			";
        } else {//Se não achou o produto, adiciona, e retorna para ao orçamento.
            //Adiciona o item ao pedido
            //SQL
            $query = "
			INSERT INTO tb_pedido_produto	(idPedido,
			idProduto,
			valor,
			nrQuantidade,
			nrPeso,
			nmUnidade,
			nrValorFrete)
				
			VALUES							('" . $_REQUEST["idPedido"] . "',
			'" . $qryProduto[0]["idProduto"] . "',
			'" . $qryProduto[0]["valor"] . "',
			'1',
			'" . $qryProduto[0]["nrPeso"] . "',
			'" . $qryProduto[0]["nmUnidade"] . "',
			'" . $qryProduto[0]["nrValorFrete"] . "')
			";

            //Executa SQL
            $db->update($query);

            $caminho = '../admin/cadPedido.php?idPedido=' . $_REQUEST["idPedido"];

            //Retorno
            echo "
			<script type='text/javascript'>
			location.href='" . $caminho . "';
			</script>
			";
        }

        break;


    case "Atualizar":

        //1. Excluir o item do pedido
        //SQL
        if (isset($_REQUEST["valor"])) {
            $filtro = ",valor = '" . valorMoedaIngles($_REQUEST["valor"]) . "'";
        } else {
            $filtro = "";
        }


        $query = "
		UPDATE	tb_pedido_produto
		SET		nrQuantidade 	= '" . $_REQUEST["nrQuantidade"] . "'
		" . $filtro . "
		WHERE	idProduto		= " . $_REQUEST["id"] . "
		AND		idPedido		= " . $_REQUEST["pedido"] . "
		";

        //new dBUg($query);
        //exit;

        $db->update($query);

        //Retorno
        if ($_REQUEST["retorno"] == 'admin') {
            $caminho = '../admin/cadPedido.php?idPedido=' . $_REQUEST["pedido"];
        } else {
            $caminho = ''.$url_raiz.'comprar';
        }

        echo "
            
		<script type='text/javascript'>
		location.href='" . $caminho . "';
		</script>
		";

        break;

case "concluirPS":

    /*   INICIO  */
    //Data atual formatada, para utilizar no log das operaÃ§Ãµes
                     $data = dataFormatoBanco(); //$data = converteData(date("d/m/Y")); 

                     //1.Gravar os dados do cliente
                             //1.1 Verifica pelo e-mail se já foi cadastrado.
                             $query = " SELECT idCliente FROM tb_cliente WHERE nmEmailCliente = '".$_REQUEST["cliente_email"]."' ";
                             $qryVerificaCliente = $db->query($query);

                             if($qryVerificaCliente){ //Se o cliente jÃ¡ estÃ¡ cadastrado, atualiza

                                     $idCliente = $qryVerificaCliente[0]["idCliente"];

                                     //SQL
                                     $query = "
                                             UPDATE	tb_cliente
                                             SET		nmCliente 		= '".trim($_POST["cliente_nome"])."',
                                                             nmEmailCliente 		= '".trim($_POST["cliente_email"])."',
                                                             nmTelefoneCliente 		= '".$_POST["cliente_telefone"]."',
                                                             nmCidadeCliente 		= '".trim($_POST["cliente_cidade"])."',
                                                             nmUfCliente 		= '".$_POST["cliente_uf"]."',
                                                             nmEnderecoCliente 		= '".trim($_POST["cliente_end"])."',
                                                             nrNumeroCliente 		= '".trim($_POST["cliente_num"])."',
                                                             nmComplementoCliente 	= '".trim($_POST["cliente_compl"])."',
                                                             nmBairroCliente 		= '".$_POST["cliente_bairro"]."',
                                                             nrCepCliente 		= '".$_POST["cliente_cep"]."',
                                                             dtDataCadastro 		= '".$data."'

                                             WHERE	idCliente		= '".$idCliente."'";

                                     $db->update($query);

                             } else { //Se o cliente ainda nÃ£o estÃ¡ cadastrado, insere os dados

                                                                     //SQL
                                     $query = "
                                             INSERT INTO tb_cliente (nmCliente,
                                                                                nmEmailCliente,
                                                                                nmTelefoneCliente,
                                                                                nmCidadeCliente,
                                                                                nmUfCliente,
                                                                                nmEnderecoCliente,
                                                                                nrNumeroCliente,
                                                                                nmComplementoCliente,
                                                                                nmBairroCliente,
                                                                                nrCepCliente,
                                                                                dtDataCadastro)

                                        VALUES					('".trim($_POST["cliente_nome"])."',
                                                                                        '".trim($_POST["cliente_email"])."',
                                                                                        '".$_POST["cliente_telefone"]."',
                                                                                        '".trim($_POST["cliente_cidade"])."',
                                                                                        '".$_POST["cliente_uf"]."',
                                                                                        '".trim($_POST["cliente_end"])."',
                                                                                        '".trim($_POST["cliente_num"])."',
                                                                                        '".trim($_POST["cliente_compl"])."',
                                                                                        '".$_POST["cliente_bairro"]."',
                                                                                        '".$_POST["cliente_cep"]."',
                                                                                        '".$data."')";

                                     $db->update($query);
                                     //$idCliente = mysql_insert_id();

                             }


    /*   FIM    */

                     //1. Busca o número do pedido para informar ao cliente
                     $query = "SELECT nrPedido, year(dtDataPedido) as nrAnoPedido, idPedido FROM tb_pedido WHERE idSessao ='". session_id() ."'
                             AND idTipoPedido = 1";

                     $qryPedido = $db->query($query);
                     $pedido = $qryPedido[0]["nrPedido"].'/'.$qryPedido[0]["nrAnoPedido"].'-'.$qryPedido[0]["idPedido"]; 
                     $data = dataFormatoBanco(); //$data = converteData(date("d/m/Y")); 

                     //2. Atualiza a tabela	
                             //SQL
                             $query = "UPDATE tb_pedido SET idPagSeguro = '".$_GET["id_pagseguro"]."', idTipoPedido	= '3', dtPagSeguro = '".$data."'
                                     WHERE	idPedido = '".$qryPedido[0]["idPedido"]."' ";

                             $db->update($query);
                             
                             $query = "SELECT B.idPedido 
                                FROM tb_pedido_produto AS A
                                INNER JOIN tb_pedido AS B ON ( B.idPedido = A.idPedido ) 
                                WHERE B.idSessao = '". session_id() ."'";
                               $qryPed = $db->query($query);
                             
                             /* Aqui vai o envio de e-mail*/
                               
                               
                        //Envia e-mail para o inscrito.
                        $assunto = 'DDC - Compra do PagSeguro Solicitada';
                        $mensagem = "
                                   ==============================================================
                                   <p align='left'><img src='" . $url_raiz . "img/" . $geralConfig[0]["nmLinkLogoTopo"] . "' title='" . strtoupper($geralConfig[0]["nmTituloSite"]) . "' alt='" . strtoupper($geralConfig[0]["nmTituloSite"]) . "'></p>
                                   ============================================================== 
                                   <br/><br/>
                                   Um pedido de compra foi encaminhado ao: " . $geralConfig[0]["nmTituloSite"] . "<br/>
                                   <br/>
                                   <strong>DATA:</strong> " . dataTimeBarrasBR($data) . "<br/>
                                   <strong>NOME:</strong> " . tratastr(utf8_decode($_POST["cliente_nome"])) . "<br/>
                                   <strong>E-MAIL:</strong> " . tratastr(utf8_decode($_POST["cliente_email"])) . "<br/>
                                   <strong>CIDADE:</strong> " . tratastr(utf8_decode($_POST["cliente_cidade"])) . "<br/>
                                   <strong>MENSAGEM:</strong> " . tratastr(utf8_decode($_POST["mensagem"])) . "<br/>
                                   <br/>
                                   
                                   Favor, verificar o painel de controle do site para mais detalhes. <br><br>
                                   ==============================================================
                                   <br/>
                                   OBS.: Esta &eacute; uma mensagem autom&aacute;tica. N&atilde;o responda.
                                   <br/>
                                   ==============================================================
                                               ";
                        $config_email = $db->query("SELECT * FROM tb_contato_site WHERE inContatoPrincipal = 1");
                        try {
                           $arquivoEnviado = true;
                               //fezendo o envio da news
                               $mail->From = trim(tratastr($_REQUEST["cliente_email"]));
                               $mail->FromName = trim(tratastr($_REQUEST["cliente_nome"]));
                               $mail->AddAddress($config_email[0]["nmEmailContato"]);

                               $mail->IsHTML(true);
                               $mail->CharSet = 'utf-8';
                               $mail->Subject = utf8_encode($assunto);
                               $mail->Body = utf8_encode(stripcslashes($mensagem));
                               $resultado = ($mail->Send() ? 1 : 0);
                               $mail->ClearAllRecipients();
                               $msg = 'sucess';
                               
                        } catch (Exception $e) {
                           
                        }

                        /*FIm do ENVIO de EMAIL*/   

                     //5. Retornar
                     $caminho = ''.$url_raiz.'comprar';
                   echo "

                     <script type='text/javascript'>
                     location.href='" . $caminho . "';
                     </script>
                  ";

             break;
		
		
		
		case "Finalizar":
		
			//Data atual formatada, para utilizar no log das operaÃ§Ãµes
			$data = dataFormatoBanco(); //$data = converteData(date("d/m/Y")); 
			
			//1.Atualizar o status do pedido na tabela
			
				//1.1 Busca o ID do pedido pelo ID 
				$query = "SELECT idPedido, year(dtDataPedido) as nrAnoPedido, nrPedido, dtDataPedido, idCliente FROM tb_pedido WHERE idPedido = '".$_REQUEST["idPedido"]."' ORDER BY idPedido DESC LIMIT 1";
				$qryPedido = $db->query($query);
				$pedido = $qryPedido[0]["nrPedido"].'/'.$qryPedido[0]["nrAnoPedido"].'-'.$qryPedido[0]["idPedido"];
				
				$query = "SELECT * FROM tb_cliente WHERE idCliente = '".$qryPedido[0]["idCliente"]."' ORDER BY idCliente DESC LIMIT 1";
				$qryCliente = $db->query($query);
				
				if(isset($_REQUEST["nmObservacoesPedido"])){
					$filtro = ",nmObservacoesPedido = '".$_REQUEST["nmObservacoesPedido"]."'";
					
				} else {
					$filtro = "";
				}
				
				//SQL
				$query = "
					UPDATE	tb_pedido
					SET		idTipoPedido			= 4
							".$filtro."	
					WHERE	idPedido				= ".$qryPedido[0]["idPedido"]."
				";
				
				$db->update($query);

			
			//3. Busca os dados do pedido para informar ao cliente
				$qry = "
						SELECT 		A.*,
									B.*,
									C.nmTituloConteudo AS nmProduto,
									year(A.dtDataPedido) as nrAnoPedido
								
						FROM 		tb_pedido A
						LEFT JOIN	tb_pedido_produto B ON (A.idPedido = B.idPedido)
						LEFT JOIN	tb_conteudo C ON (B.idProduto = C.idConteudo)
						
						
						WHERE 		A.idPedido = '".$qryPedido[0]["idPedido"]."'	
				";
				$qryPedidoCliente = $db->query($qry);
				
			//4. Enviar e-mail para o dono do site e para o cliente
			
				# -=-=-=- MAIL HEADERS
				
				$to = $qryCliente[0]["nmEmailCliente"];
				$subject = 'RES: ['.$geralConfig[0]["nmTituloSite"].'] PEDIDO DE ORÃAMENTO';
				
				// Additional headers
				$headers  = "MIME-Version: 1.0\n"; 
				$headers .= "Content-type: text/html; charset=iso-8859-1\n";				
				$headers .= 'From: '.$geralConfig[0]["nmTituloSite"].' <'.$qryRodape[0]["nmEmailPrincipal"].'>' . "\r\n";
				$headers .= 'Cc: '.$qryRodape[0]["nmEmailPrincipal"].'' . "\r\n";
				
				$data = date("Y/m/d", time());
				$hora = date("H:i:s");
				
				# -=-=-=- TEXT EMAIL PART

	for($i=0; $i<count($qryPedidoCliente); $i++){
		$total = ($qryPedidoCliente[$i]["valor"] * $qryPedidoCliente[$i]["nrQuantidade"]); 
		$totalFinal = $totalFinal + $total; 
		
        $produtos = $produtos. '<tr>
          <td align="center">'.$qryPedidoCliente[$i]['nmProduto'].'</td>
          <td align="center">'.$qryPedidoCliente[$i]['nrQuantidade'].' ('.$qryPedidoCliente[$i]['nmUnidade'].')</td>
          <td align="center">'.valorMoedaBR($qryPedidoCliente[$i]["valor"]).'</td>
          <td align="center">'.valorMoedaBR($total).'</td>
        </tr>';
	}


$message = '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><table border="0" cellspacing="0" cellpadding="0" width="550">
  <tr>
    <td><p align="center"><img src="'.$url_raiz.'img/'.$geralConfig[0]["nmLinkLogoTopo"].'"></p>
      <div align="center">
        <hr size="3" width="100%" noshade="noshade" align="center" />
      </div></td>
  </tr>
  <tr>
    <td><p>VocÃª estÃ¡ recebendo este e-mail em respostao a um Pedido de OrÃ§amento foi feito em nome de '.$qryCliente[0]["nmCliente"].', em '.date('d/m/Y - H:i:s', strtotime($qryPedido[0]["dtDataPedido"])).', no site <a href="'.$url_raiz.'">'.$geralConfig[0]["nmTituloSite"].'</a> ('.$url_raiz.').</p>
	<div align="center">
      <hr size="2" width="100%" noshade="noshade" align="center" />
    </div>
	</td>
  </tr>
  <tr>
    <td><p><br />
      <strong>CÃ³digo do Pedido:</strong> '.$pedido.'<br />
      <strong>Tipo / SituaÃ§Ã£o:</strong> OrÃ§amento Finalizado<br />
      <strong>OrÃ§amento Elaborado por:</strong> '.$_SESSION["NOME"].'<br /><br />
      <strong>Cliente:</strong> '.$qryCliente[0]["nmCliente"].'<br />
      <strong>E-mail:</strong> '.$qryCliente[0]["nmEmailCliente"].'<br />
      <strong>Telefone:</strong> '.$qryCliente[0]["nmTelefoneCliente"].'<br />
      <strong>Cidade:</strong> '.$qryCliente[0]["nmCidadeCliente"].'<br />
      <strong>UF:</strong> '.$qryCliente[0]["nmUfCliente"].'<br />
      <strong>Data Resposta:</strong> '.$data.'<br />
      <strong>Hora Resposta:</strong> '.$hora.'<br />
      <strong>Comentarios:</strong><br />'.$_POST["nmObservacoesPedido"].'<br /><br />
  </tr>
  <tr>
    <td><p>&nbsp;</p>
	<div align="center">
	  <hr size="2" width="100%" noshade="noshade" align="center" />
	</div>
      <table border="0" cellspacing="0" cellpadding="0" rules="groups" width="100%">
        <tr>
          <td><p align="center"><strong>ITENS DO PEDIDO</strong><strong> </strong></p></td>
          <td><p align="center"><strong>QTD. / UN.</strong><strong> </strong></p></td>
          <td><p align="center"><strong>VALOR UN.</strong><strong> </strong></p></td>
          <td><p align="center"><strong>TOTAL&nbsp;(R$)</strong><strong> </strong></p></td>
        </tr>
		'.$produtos.'
        <tr>
          <td colspan="4">
			<div align="center">
			  <hr size="2" width="100%" noshade="noshade" align="center" />
			</div>
		  <p>&nbsp;</p></td>
        </tr>
        <tr>
          <td><p>&nbsp;</p></td>
          <td>&nbsp;</td>
          <td align="center"><strong>Total geral</strong></td>
          <td align="center"><strong>R$'.valorMoedaBR($totalFinal).'</strong></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td><p>Atenciosamente,<br />
      Equipe <a href="mailto:'.$qryRodape[0]["nmEmailPrincipal"].'">'.$geralConfig[0]["nmTituloSite"].'</a>.<br />
      <br />
      </p></td>
  </tr>
  <tr>
    <td><div align="center">
      <hr size="2" width="100%" noshade="noshade" align="center" />
    </div>
      <p align="center">DÃVIDAS? Acesse <a href="'.$url_raiz.'?pg=contato.php">'.$url_raiz.'?pg=contato.php</a></p></td>
  </tr>
  <tr>
    <td>
	<div align="center">
      <hr size="2" width="100%" noshade="noshade" align="center" />
    </div>
      <p align="center">Este Ã© um e-mail automÃ¡tico disparado pelo sistema. Favor    nÃ£o respondÃª-lo, pois esta conta nÃ£o Ã© monitorada. </p></td>
  </tr>
  <tr>
    <td background="https://p.simg.uol.com.br/out/pagseguro/i/email/deg_rodapemail.gif"><p>&nbsp;</p></td>
  </tr>
</table>';
				
				$mail_sent = mail($to, utf8_decode($subject), utf8_decode($message), $headers );
				
			//Retorno
			$caminho = '../admin/cadPedido.php?idPedido='.$qryPedido[0]["idPedido"];
			
			echo "
				<script type='text/javascript'>
					location.href='".$caminho."';
				</script>
			";
				
			exit;
		
		break;
		
		default:
			$caminhocr = ''.$url_raiz.'comprar';
		      echo "

                        <script type='text/javascript'>
                        location.href='" . $caminhocr . "';
                        </script>
		     ";
		break;	
	}

?>