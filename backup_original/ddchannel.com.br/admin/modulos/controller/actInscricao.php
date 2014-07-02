<?php

if ($_REQUEST["acao"]) {
    $acao = $_REQUEST["acao"];
} else {
    $acao = "";
}
switch ($acao) {
    case "Ativar":
        $query = "UPDATE tb_inscricao SET selecionada = 1 WHERE idInscricao= " . $db->clean($_REQUEST["idInscricao"]);
        //Atualiza a tabela com os dados do formul�rio
        $db->update($query);
        //Retorno
        echo "
				<script type='text/javascript'>
					alert('Inscrição Selecionada.');
					location.href='" . $url_raiz . "admin/inscritos-promocao?idPromocao=" . $_REQUEST["idPromocao"] . "&atualizado';
				</script>
			";
        break;
    case "Desativar":
        $query = "UPDATE tb_inscricao SET selecionada = 0 WHERE idInscricao= " . $db->clean($_REQUEST["idInscricao"]);
        //Atualiza a tabela com os dados do formul�rio
        $db->update($query);
        //Retorno
        echo "
				<script type='text/javascript'>
					alert('Inscrição deselecionada.');
					location.href='" . $url_raiz . "admin/inscritos-promocao?idPromocao=" . $_REQUEST["idPromocao"] . "&atualizado';
				</script>
			";
        break;
    case "Excluir":
        //SQL
        $query = "DELETE FROM tb_inscricao WHERE idInscricao	= " . $_REQUEST["idInscricao"];
        //Executar SQL
        $db->update($query);
        //RETORNO
        echo "
				<script type='text/javascript'>
					alert('Item excluido.');
					location.href='" . $url_raiz . "admin/inscritos-promocao?idPromocao=" . $_REQUEST["idPromocao"] . "';
				</script>
			";
        break;
    default:
        echo "
				<script type='text/javascript'>
					alert('Você não pode acessar esta página diretamente.');
					location.href='" . $url_raiz . "admin/index.php';
				</script>
			";
        break;
}
?>