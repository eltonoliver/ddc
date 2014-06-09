<?php

function resume($string, $char) {

    $novaString = mb_substr($string, 0, $char, "UTF-8");
    if (strlen($string) > $char) {
        $novaString.= '...';
    }
    return $novaString;
}

function encurtaLocal($url) {
    $linkAPI = 'http://ugago.it/apiGerar.php?url=%s';
    $minURL = $linkAPI;
    $requestURL = sprintf($minURL, $url);
    $curl = (bool) function_exists('curl_init');
    $allow_url = (bool) ini_get('allow_url_fopen');

    if ($curl AND ! $allow_url) {
        $ch = curl_init($requestURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resultado = curl_exec($ch);
        curl_close($ch);
    } else if ($allow_url) {
        $resultado = file_get_contents($requestURL);
        if (!$resultado) {
            $handle = @fopen($requestURL, "r");
            $resultado = '';
            if ($handle)
                while (!feof($handle))
                    $resultado .= fgets($handle, 4096);
        }
    }
    else {
        trigger_error('tinyURL: Não é possível usar o cURL nem URLs com fopen!', E_USER_ERROR);
        $resultado = $url;
    }
    return ((isset($resultado) AND ! empty($resultado)) ? trim($resultado) : $url);
}

$protocolo = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false) ? 'http' : 'https';
$host = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$parametros = $_SERVER['QUERY_STRING'];
$urlAtual = $protocolo . '://' . $host . $script;
//ConfiguraÃ§Ã£o de diretÃ³rios e URLs especÃ­ficos do servidor
$nome_arquivo_atual = basename($_SERVER['PHP_SELF']);
$raiz = str_replace('\\', '/', realpath(dirname(__FILE__))) . '/';
$url_raiz = str_replace($nome_arquivo_atual, '', $urlAtual);

require($raiz . "lib/conexoes.php");
require_once($raiz . "/class/mysql.php");
include($raiz . "lib/rss_gen/feed.php");
$db = new mysqlClass($mysql_address, $mysql_username, $mysql_password, $mysql_database);

$idTipoConteudo = '26';
$sqlNoticias = "SELECT nmTituloAmigavel,nmUsuario,dtDataConteudo as data,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo= " . $idTipoConteudo . " order by dtDataConteudo desc limit 10";
$qryNoticias = $db->query($sqlNoticias);
//new dBug($qryNoticias);
//Create a new Feed
$feed = new Feed();
//Setting the channel elements
$feed->setFeedTitle('DURANGO DUARTE - DDCHANNEL');
$feed->setFeedLink('http://ddchannel.com.br/rss.php');
$feed->setFeedDesc('Os artigos publicados !');
$feed->setFeedImage('DURANGO DUARTE - DDCHANNEL', 'http://ddchannel.com.br/rss.php', 'http://ddchannel.com.br/img/logo.png', '285', '100');
$item = array();
for ($i = 0; $i < count($qryNoticias); $i++) {
    $url_noticia = 'http://ddchannel.com.br/artigos/' . $qryNoticias[$i]["nmTituloAmigavel"];
    $url_noticia = encurtaLocal(urlencode($url_noticia));
    $data_noticia = date('d/m/Y', strtotime($qryNoticias[$i]['data']));
    $titulo_noticia = '[' . $data_noticia . '] - ' . $qryNoticias[$i]['nmTituloConteudo'];
    $titulo_noticia = utf8_encode($titulo_noticia);
    //$conteudo_resumo1 = $qryNoticias[$i]['nmConteudo'];
    $conteudo_resumo = html_entity_decode($qryNoticias[$i]['nmConteudo'],ENT_QUOTES,"UTF-8");
    //Create a new Item
    $item[$i] = new Item();
    //Setting the Item elements
    //Helper -> http://www.rssboard.org/rss-specification
    $item[$i]->setItemTitle(utf8_decode($titulo_noticia));
    $item[$i]->setItemLink($url_noticia);
    $item[$i]->setItemDate($data_noticia);
    $item[$i]->setItemDesc(strip_tags(resume($conteudo_resumo, 150)));
    //$item1->setItemEnclosure( 'http://www.beardodisco.com/beatelectric/music/Loverboy12Mix.mp3', '17121349', 'audio/mpeg' );
    $item[$i]->setItemAuthor('contato@ddchannel.com.br(DDCHANNEL)');
    //As in Channel is possible to use setItemElm() function for setting other optional item elements
}
for ($i = 0; $i < count($qryNoticias); $i++) {
    //Adding both created items
    $feed->addItem($item[$i]);
}
//$teste = get_object_vars($item[0]);
//new dBug($teste);
//$url_noticia.$qryNoticias[$i]['idConteudo']
//Now we're ready to generate the Feed, Awesome!
try {
    $feed->genFeed();
} catch (Exception $e) {
    echo 'Ocorreu um erro inesperado. Contate o suporte.';
}
?>