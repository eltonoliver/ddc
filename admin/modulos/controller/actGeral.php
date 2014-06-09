<?php

$acao = $_REQUEST['acao'] ? $_REQUEST['acao'] : '';

$imagens = "";
//Upload da imagem do logtipo
if (strlen($_FILES["nmLinkLogoTopo"]["name"]) > 0) {

    $imagem = $_FILES["nmLinkLogoTopo"];
    $extensoes = "jpg,png,gif,jpeg";
    $caminho = $raiz . 'img/';

    $up->setCaminho($caminho);
    $up->setArquivo($imagem);
    $up->setExtensoes($extensoes);
    $up->setAltura('195');
    $up->setLargura('316');

    $retorno = $up->enviarArquivo();
    if (strlen($retorno["erro"]) > 0) {

        $_SESSION['msgErro'] = 'Ocorreu um erro: ' . $retorno["erro"];
        header('Location: ' . $url_raiz . 'admin/dados-geral');
    } else {
        $nmLinkLogoTopo = $retorno["nome_arquivo"];
        $imagens.= "nmLinkLogoTopo = '" . $nmLinkLogoTopo . "',";
    }
}

//MARCA DO SITE
if (strlen($_FILES["nmLinkMarcaSite"]["name"]) > 0) {

    $imagem = $_FILES["nmLinkMarcaSite"];
    $extensoes = "jpg,png,gif,jpeg";
    $caminho = $raiz . 'img/';

    $up->setCaminho($caminho);
    $up->setArquivo($imagem);
    $up->setExtensoes($extensoes);
    $up->setAltura('195');
    $up->setLargura('316');

    $retorno = $up->enviarArquivo();
    if (strlen($retorno["erro"]) > 0) {

        $_SESSION['msgErro'] = 'Ocorreu um erro: ' . $retorno["erro"];
        header('Location: ' . $url_raiz . 'admin/dados-geral');
    } else {
        $nmLinkMarcaSite = $retorno["nome_arquivo"];
        $imagens.= "nmLinkMarcaSite = '" . $nmLinkMarcaSite . "',";
    }
}

//MARCA MENOR
if (strlen($_FILES["nmLinkMarcaMenor"]["name"]) > 0) {

    $imagem = $_FILES["nmLinkMarcaMenor"];
    $extensoes = "jpg,png,gif,jpeg";
    $caminho = $raiz . 'img/';

    $up->setCaminho($caminho);
    $up->setArquivo($imagem);
    $up->setExtensoes($extensoes);
    $up->setAltura('195');
    $up->setLargura('316');

    $retorno = $up->enviarArquivo();
    if (strlen($retorno["erro"]) > 0) {

        $_SESSION['msgErro'] = 'Ocorreu um erro: ' . $retorno["erro"];
        header('Location: ' . $url_raiz . 'admin/dados-geral');
    } else {
        $nmLinkMarcaMenor = $retorno["nome_arquivo"];
        $imagens.= "nmLinkMarcaMenor = '" . $nmLinkMarcaMenor . "',";
    }
}

//MARCA MAIOR
if (strlen($_FILES["nmLinkMarcaMaior"]["name"]) > 0) {

    $imagem = $_FILES["nmLinkMarcaMaior"];
    $extensoes = "jpg,png,gif,jpeg";
    $caminho = $raiz . 'img/';

    $up->setCaminho($caminho);
    $up->setArquivo($imagem);
    $up->setExtensoes($extensoes);
    $up->setAltura('195');
    $up->setLargura('316');

    $retorno = $up->enviarArquivo();
    if (strlen($retorno["erro"]) > 0) {

        $_SESSION['msgErro'] = 'Ocorreu um erro: ' . $retorno["erro"];
        header('Location: ' . $url_raiz . 'admin/dados-geral');
    } else {
        $nmLinkMarcaMaior = $retorno["nome_arquivo"];
        $imagens.= "nmLinkMarcaMaior = '" . $nmLinkMarcaMaior . "',";
    }
}

$qryVerifica = $db->query("SELECT idGeral FROM tb_geral");

if ($qryVerifica) {
    $data = converteData($_POST["dtLancamento"]);
    $query = " 	
			UPDATE	tb_geral
			SET		nmEmpresa				= " . $db->clean($_POST["nmEmpresa"]) . ", 
					nmTituloSite			= " . $db->clean($_POST["nmTituloSite"]) . ",
					nmEmailSuporte			= " . $db->clean($_POST["nmEmailSuporte"]) . ",
					inHttps					= " . $db->clean($_POST["inHttps"]) . ",
                                        inOrcamento				= " . $_POST["inOrcamento"] . ",
                                        inPagSeguro				= " . $_POST["inPagSeguro"] . ",
                                        nmEmailPagSeguro		= '" . $_POST["nmEmailPagSeguro"] . "',
					" . $imagens . "
					dtLancamento			= " . $db->clean($data) . "
		";
    //Atualiza a tabela com os dados do formulário
    $qryGeral = $db->update($query);
} else {

    $query = " 
			INSERT INTO tb_geral(nmEmpresa, 
								nmTituloSite,
								nmEmailSuporte,
								inHttps,
								dtLancamento)
								
			VALUES				(" . $db->clean($_POST["nmEmpresa"]) . ", 
								" . $db->clean($_POST["nmTituloSite"]) . ",
								" . $db->clean($_POST["nmEmailSuporte"]) . ",
								" . $db->clean($_POST["inHttps"]) . ",
								" . $db->clean($data) . ")
		";

    //Cadastrar os dados do formulário na tabela
    $qryGeral = $db->update($query);
}

if ($qryGeral) {
    $_SESSION['msg'] = 'Dados atualizados com sucesso!';
} else {
    $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
}

header('Location: ' . $url_raiz . 'admin/dados-geral');
?>