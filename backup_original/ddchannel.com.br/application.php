
<?php

//header('Content-type: text/html; charset=UTF-8');
$protocolo = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false) ? 'http' : 'https';
$host = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$parametros = $_SERVER['QUERY_STRING'];

$urlAtual = $protocolo . '://' . $host . $script;
$urlAtualCompleta = $protocolo . '://' . $host . $script . '?' . $parametros;

//$linkEsteArquivo       = end(explode("/", $_SERVER['SCRIPT_NAME']));
//Configuração de diretórios e URLs específicos do servidor
$nome_arquivo_atual = basename($_SERVER['PHP_SELF']);
$raiz = str_replace('\\', '/', realpath(dirname(__FILE__))) . '/';
$url_raiz = str_replace($nome_arquivo_atual, '', $urlAtual);

//Se o Application for incluído no header da área administrativa, preservará os caminhos raiz para o diretório raiz
if (strpos($_SERVER['SCRIPT_FILENAME'], 'admin') > 0) {
    //Se estiver o admin, muda o caminho dos includes
    $raiz = str_replace('admin/', '', $raiz);
    $url_raiz = str_replace('admin/', '', $url_raiz);
}
if (strpos($_SERVER['SCRIPT_FILENAME'], 'controller') > 0) {
    //Se estiver o admin, muda o caminho dos includes
    $raiz = str_replace('controller/', '', $raiz);
    $url_raiz = str_replace('controller/', '', $url_raiz);
}

//Fuso horário padrão
date_default_timezone_set("America/Manaus");

//dadados de conexão do banco
require($raiz . "lib/conexoes.php");

//chamadas de classes globais
require($raiz . "lib/classes.php");

//Utilirários
require($raiz . "lib/utilitarios.php");
//require($raiz . "thumb.php");
//Funções
require($raiz . "lib/funcoes.php");

//TRATAMENTO DE ERROS
function chamaTrataErros($err_no, $err_desc, $err_file, $err_line, $err_trace) {
    $erros = new Erros();

    $erros->setMensagem($err_desc);
    $erros->setArquivo($err_file);
    $erros->setLinha($err_line);
    $erros->setCodErro($err_no);
    $erros->setDetalhes($err_trace);
    //$erros->setRaiz($raiz);
    $erros->mostraMensagem();
}

//Tipos de erros que serão tratadas
//Todos os tipos possíveis: http://php.net/manual/en/errorfunc.constants.php
error_reporting(E_ERROR);
//informa qual o método que irá tratar as excessões levantadas pelo php
set_error_handler("chamaTrataErros", E_ERROR);

//Funções Específicas
require($raiz . "lib/lib_especifica.php");

//criando a url segura
$urls_raiz = str_replace('http', 'https', $url_raiz);
//desabilita o https
if (!$geralConfig[0]['inHttps']) {
    $urls_raiz = $url_raiz;
}
?>