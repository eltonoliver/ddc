<?php

$acao = $_REQUEST["acao"] ? $_REQUEST["acao"] : "";

switch ($acao) {

    case "AtivarDesativar":

        $db->update($db->updateQuery(array('inPublicar' => ($_REQUEST['status'] ? true : false)), 'tb_arquivo', 'idArquivo = ' . $db->clean($_REQUEST['idArquivo'])));
        exit;

        break;

    case "Ativar":

        $query = "
				UPDATE 	tb_menu
				SET		inExibir = 1
				WHERE	idMenu	= " . $db->clean($_REQUEST["idMenu"]) . "
				OR 		idMenuPai	= " . $db->clean($_REQUEST["idMenu"]) . "
			";

        //Atualiza a tabela com os dados do formul�rio
        $db->update($query);

        //Retorno
        echo "
				<script type='text/javascript'>
					alert('Menu Ativado.');
					location.href='" . $url_raiz . "admin/menu-menus?atualizado';
				</script>
			";

        break;

    case "Destivar":

        $query = "
				UPDATE 	tb_menu
				SET		inExibir = 0
				WHERE	idMenu	= " . $db->clean($_REQUEST["idMenu"]) . "
				OR 		idMenuPai	= " . $db->clean($_REQUEST["idMenu"]) . "
			";

        //Atualiza a tabela com os dados do formul�rio
        $db->update($query);

        //Retorno
        echo "
				<script type='text/javascript'>
					alert('Menu Desativado.');
					location.href='" . $url_raiz . "admin/menu-menus?atualizado';
				</script>
			";
        break;

    case "Excluir":

        //1. Busca todos os menus filhos
        $qryFilhos = $db->query("SELECT idMenu FROM tb_menu WHERE	idMenuPai = " . $db->clean($_REQUEST["idMenu"]));
        $listaFilhos = campoMatrizParaLista('', $qryFilhos, 'idMenu');

        //1.1 Se possui filhos, deleta
        if ($qryFilhos) {
            //1.1.1 Deleta os relacionamentos dos filhos com os perfis
            $query = "
						DELETE	FROM tb_perfil_menu
						WHERE	idMenu in (" . $listaFilhos . ")
					";
            $db->update($query);

            //1.1.2 Deleta os menus filhos
            $query = "
						DELETE	FROM tb_menu
						WHERE	idMenu in (" . $listaFilhos . ")
					";
            $db->update($query);
        }

        //2 Deleta os relacionamentos dos menus com os perfis
        $query = "
				DELETE	FROM tb_perfil_menu
				WHERE	idMenu = " . $db->clean($_REQUEST["idMenu"]) . "
			";
        $db->update($query);

        //3. Deleta os menus
        $query = "
				DELETE	FROM tb_menu
				WHERE	idMenu = " . $db->clean($_REQUEST["idMenu"]) . "
			";
        $db->update($query);

        //Retorno
        echo "
				<script type='text/javascript'>
					alert('Menu exclu�do.');
					location.href='" . $url_raiz . "admin/menu-menus?atualizado';
				</script>
			";
        break;

    case "Atualizar":

        //Atualiza a tabela com os dados do formul�rio
        $teste = $db->update($db->updateQuery(array(
                    'nmMenu' => $_POST["nmMenu"],
                    'descricaoMenu' => $_POST["descricaoMenu"],
                    'idMenuPai' => $_POST["idMenuPai"],
                    'ordemMenu' => $_POST["ordemMenu"],
                    'linkMenu' => $_POST["linkMenu"],
                    'inExibir' => $_POST["inExibir"]
                        ), 'tb_menu', 'idMenu=' . $db->clean($_POST["idMenu"])));


        $query = "DELETE FROM tb_perfil_menu WHERE idMenu = " . $db->clean($_POST["idMenu"]);
        $db->update($query);

        for ($i = 0; $i <= $_POST["totalPerfis"]; $i++) {

            $campo = 'idPerfil_' . $i;
            $valor = $_POST[$campo];
            if (strlen($_POST[$campo]) > 0) {
                $query = "INSERT INTO tb_perfil_menu (idPerfil, idMenu) VALUES (" . $db->clean($valor) . "," . $db->clean($_POST["idMenu"]) . ")";
                $db->update($query);
            }
        }

        echo "
				<script type='text/javascript'>
					alert('Dados atualizados com sucesso!');
					location.href='" . $url_raiz . "admin/cad-menu?idMenu=" . $_POST["idMenu"] . "&idMenuPai=" . $_POST["idMenuPai"] . "&atualizado';
				</script>
			";
        break;

    case "Cadastrar":
        $idConteudo = (int) $_POST["idConteudo"];
        $data = dataFormatoBanco(); //$data = converteData(date("d/m/Y")); 

        for ($i = 0; $i < $_POST["totalArquivos"]; $i++) {

            //1. Seta os campos
            $campo1 = 'nmNomeArquivo_' . $i;
            $nomeArquivo = $_POST[$campo1];

            $campo2 = 'nmTituloArquivo_' . $i;
            $tituloArquivo = $_POST[$campo2];

            $campo3 = 'nmDescricaoArquivo_' . $i;
            $descricaoArquivo = $_POST[$campo3];

            //2. Identificar o tipo de arquivo
            //Verifica a extens�o do arquivo e define 
            //o tipo a ser associado na tabela
            $arquivo = $raiz . 'arquivos/enviados/temp/' . $nomeArquivo;
            $tipoArquivo = tipoArquivo($arquivo); //Esta fun��o est� na no arquivo lib/lib_especficia.php
            $idTipoArquivo = $tipoArquivo["idTipoArquivo"];
            $pasta = $tipoArquivo["pasta"];

            //3. Gravar os dados no banco
            if (!$idConteudo) {
                $resposta = $db->update($db->insertQuery(array(
                            'dtDataEnvio' => $data,
                            'nmNomeArquivo' => $nomeArquivo,
                            'nmDescricaoArquivo' => trim($descricaoArquivo),
                            'nmTituloArquivo' => trim($tituloArquivo),
                            'idTipoArquivo' => $idTipoArquivo,
                            'idUsuarioCadastro' => $_SESSION["ID"],
                            'idStatusGeral' => 1,
                            'inVisibilidade' => 1,
                            'inPublicar' => 1,
                            'inImpressao' => 1
                                ), 'tb_arquivo'));
                //var_dump($resposta);exit;
            } else {
                $resposta = $db->update($db->insertQuery(array(
                            'nmImagem' => $nomeArquivo,
                            'idGaleria' => $idConteudo,
                            'nmLegenda' => trim($tituloArquivo),
                            'inOrdem' => $i + 1
                                ), 'tb_galeria_imagem'));
            }
            if ($resposta) {
                $diretorio = $raiz . 'arquivos/enviados/';
                if (!$idConteudo) {
                    copy($diretorio . 'temp/' . $nomeArquivo, $diretorio . $pasta . '/' . $nomeArquivo);
                    unlink($diretorio . 'galeria/' . $nomeArquivo);
                } else {
                    //mkdir($diretorio . $pasta . '/' . $idConteudo . '/', 0755);
                    mkdir($diretorio . 'galeria/' . $idConteudo . '/', 0755);
                    /* mkdir($diretorio . 'home/' . $idConteudo . '/', 0755);
                      mkdir($diretorio . 'miniaturas/' . $idConteudo . '/', 0755); */
                    mkdir($diretorio . 'thumbnails/' . $idConteudo . '/', 0755);
                    //copy($diretorio . 'image/' . $nomeArquivo, $diretorio . $pasta . '/' . $idConteudo . '/' . $nomeArquivo);
                    copy($diretorio . 'image/' . $nomeArquivo, $diretorio . 'galeria/' . $idConteudo . '/' . $nomeArquivo);
                    /* copy($diretorio . 'home/' . $nomeArquivo, $diretorio . 'home/' . $idConteudo . '/' . $nomeArquivo);
                      copy($diretorio . 'miniaturas/' . $nomeArquivo, $diretorio . 'miniaturas/' . $idConteudo . '/' . $nomeArquivo); */
                    copy($diretorio . 'thumbnails/' . $nomeArquivo, $diretorio . 'thumbnails/' . $idConteudo . '/' . $nomeArquivo);
                    unlink($diretorio . 'image/' . $nomeArquivo);
                    /* unlink($diretorio . 'galeria/' . $nomeArquivo);
                      unlink($diretorio . 'home/' . $nomeArquivo);
                      unlink($diretorio . 'miniaturas/' . $nomeArquivo); */
                    unlink($diretorio . 'thumbnails/' . $nomeArquivo);
                }
            }
        }
        //Deleta todos os arquivos na pasta tempor�ria
        $diretorio = $raiz . 'arquivos/enviados/temp/';
        $dir->setDiretorio($diretorio);
        $dir->limpaDiretorio();

        /* //Deleta as tumbnails dos arquivos na pasta tempor�ria
          $diretorio = $raiz.'arquivos/enviados/thumbnails/';
          $dir->setDiretorio($diretorio);
          $dir->limpaDiretorio(); */
        if (!$idConteudo) {
            echo '<script type="text/javascript">location.href="' . $url_raiz . 'admin/cad-arquivos3?noTopoRodape=' . $_REQUEST['noTopoRodape'] . '"</script>'; //exit;
        } else {
            echo "<script type='text/javascript'>
                                    setTimeout(function() {
                                            window.parent.$('#layer_popup_frame').css('display', 'none');
                                            window.parent.$('#layer_popup_conteudo_frame').css('display', 'none');
                                            window.parent.location.href='" . $url_raiz . "admin/cad-galeria?id=" . $idConteudo . "'
                                    }, 1000);                
                    </script>"; //exit;
        }
        break;

    case 'validarArquivosTemp':

        $caminho = 'arquivos/enviados/temp/';
        $thumb = 'arquivos/enviados/thumbnails/';
        $diretorio = $raiz . $caminho;
        $dir->setDiretorio($diretorio);
        $dir->setExtensao('*');
        $arq = $dir->listaArquivos();

        echo json_encode(array('status' => (count($arq) ? true : false)));
        exit;

        break;

    case 'adicionarTag':

        $idArquivo = $db->clean($_REQUEST['idArquivo']);
        $idCategoria = $db->clean($_REQUEST['idCategoria']);

        if (!strlen($idArquivo) || !strlen($idCategoria)) {
            echo json_encode(array('status' => false));
        } else {
            $query = 'INSERT INTO tb_arquivo_categoria(idCategoria, idArquivo) VALUES(' . $idCategoria . ', ' . $idArquivo . ')';
            $status = $db->update($query);
            echo json_encode(array('status' => $status));
        }

        exit;

        break;

    case 'adicionarTagNova':

        $r = $db->select(array(
            'nmCategoria' => trim($_POST["nmCategoria"]),
            'inTipo' => 2
                ), 'tb_categoria');

        if ($r) {
            echo json_encode(array('status' => 1));
        } else {
            $dados = array(
                'nmCategoria' => $_POST["nmCategoria"],
                'idCategoriaPai' => 0,
                'inTipo' => 2,
                'inDestaque' => 0);

            $query = $db->insertQuery($dados, 'tb_categoria');
            $db->update($query);
            $idArquivo = $_REQUEST['idArquivo'];
            $idCategoria = mysql_insert_id();

            if (!strlen($idArquivo) || !strlen($idCategoria)) {
                echo json_encode(array('status' => 1));
            } else {
                $query = 'INSERT INTO tb_arquivo_categoria(idCategoria, idArquivo) VALUES(' . $idCategoria . ', ' . $idArquivo . ')';
                $status = $db->update($query);
                echo json_encode(array('id' => $idCategoria, 'status' => 2));
            }
        }
        exit;
        break;

    case 'excluirTag':
        $idArquivo = $_REQUEST['idArquivo'];
        $idCategoria = $_REQUEST['idCategoria'];

        if (!strlen($idArquivo) || !strlen($idCategoria)) {
            echo json_encode(array('status' => false));
        } else {
            $query = 'DELETE FROM tb_arquivo_categoria WHERE idCategoria=' . $db->clean($idCategoria) . ' AND idArquivo=' . $db->clean($idArquivo);
            $status = $db->update($query);
            echo json_encode(
                    array(
                        'status' => $status,
                        'idArquivo' => $idArquivo,
                        'idCategoria' => $idCategoria
                    )
            );
        }

        exit;

        break;

    case 'excluirArquivo':

        $idArquivo = $db->clean($_REQUEST['idArquivo']);
        if (!strlen($idArquivo)) {
            echo json_encode(array('status' => false));
        } else {
            $query = 'DELETE FROM tb_arquivo_categoria WHERE idArquivo=' . $idArquivo;
            $status = $db->update($query);
            $query = 'SELECT * FROM tb_arquivo WHERE idArquivo=' . $idArquivo;
            $qryArquivo = $db->query($query);
            if ($qryArquivo) {
                $qryArquivo = current($qryArquivo);
                $pasta = pastaArquivo($qryArquivo["idTipoArquivo"]);
                $arquivo = $raiz . 'arquivos/enviados/' . $pasta['pasta'] . '/' . $qryArquivo["nmNomeArquivo"];

                unlink($arquivo);
                if ($qryArquivo["idTipoArquivo"] == 6) {
                    $miniatura = $raiz . 'arquivos/enviados/miniaturas/' . $qryArquivo["nmNomeArquivo"];
                    unlink($miniatura);
                    $thumbnail = $raiz . 'arquivos/enviados/thumbnails/' . $qryArquivo["nmNomeArquivo"];
                    unlink($thumbnail);
                    $home = $raiz . 'arquivos/enviados/home/' . $qryArquivo["nmNomeArquivo"];
                    unlink($home);
                }

                $query = 'DELETE FROM tb_arquivo WHERE idArquivo=' . $idArquivo;
                $db->update($query);
                echo json_encode(array('status' => true));
            }
        }

        exit;

        break;

    case 'editarArquivo':

        $idArquivo = $db->clean($_REQUEST['idArquivo']);
        if (!strlen($idArquivo)) {
            echo json_encode(array('status' => false));
        } else {

            $resposta = $db->update($db->updateQuery(array(
                        'nmTituloArquivo' => $_REQUEST['nmTituloArquivo'],
                        'nmDescricaoArquivo' => $_REQUEST['nmDescricaoArquivo'],
                        'inVisibilidade' => $_REQUEST['inVisibilidade'],
                        'inPublicar' => $_REQUEST['inPublicar'],
                        'inImpressao' => $_REQUEST['inImpressao'],
                            ), 'tb_arquivo', 'idArquivo=' . $idArquivo));

            if (!$resposta) {
                echo json_encode(array('status' => false, 'erro' => mysql_error()));
            } else {
                echo json_encode(array(
                    'status' => true,
                    'nmTituloArquivo' => $_REQUEST['nmTituloArquivo'],
                    'nmDescricaoArquivo' => $_REQUEST['nmDescricaoArquivo']
                ));
            }
        }

        exit;

        break;

    case 'validarFinalizar':

        /* $arq = $db->query(
          "SELECT COUNT(*) total FROM tb_arquivo a WHERE a.idStatusGeral = 1 AND a.idArquivo NOT IN (SELECT idArquivo FROM tb_arquivo_categoria);");
          echo json_encode(array('status'=>($arq[0]['total']?false:true)));
         */
        echo json_encode(array('status' => true));
        exit;

        break;

    case 'finalizar':

        $query = 'UPDATE tb_arquivo SET idStatusGeral = 2 WHERE idStatusGeral = 1';
        $resposta = $db->update($query);

        echo json_encode(array('status' => $resposta));
        exit;

        break;

    case 'adicionarAudio':

        $idArquivo = $db->clean($_REQUEST['idArquivo']);
        $nmLinkExterno = $db->clean($_REQUEST['idAudio']);

        if (!strlen($idArquivo) || !strlen($nmLinkExterno)) {
            echo json_encode(array('status' => false));
        } else {
            $query = 'UPDATE tb_arquivo SET nmLinkExterno=' . $nmLinkExterno . ' WHERE idArquivo = ' . $idArquivo;
            $status = $db->update($query);
            echo json_encode(array('status' => $status));
        }

        exit;

        break;

    default:
        echo "
				<script type='text/javascript'>
					alert('Você não pode acessar esta página diretamente.');
					location.href='" . $url_raiz . "admin/login';
				</script>
			";
        break;
}
?>