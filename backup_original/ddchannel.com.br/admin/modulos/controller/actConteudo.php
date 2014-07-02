<?php

$acao = $_REQUEST["acao"] ? $_REQUEST["acao"] : "";

switch ($acao) {
    case "Excluir":

        $consulta = "SELECT nmLinkImagem, idTipoConteudo FROM tb_conteudo WHERE idConteudo = " . $db->clean($_REQUEST["idConteudo"]);

        //Busca a imagem relacionada ao conte�do.
        $qryConsulta = $db->query($consulta);

        if (strlen($qryConsulta[0]["nmLinkImagem"]) > 0) {
            $imgDeletar = $raiz . 'img/' . $qryConsulta[0]["nmLinkImagem"];
            unlink($imgDeletar);
        }

        //DELETANDO OS TAGS RELACIONADAS
        $query = 'DELETE FROM tb_conteudo_tag WHERE idConteudo=' . $db->clean($_REQUEST["idConteudo"]);
        $db->query($query);

        //DELETANDO AS TAGS RELACIONADAS
        $query = 'DELETE FROM tb_conteudo_tag WHERE idConteudo=' . $db->clean($_REQUEST["idConteudo"]);
        $db->query($query);

        $query = "
		DELETE	FROM tb_conteudo
		WHERE	idConteudo	= " . $db->clean($_REQUEST["idConteudo"]) . "
		";
        //Atualiza a tabela com os dados do formul�rio
        $qryDel = $db->update($query);

        if ($qryDel) {
            $_SESSION['msg'] = 'Item excluído com sucesso.';
        } else {
            $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
        }

        header('Location: ' . $url_raiz . 'admin/menu-conteudo?idTipoConteudo=' . $qryConsulta[0]["idTipoConteudo"]);

        break;

    case "Publicar":

        $consulta = "SELECT nmLinkImagem, idTipoConteudo FROM tb_conteudo WHERE idConteudo = " . $db->clean($_REQUEST["idConteudo"]);
        $qryConsulta = $db->query($consulta);

        $query = "
		UPDATE 	tb_conteudo
		SET		inPublicar = 1
		WHERE	idConteudo	= " . $db->clean($_REQUEST["idConteudo"]) . "
		";

        //Atualiza a tabela com os dados do formulário
        $qry = $db->update($query);

        if ($qry) {
            $_SESSION['msg'] = 'Item Publicado.';
        } else {
            $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
        }

        header('Location: ' . $url_raiz . 'admin/menu-conteudo?idTipoConteudo=' . $qryConsulta[0]["idTipoConteudo"]);

        break;

    case "Ocultar":
        $consulta = "SELECT nmLinkImagem, idTipoConteudo FROM tb_conteudo WHERE idConteudo = " . $db->clean($_REQUEST["idConteudo"]);
        $qryConsulta = $db->query($consulta);

        $query = "
		UPDATE 	tb_conteudo
		SET		inPublicar = 0
		WHERE	idConteudo	= " . $db->clean($_REQUEST["idConteudo"]) . "
		";

        //Atualiza a tabela com os dados do formul�rio
        $qry = $db->update($query);

        if ($qry) {
            $_SESSION['msg'] = 'Item Publicado.';
        } else {
            $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
        }

        header('Location: ' . $url_raiz . 'admin/menu-conteudo?idTipoConteudo=' . $qryConsulta[0]["idTipoConteudo"]);


        break;


    case "Atualizar":
        //verificando se quem estou alterando existe e foi quem eu solicitei
        $key = $_POST["idConteudo"];
        if (!isset($_SESSION[$key])) {
            header('Location: ' . $url_raiz . 'admin/sis-erro-sistema');
            exit;
        } else {
            $idInserido = $_SESSION[$key]['idConteudo'];
            unset($_SESSION[$key]);
        }

        $data = converteData($_POST["dtDataConteudo"]);
        $dataExpiracao = converteData($_POST["dtDataExpiracao"]);
        $conteudo = $_POST["nmConteudo"];
        $nmTituloAmigavel = $_POST["nmTituloAmigavel"] ? renomearArquivo($_POST["nmTituloConteudo"]) : NULL;
        $ordem = (int) $_POST["ordem"] > 0 ? (int) $_POST["ordem"] : NULL;
        $valor = strtr($_POST['valor'], array("." => "", "," => "."));
        //Atualiza a tabela com os dados do formul�rio
        $r = $db->update($db->updateQuery(array(
                    'nmTituloConteudo' => htmlspecialchars($_POST["nmTituloConteudo"]),
                    'nmTituloAmigavel' => $nmTituloAmigavel,
                    'nmResumo' => $_POST["nmResumo"],
                    'nmFonteExterna' => htmlspecialchars($_POST["nmFonteExterna"]),
                    'nmLinkExterno' => htmlspecialchars($_POST["nmLinkExterno"]),
                    'idCategoria' => $_POST["idCategoria"],
                    'idSecao' => $_POST["idSecao"],
                    'nmConteudo' => $conteudo,
                    'inVisibilidade' => $_POST["inVisibilidade"],
                    'nmObservacoes' => $_POST["nmObservacoes"],
                    'inPublicar' => $_POST["inPublicar"],
                    'inDestaque' => $_POST["inDestaque"],
                    'inComentario' => $_POST["inComentario"],
                    'idTipoConteudo' => $_POST["idTipoConteudo"],
                    'nmLinkImagem' => $_POST['nmLinkImagem'],
                    'nmLinkImagem2' => $_POST['nmLinkImagem2'],
                    'dtDataConteudo' => $data,
                    'dtDataExpiracao' => $dataExpiracao,
                    'inImpressao' => (int) $_POST['inImpressao'],
                    'idUsuarioCadastro' => $_POST["idUsuarioCadastro"],
                    'nmLinkArquivo' => $_POST['nmLinkArquivo'],
                    'idGaleria' => implode(',', $_POST['idGaleria']),
                    'idConteudoRelacionado' => $_POST['idConteudoRelacionado'] ? $_POST['idConteudoRelacionado'] : 0,
                    'nmLocal' => $_POST["nmLocal"],
                    'hrAbertura' => $_POST["hrAbertura"],
                    'linkFacebook' => $_POST["linkFacebook"],
                    'linkTwitter' => $_POST["linkTwitter"],
                    'linkLinkedin' => $_POST["linkLinkedin"],
                    'linkWikipedia' => $_POST["linkWikipedia"],
                    'linkInstagram' => $_POST["linkInstagram"],
                    'linkGoogle' => $_POST["linkGoogle"],
                    'valor' => $valor,
                    'ordem' => $_POST['ordem']
                        ), 'tb_conteudo', 'idConteudo=' . $db->clean($idInserido)));

        //POSTAR NO TWITTER
        if ($_POST["inCompartilhar"] == 1 && $r) {
            $strTwitter = "SELECT * FROM vwredesocialapi WHERE idRedeSocial = 1 ORDER BY idDadosApi DESC LIMIT 1"; //Para este projeto, estou usando somente os dados do Twitter
            $qryTwitter = $db->query($strTwitter);

            require_once($raiz . "/lib/twitteroauth/twitteroauth/twitteroauth.php");

            $consumer_key = $qryTwitter[0]['nmConsumerKey'];
            $consumer_secret = $qryTwitter[0]['nmConsumerSecret'];
            $oauth_token = $qryTwitter[0]['nmAcessToken'];
            $oauth_token_secret = $qryTwitter[0]['nmAcessTokenSecret'];

            $connection = new TwitterOAuth(
                    $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret
            );
            $linkNoticia = $url_raiz . 'detalhar/id/' . $idInserido;
            $linkNoticia = urlencode($linkNoticia);
            $linkCurto = encurtaLocal($linkNoticia);

            $txtTweet = $_POST["nmTituloConteudo"] . ' | ' . $linkCurto;
            //$txtTweet = $_POST["nmTituloConteudo"].' | '.$linkCurto.' via @'.$qryTwitter[0]['nmUsuario'];
            $txtTweet = utf8_encode($txtTweet);
            $result = $connection->post(
                    'statuses/update', array(
                'status' => $txtTweet,
                    )
            );
        }

        if (count($_POST['tags'])) {
            //apagando o relacionamento com as antigas tags
            $query = 'DELETE FROM tb_conteudo_tag WHERE idConteudo=' . $db->clean($idInserido);
            $db->query($query);

            foreach ($_POST['tags'] as $tag) {
                $query = 'INSERT INTO tb_conteudo_tag(idConteudo, idCategoria) VALUES(' . $db->clean($idInserido) . ', ' . $tag . ')';
                $db->update($query);
            }
        }

        echo "
		<script type='text/javascript'>
		alert('Dados atualizados com sucesso!');
		location.href='" . $url_raiz . "admin/cad-conteudo?idTipoConteudo=" . $_POST["idTipoConteudo"] . "&idConteudo=" . $idInserido . "&atualizado';
		</script>
		";
        break;

    case "Cadastrar":
        $conteudo = $_POST["nmConteudo"];
        $nmTituloAmigavel = $_POST["nmTituloAmigavel"] ? renomearArquivo($_POST["nmTituloConteudo"]) : NULL;
        $ordem = isset($_POST["ordem"]) ? (int) $_POST["ordem"] : NULL;
        $dataConteudo = converteData($_POST["dtDataConteudo"]);
        $dataExpiracao = converteData($_POST["dtDataExpiracao"]);
        $valor = strtr($_POST['valor'], array("." => "", "," => "."));
        $query = $db->insertQuery($d = array(
            'nmTituloConteudo' => trim(htmlspecialchars($_POST['nmTituloConteudo'])),
            'nmTituloAmigavel' => $nmTituloAmigavel,
            'nmResumo' => trim($_POST['nmResumo']),
            'nmFonteExterna' => trim(htmlspecialchars($_POST['nmFonteExterna'])),
            'nmLinkExterno' => trim(htmlspecialchars($_POST['nmLinkExterno'])),
            'idCategoria' => $_POST['idCategoria'],
            'idSecao' => $_POST['idSecao'],
            'nmConteudo' => $_POST['nmConteudo'],
            'inVisibilidade' => $_POST['inVisibilidade'],
            'nmObservacoes' => $_POST["nmObservacoes"],
            'inPublicar' => $_POST['inPublicar'],
            'inDestaque' => $_POST['inDestaque'],
            'inComentario' => $_POST['inComentario'],
            'idTipoConteudo' => $_POST['idTipoConteudo'],
            'nmLinkImagem' => $_POST['nmLinkImagem'],
            'nmLinkImagem2' => $_POST['nmLinkImagem2'],
            'nmLinkArquivo' => $_POST['nmLinkArquivo'],
            'dtDataConteudo' => $dataConteudo,
            'dtDataExpiracao' => $dataExpiracao,
            'dtDataCadastro' => dataFormatoBanco(),
            'inImpressao' => $_POST['inImpressao'],
            'idUsuarioCadastro' => $_SESSION['ID'],
            'inCompartilhar' => $_POST['inCompartilhar'],
            'idGaleria' => implode(',', $_POST['idGaleria']),
            'idConteudoRelacionado' => $_POST['idConteudoRelacionado'] ? $_POST['idConteudoRelacionado'] : 0,
            'nmLocal' => $_POST["nmLocal"],
            'hrAbertura' => $_POST["hrAbertura"],
            'nmLang' => $_POST['nmLang'],
            'idConteudoPt' => $_POST['idConteudoPt'],
            'linkFacebook' => $_POST["linkFacebook"],
            'linkTwitter' => $_POST["linkTwitter"],
            'linkLinkedin' => $_POST["linkLinkedin"],
            'linkWikipedia' => $_POST["linkWikipedia"],
            'linkInstagram' => $_POST["linkInstagram"],
            'linkGoogle' => $_POST["linkGoogle"],
            'valor' => $valor,
            'ordem' => $ordem), 'tb_conteudo');
        //Atualiza a tabela com os dados do formul�rio
        $qry = $db->update($query);
        $idInserido = mysql_insert_id();
        //TWITTER
        if ($_POST["inCompartilhar"] == 1) {
            //include($raiz.'lib/twitterAPI.php');
            $strTwitter = "SELECT * FROM vwredesocialapi WHERE idRedeSocial = 1 ORDER BY idDadosApi DESC LIMIT 1"; //Para este projeto, estou usando somente os dados do Twitter
            $qryTwitter = $db->query($strTwitter);

            require_once ( $raiz . "/lib/twitteroauth/twitteroauth/twitteroauth.php");

            $consumer_key = $qryTwitter[0]['nmConsumerKey'];
            $consumer_secret = $qryTwitter[0]['nmConsumerSecret'];
            $oauth_token = $qryTwitter[0]['nmAcessToken'];
            $oauth_token_secret = $qryTwitter[0]['nmAcessTokenSecret'];

            $connection = new TwitterOAuth(
                    $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret
            );
            $linkNoticia = $url_raiz . 'detalhar/id/' . $idInserido;
            $linkNoticia = urlencode($linkNoticia);
            $linkCurto = encurtaLocal($linkNoticia);

            //$txtTweet = $_POST["nmTituloConteudo"].' | '.$linkCurto.' via @'.$qryTwitter[0]['nmUsuario'];
            $txtTweet = $_POST["nmTituloConteudo"] . ' | ' . $linkCurto;
            $txtTweet = utf8_encode($txtTweet);
            $result = $connection->post(
                    'statuses/update', array(
                'status' => $txtTweet,
                    )
            );
        }


        if (count($_POST['tags'])) {
            foreach ($_POST['tags'] as $tag) {
                $query = 'INSERT INTO tb_conteudo_tag(idConteudo, idCategoria) VALUES(' . $idInserido . ', ' . $tag . ')';
                $db->update($query);
            }
        }


        if ($qry) {
            $_SESSION['msg'] = 'Dados cadastrados com sucesso!';
        } else {
            $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
        }

        header('Location: ' . $url_raiz . 'admin/cad-conteudo?idConteudo=' . $idInserido . "&idTipoConteudo=" . $_POST["idTipoConteudo"]);
        break;

    default:
        echo "
		<script type='text/javascript'>
		alert('Voce nao pode acessar esta p�gina diretamente. Tente novamente.');
		location.href='" . $url_raiz . "admin/login';
		</script>
		";
        break;
}
?>