<?php

if ($_REQUEST["acao"]) {
    $acao = $_REQUEST["acao"];
} else {
    $acao = "";
}
switch ($acao) {
    case "Excluir":
        //1.Verifica se o cliente j� est� associado a pedidos
        $query = "
							SELECT * 
							FROM tb_pedido 
							WHERE idCliente = " . $_REQUEST["idCliente"] . "
			";
        $qryVerificaPedido = $db->query($query);
        if ($qryVerificaPedido) { //Se o cliente est� vinculado a pedidos
            //1.1 O cliente n�o pode ser exclu�do porque j� exite
            echo "
						<script type='text/javascript'>
							alert('Cliente não pode ser excluído porque já está associado a Pedidos.');
							location.href='" . $url_raiz . "admin/menu-clientes?erro';
						</script>
					";
        } else {//Se o cliente n�o est� associado a pedidos
            //1.2 Realiza a exclus�o do cliente
            //SQL
            $query = "
						DELETE	FROM tb_cliente
						WHERE	idCliente	= " . $_REQUEST["idCliente"] . "
					";

            //Executar SQL
            $db->update($query);

            //RETORNO
            echo "
						<script type='text/javascript'>
							alert('Item excluído.');
							location.href='" . $url_raiz . "admin/menu-clientes?atualizado';
						</script>
					";
        }

        break;

    case "Atualizar":

        //Data atual formatada, para utilizar no log das opera��es
        $data = dataFormatoBanco(); //$data = converteData(date("d/m/Y")); 
        //1.Atualiza os dados
        //SQL
        $query = "
				UPDATE	tb_cliente
				SET		nmCliente 				= '" . trim($_POST["nmCliente"]) . "',
						nmEmailCliente 			= '" . trim($_POST["nmEmailCliente"]) . "',
						nmTelefoneCliente 		= '" . trim($_POST["nmTelefoneCliente"]) . "',
						nmCidadeCliente 		= '" . trim($_POST["nmCidadeCliente"]) . "',
						nmUfCliente 			= '" . $_POST["nmUfCliente"] . "',
						nmEnderecoCliente 		= '" . trim($_POST["nmEnderecoCliente"]) . "',
						nrNumeroCliente 		= '" . trim($_POST["nrNumeroCliente"]) . "',
						nmComplementoCliente 	= '" . trim($_POST["nmComplementoCliente"]) . "',
						nmBairroCliente 		= '" . $_POST["nmBairroCliente"] . "',
						nrCepCliente 			= '" . $_POST["nrCepCliente"] . "',
						dtDataCadastro 			= '" . $data . "'
						
				WHERE	idCliente		= " . $_POST["idCliente"] . "
			";

        //Executar SQL
        $db->update($query);

        if (isset($_REQUEST["pedido"])) {

            //Cadastra os dados do or�amento
            $qry = "
						SELECT 	max(nrPedido) as nrPedido
						FROM	tb_pedido
						WHERE 	year(dtDataPedido) = '" . date("Y") . "'
				
						";
            $qryNrPedido = $db->query($qry);

            $nrPedido = 0;
            if ($qryNrPedido[0]["nrPedido"] > 0) {
                $nrPedido = $qryNrPedido[0]["nrPedido"] + 1;
            } else {
                $nrPedido = 1;
            }

            //SQL
            $query = "
					INSERT INTO tb_pedido	(dtDataPedido,
											idTipoPedido,
											idSessao,
											ipUsuario,
											nrPedido,
											idCliente,
											nmBrowser,
											nmObservacoesPedido)
											
					VALUES					('" . $data . "',
											 '2',
											 '" . $_SESSION["ID_SESSAO"] . "',
											 '" . $_SERVER["REMOTE_ADDR"] . "',
											 '" . $nrPedido . "',
											 '" . $_POST["idCliente"] . "',
											 '" . $_SERVER["HTTP_USER_AGENT"] . "',
											 'Pedido cadastrado via Ugadmin, pelo usu�rio " . $_SESSION["NOME"] . ".')
				";

            //Executa SQL
            $db->update($query);

            //ID inserido
            $idPedido = mysql_insert_id();

            $retorno = $url_raiz . "admin/cad-pedido?idPedido=" . $idPedido;
        } else {
            $retorno = $url_raiz . "admin/cad-cliente?idCliente=" . $_POST["idCliente"] . "&atualizado";
        }

        //Retorno
        echo "
				<script type='text/javascript'>
					location.href='" . $retorno . "';
				</script>
			";
        break;

    case "Cadastrar":

        //Data atual formatada, para utilizar no log das opera��es
        $data = dataFormatoBanco(); //$data = converteData(date("d/m/Y")); 
        //1.Gravar os dados do cliente
        //1.1 Verifica pelo e-mail se j� foi cadastrado.
        $query = "
					SELECT 	idCliente
					FROM 	tb_cliente 
					WHERE	nmEmailCliente = '" . $_REQUEST["nmEmailCliente"] . "'
				";
        $qryVerificaCliente = $db->query($query);

        if ($qryVerificaCliente) { //Se o cliente j� est� cadastrado, atualiza
            $idCliente = $qryVerificaCliente[0]["idCliente"];

            //SQL
            $query = "
						UPDATE	tb_cliente
						SET		nmCliente 				= '" . trim($_POST["nmCliente"]) . "',
								nmEmailCliente 			= '" . trim($_POST["nmEmailCliente"]) . "',
								nmTelefoneCliente 		= '" . trim($_POST["nmTelefoneCliente"]) . "',
								nmCidadeCliente 		= '" . trim($_POST["nmCidadeCliente"]) . "',
								nmUfCliente 			= '" . $_POST["nmUfCliente"] . "',
								nmEnderecoCliente 		= '" . trim($_POST["nmEnderecoCliente"]) . "',
								nrNumeroCliente 		= '" . trim($_POST["nrNumeroCliente"]) . "',
								nmComplementoCliente 	= '" . trim($_POST["nmComplementoCliente"]) . "',
								nmBairroCliente 		= '" . $_POST["nmBairroCliente"] . "',
								nrCepCliente 			= '" . $_POST["nrCepCliente"] . "',
								dtDataCadastro 			= '" . $data . "'
								
						WHERE	idCliente		= " . $idCliente . "
					";

            //Executar SQL
            $db->update($query);

            if (isset($_REQUEST["pedido"])) {

                //Cadastra os dados do or�amento
                $qry = "
								SELECT 	max(nrPedido) as nrPedido
								FROM	tb_pedido
								WHERE 	year(dtDataPedido) = '" . date("Y") . "'
						
								";
                $qryNrPedido = $db->query($qry);

                $nrPedido = 0;
                if ($qryNrPedido[0]["nrPedido"] > 0) {
                    $nrPedido = $qryNrPedido[0]["nrPedido"] + 1;
                } else {
                    $nrPedido = 1;
                }

                //SQL
                $query = "
							INSERT INTO tb_pedido	(dtDataPedido,
													idTipoPedido,
													idSessao,
													ipUsuario,
													nrPedido,
													idCliente,
													nmBrowser,
													nmObservacoesPedido)
													
							VALUES					('" . $data . "',
													 '2',
													 '" . $_SESSION["ID_SESSAO"] . "',
													 '" . $_SERVER["REMOTE_ADDR"] . "',
													 '" . $nrPedido . "',
													 '" . $idCliente . "',
													 '" . $_SERVER["HTTP_USER_AGENT"] . "',
													 'Pedido cadastrado via Ugadmin, pelo usu�rio " . $_SESSION["NOME"] . ".')
						";

                //Executa SQL
                $db->update($query);

                //ID inserido
                $idPedido = mysql_insert_id();


                $retorno = $url_raiz . "admin/cad-pedido?idPedido=" . $idPedido;
            } else {
                $retorno = $url_raiz . "admin/cad-cliente?idCliente=" . $idCliente . "&atualizado";
            }

            //Retorno
            echo "
						<script type='text/javascript'>
							location.href='" . $retorno . "';
						</script>
					";
        } else { //Se o cliente ainda n�o est� cadastrado, insere os dados
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
					
						VALUES					('" . trim($_POST["nmCliente"]) . "',
												'" . trim($_POST["nmEmailCliente"]) . "',
												'" . trim($_POST["nmTelefoneCliente"]) . "',
												'" . trim($_POST["nmCidadeCliente"]) . "',
												'" . $_POST["nmUfCliente"] . "',
												'" . trim($_POST["nmEnderecoCliente"]) . "',
												'" . trim($_POST["nrNumeroCliente"]) . "',
												'" . trim($_POST["nmComplementoCliente"]) . "',
												'" . $_POST["nmBairroCliente"] . "',
												'" . $_POST["nrCepCliente"] . "',
												'" . $data . "')
					";

            $db->update($query);
            $idCliente = mysql_insert_id();

            if (isset($_REQUEST["pedido"])) {

                //Cadastra os dados do or�amento
                $qry = "
								SELECT 	max(nrPedido) as nrPedido
								FROM	tb_pedido
								WHERE 	year(dtDataPedido) = '" . date("Y") . "'
						
								";
                $qryNrPedido = $db->query($qry);

                $nrPedido = 0;
                if ($qryNrPedido[0]["nrPedido"] > 0) {
                    $nrPedido = $qryNrPedido[0]["nrPedido"] + 1;
                } else {
                    $nrPedido = 1;
                }

                //SQL
                $query = "
							INSERT INTO tb_pedido	(dtDataPedido,
													idTipoPedido,
													idSessao,
													ipUsuario,
													nrPedido,
													idCliente,
													nmBrowser)
													
							VALUES					('" . $data . "',
													 '2',
													 '" . $_SESSION["ID_SESSAO"] . "',
													 '" . $_SERVER["REMOTE_ADDR"] . "',
													 '" . $nrPedido . "',
													 '" . $idCliente . "',
													 '" . $_SERVER["HTTP_USER_AGENT"] . "')
						";

                //Executa SQL
                $db->update($query);

                //ID inserido
                $idPedido = mysql_insert_id();


                $retorno = $url_raiz . "admin/cad-pedido?idPedido=" . $idPedido;
            } else {
                $retorno = $url_raiz . "admin/cad-cliente?idCliente=" . $idCliente . "&atualizado";
            }

            //Retorno
            echo "
						<script type='text/javascript'>
							location.href='" . $retorno . "';
						</script>
					";
        }

        break;

    default:
        echo "
				<script type='text/javascript'>
					alert('Você não pode acessar esta página diretamente. Tente novamente.');
					location.href='" . $url_raiz . "admin/index';
				</script>
			";
        break;
}
?>