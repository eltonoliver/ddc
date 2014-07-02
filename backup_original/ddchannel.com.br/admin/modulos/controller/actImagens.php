<?php

$qryDel = $db->query("delete from tb_dados_imagens");
$n = count($_POST['nome']);
for ($i = 0; $i < $n; $i++) {
    if (!vazio($_POST['nome'][$i])) {
        $query = $db->insertQuery(array('largura' => (int) $_POST['largura'][$i], 'altura' => (int) $_POST['altura'][$i], 'nome' => tratastr($_POST['nome'][$i]), 'crop' => (int) $_POST['crop' . $i]), 'tb_dados_imagens');
        $qryGeral = $db->update($query);
    }
}

if ($qryGeral) {
    $_SESSION['msg'] = 'Dados inseridos com sucesso!';
} else {
    $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
}

header('Location: ' . $url_raiz . 'admin/dados-imagens');
?>