<?php

$acao = $_REQUEST["acao"] ? $_REQUEST["acao"] : "";

switch ($acao) {
    case "Excluir":
        if ($_REQUEST["idPerfil"] == 1) {
            echo "
				<script type='text/javascript'>
					alert('Perfil não pode ser excluido.');
					location.href='" . $url_raiz . "admin/menu-perfis';
				</script>
			";
        } else {
            //Atualiza a tabela de relacionamento dos menus para ficar sem perfil
            $query = "DELETE FROM tb_perfil_menu WHERE idPerfil = " . $db->clean($_REQUEST["idPerfil"]);
            $db->update($query);
            //Deleta a categoria
            $query = "
				DELETE	FROM tb_perfil
				WHERE	idPerfil	= " . $db->clean($_REQUEST["idPerfil"]) . "
			";
            $db->update($query);
            //Retorno
            echo "
				<script type='text/javascript'>
					alert('Item excluído.');
					location.href='" . $url_raiz . "admin/menu-perfis?atualizado';
				</script>
			";
        }
        break;

    case "Atualizar":
        if ($_POST["idPerfil"] == 1 and ($_POST["idPerfil"] != $_SESSION['PERFIL'])) {
            echo "
				<script type='text/javascript'>
					alert('Perfil não pode ser modificado por voce.');
					location.href='" . $url_raiz . "admin/menu-perfis';
				</script>
			";
        } else {
            $arrayMenus = $_POST["idMenu"];
            $query = $db->updateQuery(array(
                'nmPerfil' => trim($_POST["nmPerfil"])
                    ), 'tb_perfil', 'idPerfil = ' . $_POST["idPerfil"]);
            $qry = $db->update($query);
            $idInserido = $_POST["idPerfil"];

            //Deleta todos os menus associados a este perfil
            $query = "DELETE FROM tb_perfil_menu WHERE idPerfil = " . $db->clean($idInserido) . " AND idPerfil <> 1"; //Por segurança, o usuário administrador não pode ser alterado
            $teste = $db->update($query);

            //Insere os novos menus para o usuário
            for ($i = 0; $i < count($arrayMenus); $i++) {
                $query = "INSERT INTO tb_perfil_menu (idPerfil, idMenu) VALUES (" . $db->clean($idInserido) . "," . $db->clean($arrayMenus[$i]) . ")";
                $teste = $db->update($query);
            }

            if ($qry) {
                $_SESSION['msg'] = 'Dados atualizados com sucesso!';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }

            header('Location: ' . $url_raiz . 'admin/cad-perfil?idPerfil=' . $idInserido . '&atualizado');
        }
        break;


    case "Ativar":
        $id = $_REQUEST['idPerfil'];
        $query = "
				UPDATE 	tb_perfil
				SET		inAtivo = 1
				WHERE	idPerfil	= " . $db->clean($id) . "
			";

        $qry = $db->update($query);

        if ($qry) {
            $_SESSION['msg'] = 'Item ativado com sucesso!';
            header('Location: ' . $url_raiz . 'admin/menu-perfis');
        } else {
            $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            header('Location: ' . $url_raiz . 'admin/menu-perfis?idPerfil=' . $id);
        }
        break;

    case "Destivar":
        $id = $_REQUEST['idPerfil'];
        if ($id == 1) {
            echo "
				<script type='text/javascript'>
					alert('Perfil não pode ser desativado.');
					location.href='" . $url_raiz . "admin/menu-perfis';
				</script>
			";
        } else {
            $query = "
				UPDATE 	tb_perfil
				SET		inAtivo = 0
				WHERE	idPerfil	= " . $db->clean($id) . "
			";

            $qry = $db->update($query);

            if ($qry) {
                $_SESSION['msg'] = 'Item desativado com sucesso!';
                header('Location: ' . $url_raiz . 'admin/menu-perfis');
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
                header('Location: ' . $url_raiz . 'admin/menu-perfis?idPerfil=' . $id);
            }
        }
        break;

    case "Cadastrar":
        $arrayMenus = $_POST["idMenu"];
        //$arrayMenus = implode(',',$_POST["idMenu"]);

        $query = $db->insertQuery(array(
            'nmPerfil' => trim($_POST["nmPerfil"]),
            'inAtivo' => 1
                ), 'tb_perfil');
        $qry = $db->update($query);
        $idInserido = mysql_insert_id();

        //Insere os novos menus para o usuário
        for ($i = 0; $i < count($arrayMenus); $i++) {
            $query = "INSERT INTO tb_perfil_menu (idPerfil, idMenu) VALUES ('" . $idInserido . "'," . $db->clean($arrayMenus[$i]) . ")";
            $teste = $db->update($query);
        }

        if ($qry) {
            $_SESSION['msg'] = 'Dados atualizados com sucesso!';
        } else {
            $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
        }

        header('Location: ' . $url_raiz . 'admin/cad-perfil?idPerfil=' . $idInserido . '&atualizado');

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