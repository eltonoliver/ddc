<?php

$acao = $_REQUEST["acao"] ? $_REQUEST["acao"] : "";

switch ($acao) {
    case "Ativar":
        $query = "
				UPDATE 	tb_usuario
				SET		inAtivo = 1
				WHERE	idUsuario	= " . $db->clean($_REQUEST["idUsuario"]) . "
			";
        //Atualiza a tabela com os dados do formulário
        $db->update($query);
        //Retorno
        echo "
				<script type='text/javascript'>
					alert('Usuário Ativado.');
					location.href='" . $url_raiz . "admin/menu-usuarios?atualizado';
				</script>
			";
        break;

    case "Destivar":
        //pega perfil
        $query = "select idPerfil from tb_usuario WHERE idUsuario=" . (int) $_REQUEST["idUsuario"];
        $queryPerfil = $db->query($query);
        //se administrador, nao permite desativar
        if ($queryPerfil[0]['idPerfil'] == 1) {
            echo "
				<script type='text/javascript'>
					alert('Usuário não pode ser desativado.');
					location.href='" . $url_raiz . "admin/menu-usuarios';
				</script>
			";
        } else {
            $query = "
				UPDATE 	tb_usuario
				SET inAtivo = 0
				WHERE idUsuario	= " . $db->clean($_REQUEST["idUsuario"]) . "
			";
            //Atualiza a tabela com os dados do formulário
            $db->update($query);
            //Retorno
            echo "
				<script type='text/javascript'>
					alert('Usuário Desativado.');
					location.href='" . $url_raiz . "admin/menu-usuarios?atualizado';
				</script>
			";
        }
        break;

    case "Excluir":
        //pega perfil
        $query = "select idPerfil from tb_usuario WHERE idUsuario=" . (int) $_REQUEST["idUsuario"];
        $queryPerfil = $db->query($query);
        //se administrador, nao permite excluir
        if ($queryPerfil[0]['idPerfil'] == 1) {
            echo "
				<script type='text/javascript'>
					alert('Usuário não pode ser excluido.');
					location.href='" . $url_raiz . "admin/menu-usuarios';
				</script>
			";
        } else {
            //Atualiza todos os conteudos para autoria do usuário administrador
            $query = "UPDATE tb_conteudo SET idUsuarioCadastro = 1 WHERE idUsuarioCadastro = " . $db->clean($_REQUEST["idUsuario"]);
            $db->update($query);
            //Deleta o usuário
            $query = "
				DELETE	FROM tb_usuario
				WHERE	idUsuario	= " . $db->clean($_REQUEST["idUsuario"]) . "
			";

            $db->update($query);
            //Retorno
            echo "
				<script type='text/javascript'>
					alert('Item excluído.');
					location.href='" . $url_raiz . "admin/menu-usuarios?atualizado';
				</script>
			";
        }
        break;

    case "Atualizar":
        //pega perfil
        $query = "select idPerfil from tb_usuario WHERE idUsuario=" . (int) $_POST["idUsuario"];
        $queryPerfil = $db->query($query);
        //se administrador, nao permite excluir
        if ($queryPerfil[0]['idPerfil'] == 1 and ($_POST["idUsuario"] != $_SESSION['ID'])) {
            echo "
				<script type='text/javascript'>
					alert('Usuário não pode ser modificado por voce.');
					location.href='" . $url_raiz . "admin/menu-usuarios';
				</script>
			";
        } else {
            $filtro = "";
            if (strlen(trim($_POST["nmNovaSenha"])) > 0) {
                if (strlen($_POST["nmNovaSenha"]) < 8 or (!preg_match("/([a-zA-Z])/", $_POST["nmNovaSenha"]) and !preg_match("/([0-9])/", $_POST["nmNovaSenha"]) and !preg_match("/([!,%,&,@,#,$,^,*,?,_,~])/", $_POST["nmNovaSenha"]))) {
                    echo "
				<script type='text/javascript'>
					alert('Senha com no mínimo 8 caracteres, contendo letras, números e caracteres especiais!');
					location.href='" . $url_raiz . "admin/cad-usuario?idUsuario=" . $_POST["idUsuario"] . "';
				</script>
			";
                    exit;
                }
                if ($_POST["nmNovaSenha"] != $_POST["confirmacaoNovaSenha"]) {
                    echo "
				<script type='text/javascript'>
					alert('Confirmação de senha incorreta!');
					location.href='" . $url_raiz . "admin/cad-usuario?idUsuario=" . $_POST["idUsuario"] . "';
				</script>
			";
                    exit;
                }
                $filtro = "nmSenha				= '" . md5(trim(strtolower($_POST["nmNovaSenha"] . '-uga'))) . "',";
            }

            $query = "
				UPDATE	tb_usuario
				
				SET		nmUsuario			= " . $db->clean(htmlspecialchars($_POST["nmUsuario"])) . ",
						nmLogin				= " . $db->clean(trim(strtolower(htmlspecialchars($_POST["nmLogin"])))) . ",
						" . $filtro . "
						nmEmailUsuario		= " . $db->clean($_POST["nmEmailUsuario"]) . ",
						idPerfil			= " . $db->clean($_POST["idPerfil"]) . ",
						inAtivo				= " . $db->clean($_POST["inAtivo"]) . ",
						idPerfil			= " . $db->clean($_POST["idPerfil"]) . "
						
				WHERE	idUsuario				= " . $db->clean($_POST["idUsuario"]) . "
			";

            //Atualiza a tabela com os dados do formulário
            $teste = $db->update($query);
            echo "
				<script type='text/javascript'>
					alert('Dados atualizados com sucesso!');
					location.href='" . $url_raiz . "admin/cad-usuario?idUsuario=" . $_POST["idUsuario"] . "&atualizado';
				</script>
			";
        }
        break;

    case "Cadastrar":
        if (strlen($_POST["nmSenha"]) < 8 or (!preg_match("/([a-zA-Z])/", $_POST["nmSenha"]) and !preg_match("/([0-9])/", $_POST["nmSenha"]) and !preg_match("/([!,%,&,@,#,$,^,*,?,_,~])/", $_POST["nmSenha"]))) {
            echo "
				<script type='text/javascript'>
					alert('Senha com no mínimo 8 caracteres, contendo letras, números e caracteres especiais!');
					location.href='" . $url_raiz . "admin/cad-usuario';
				</script>
			";
            exit;
        }
        if ($_POST["nmSenha"] != $_POST["confirmacaoSenha"]) {
            echo "
				<script type='text/javascript'>
					alert('Confirmação de senha incorreta!');
					location.href='" . $url_raiz . "admin/cad-usuario';
				</script>
			";
            exit;
        } else {
            $query = "
				INSERT INTO tb_usuario	(nmUsuario,
										 nmLogin,
										 nmSenha,
										 nmEmailUsuario,
										 inAtivo,
										 idPerfil,
										 dtDataCadastro)
										
				VALUES					(" . $db->clean(htmlspecialchars($_POST["nmUsuario"])) . ",
										 " . $db->clean(trim(strtolower(htmlspecialchars($_POST["nmLogin"])))) . ",
										 " . $db->clean(md5(trim(strtolower($_POST["nmSenha"] . '-uga')))) . ",
										 " . $db->clean($_POST["nmEmailUsuario"]) . ",
										 " . $db->clean($_POST["inAtivo"]) . ",
										 " . $db->clean($_POST["idPerfil"]) . ",
										 " . $db->clean(dataFormatoBanco()) . ")
			";

            //Atualiza a tabela com os dados do formulário
            $db->update($query);
            $idInserido = mysql_insert_id();

            echo "
				<script type='text/javascript'>
					alert('Dados inseridos com sucesso!');
					location.href='" . $url_raiz . "admin/cad-usuario?idUsuario=" . $idInserido . "&atualizado';
				</script>
			";
        }
        break;

    default:
        echo "
				<script type='text/javascript'>
					alert('Você não pode acessar esta página diretamente. Tente novamente.');
					location.href='" . $url_raiz . "admin/login';
				</script>
			";
        break;
}
?>