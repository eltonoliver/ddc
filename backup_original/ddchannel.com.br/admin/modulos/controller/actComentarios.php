<?php
	
	$acao =  $_REQUEST["acao"]? $_REQUEST["acao"]:"";
	
	switch ($acao){
		
		case "atualizaComentario":
			
			//data:{acao:'atualizaComentario', idCategoria:idCategoria, idConteudo:idConteudo, idComentario:idComentario}
			$qry = $db->update($db->updateQuery(array( 'idCategoria' => $_REQUEST['idCategoria'],
												'idConteudo' => $_REQUEST['idConteudo']
												), 'tb_comentarios', 'idComentario = '.$db->clean($_REQUEST['idComentario'])));
			echo $_REQUEST["callback"].'('.json_encode(array('retorno' => $qry)).')';
			exit;
			
		break;
		
		case "AtivarDesativar":
			
			$db->update($db->updateQuery(array('inPublicar' => ($_REQUEST['status']?true:false)), 'tb_comentarios', 'idComentario = '.$db->clean($_REQUEST['idComentario'])));
			exit;
			
		break;
		
		case "Publicar":
		
			$query = "
				UPDATE 	tb_comentarios
				SET		inPublicar = 1
				WHERE	idComentario	= ".$db->clean($_REQUEST["idComentario"])."
			";
		
			//Atualiza a tabela com os dados do formulário
			$db->update($query);
			
			//Retorno
			echo "
				<script type='text/javascript'>
					alert('Item Publicado.');
					location.href='".$url_raiz."admin/menu-comentarios?atualizado';
				</script>
			";
		break;
		
		case "Ocultar":
		
			$query = "
				UPDATE 	tb_comentarios
				SET		inPublicar = 0
				WHERE	idComentario	= ".$db->clean($_REQUEST["idComentario"])."
			";
		
			//Atualiza a tabela com os dados do formulário
			$db->update($query);
			
			//Retorno
			echo "
				<script type='text/javascript'>
					alert('Item Ocultado.');
					location.href='".$url_raiz."admin/menu-comentarios?atualizado';
				</script>
			";
		break;
		
		case "Excluir":
		
			//SQL
			$query = "
				DELETE	FROM tb_comentarios
				WHERE	idComentario	= ".$db->clean($_REQUEST["idComentario"])."
			";
			
			//Executar SQL
			$db->update($query);
			
			//RETORNO
			echo "
				<script type='text/javascript'>
					alert('Item excluído.');
					location.href='".$url_raiz."admin/menu-comentarios?atualizado';
				</script>
			";
					
			
		break;
		
		case "atualizaCategoria":
		
		new dBug($_POST);
		exit;
		
		break;
		
		case "Atualizar":
		
			//Data atual formatada, para utilizar no log das operações
			$data = dataFormatoBanco(); //$data = converteData(date("d/m/Y")); 
			
			//1.Atualiza os dados
			//Executar SQL
			$db->update($db->updateQuery(array(
				'nmCliente' 			=> trim($_POST["nmCliente"]),
				'nmEmailCliente' 		=> trim($_POST["nmEmailCliente"]),
				'nmTelefoneCliente' 	=>trim($_POST["nmTelefoneCliente"]) ,
				'nmCidadeCliente' 		=> trim($_POST["nmCidadeCliente"]),
				'nmUfCliente' 			=> $_POST["nmUfCliente"],
				'nmEnderecoCliente' 	=> trim($_POST["nmEnderecoCliente"]),
				'nrNumeroCliente' 		=> trim($_POST["nrNumeroCliente"]),
				'nmComplementoCliente' 	=> trim($_POST["nmComplementoCliente"]),
				'nmBairroCliente' 		=> $_POST["nmBairroCliente"],
				'nrCepCliente' 			=> $_POST["nrCepCliente"],
				'dtDataCadastro' 		=> $data
			), 'tb_comentarios', 'idComentario='.$db->clean($_POST["idComentario"])));
			
			if(isset($_REQUEST["pedido"])){

				//Cadastra os dados do orçamento
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
				
				//Executa SQL
				$db->update($db->insertQuery(array(
					'dtDataPedido' => $data,
					'idTipoPedido' => 2,
					'idSessao' => $_SESSION["ID_SESSAO"],
					'ipUsuario' => $_SERVER["REMOTE_ADDR"],
					'nrPedido' => $nrPedido,
					'idComentario' => $_POST["idComentario"],
					'nmBrowser' => $_SERVER["HTTP_USER_AGENT"],
					'nmObservacoesPedido' => 'Pedido cadastrado via Ugadmin, pelo usuário '.$_SESSION["NOME"]
				), 'tb_pedido'));
				
				//ID inserido
				$idPedido = mysql_insert_id();
				$retorno = $url_raiz."admin/cad-pedido?idPedido=".$idPedido;

			} else {
				$retorno = $url_raiz."admin/cad-cliente?idComentario=".$_POST["idComentario"]."&atualizado";
			}
				
			//Retorno
			echo  "
				<script type='text/javascript'>
					location.href='".$retorno."';
				</script>
			";
		break;	
		
		case "Cadastrar":
			
			//Data atual formatada, para utilizar no log das operações
			$data = dataFormatoBanco(); //$data = converteData(date("d/m/Y")); 
			
			//1.Gravar os dados do cliente
				//1.1 Verifica pelo e-mail se já foi cadastrado.
				$query = "
					SELECT 	idComentario
					FROM 	tb_comentarios 
					WHERE	nmEmailCliente = '".$db->clean($_REQUEST["nmEmailCliente"])."'
				";
				$qryVerificaCliente = $db->query($query);
				
				if($qryVerificaCliente){ //Se o cliente já está cadastrado, atualiza

					$idComentario = $qryVerificaCliente[0]["idComentario"];
					
					//Executar SQL
					$db->update($db->updateQuery(array(
						'nmCliente' 			=> trim($_POST["nmCliente"]),
						'nmEmailCliente' 		=> trim($_POST["nmEmailCliente"]),
						'nmTelefoneCliente' 	=> trim($_POST["nmTelefoneCliente"]),
						'nmCidadeCliente' 		=> trim($_POST["nmCidadeCliente"]),
						'nmUfCliente' 			=> $_POST["nmUfCliente"],
						'nmEnderecoCliente' 	=> trim($_POST["nmEnderecoCliente"]),
						'nrNumeroCliente' 		=> trim($_POST["nrNumeroCliente"]),
						'nmComplementoCliente' 	=> trim($_POST["nmComplementoCliente"]),
						'nmBairroCliente' 		=> $_POST["nmBairroCliente"],
						'nrCepCliente' 			=> $_POST["nrCepCliente"],
						'dtDataCadastro' 		=> $data
					), 'tb_comentarios', 'idComentario='.$db->clean($idComentario)));
					
					if(isset($_REQUEST["pedido"])){

						//Cadastra os dados do orçamento
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
						
						//Executa SQL
						$db->update($db->insertQuery(array(
							'dtDataPedido' 			=> $data,
							'idTipoPedido' 			=> 2,
							'idSessao' 				=> $_SESSION["ID_SESSAO"],
							'ipUsuario' 			=> $_SERVER["REMOTE_ADDR"], 
							'nrPedido' 				=> $nrPedido,
							'idComentario' 			=> $idComentario,
							'nmBrowser' 			=> $_SERVER["HTTP_USER_AGENT"],
							'nmObservacoesPedido' 	=> 'Pedido cadastrado via Ugadmin, pelo usuário '.$_SESSION["NOME"]
						), 'tb_pedido'));
						
						//ID inserido
						$idPedido = mysql_insert_id();
						$retorno = $url_raiz."admin/cad-pedido?idPedido=".$idPedido;

					} else {
						$retorno = $url_raiz."admin/cad-cliente?idComentario=".$idComentario."&atualizado";
					}
						
					//Retorno
					echo  "
						<script type='text/javascript'>
							location.href='".$retorno."';
						</script>
					";
				
				} else { //Se o cliente ainda não está cadastrado, insere os dados
					 
					$db->update($db->insertQuery(array(
						'nmCliente' 			=> trim($_POST["nmCliente"]),
						'nmEmailCliente' 		=> trim($_POST["nmEmailCliente"]),
						'nmTelefoneCliente' 	=> trim($_POST["nmTelefoneCliente"]),
						'nmCidadeCliente' 		=> trim($_POST["nmCidadeCliente"]),
						'nmUfCliente' 			=> $_POST["nmUfCliente"],
						'nmEnderecoCliente' 	=> trim($_POST["nmEnderecoCliente"]),
						'nrNumeroCliente' 		=> trim($_POST["nrNumeroCliente"]),
						'nmComplementoCliente' 	=> trim($_POST["nmComplementoCliente"]),
						'nmBairroCliente' 		=> $_POST["nmBairroCliente"],
						'nrCepCliente' 			=> $_POST["nrCepCliente"],
						'dtDataCadastro' 		=> $data
					), 'tb_comentarios'));
					
					$idComentario = mysql_insert_id();
					
					if(isset($_REQUEST["pedido"])){
						
						//Cadastra os dados do orçamento
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
						
						//Executa SQL
						$db->update($db->insertQuery(array(
							'dtDataPedido' => $data,
							'idTipoPedido' => 2,
							'idSessao' => $_SESSION["ID_SESSAO"],
							'ipUsuario' => $_SERVER["REMOTE_ADDR"],
							'nrPedido' => $nrPedido,
							'idComentario' => $idComentario,
							'nmBrowser' => $_SERVER["HTTP_USER_AGENT"]
						), 'tb_pedido'));
						
						//ID inserido
						$idPedido = mysql_insert_id();
						
						
						$retorno = $url_raiz."admin/cad-pedido?idPedido=".$idPedido;
					} else {
						$retorno = $url_raiz."admin/cad-cliente?idComentario=".$idComentario."&atualizado";
					}
					
					//Retorno
					echo "
						<script type='text/javascript'>
							location.href='".$retorno."';
						</script>
					";
					
				}

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