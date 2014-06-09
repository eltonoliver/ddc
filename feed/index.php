<?php
$protocolo    = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false) ? 'http' : 'https';
$host         = $_SERVER['HTTP_HOST'];
$script       = $_SERVER['SCRIPT_NAME'];
$parametros   		= $_SERVER['QUERY_STRING'];
$urlAtual     = $protocolo . '://' . $host . $script;
//Configuração de diretórios e URLs específicos do servidor
$nome_arquivo_atual 	= basename ($_SERVER['PHP_SELF']);
$raiz 	 				= str_replace('\\','/',realpath(dirname(__FILE__))).'/';
$url_raiz				= str_replace($nome_arquivo_atual,'',$urlAtual);

require($raiz . "lib/conexoes.php");
require_once($raiz. "/class/mysql.php");
include($raiz. "class/rss_gen/feed.php" );

//Create a new Feed
$feed = new Feed( );

//Setting the channel elements
//Helper -> http://www.rssboard.org/rss-specification
$feed->setFeedTitle( 'RSS Ugagogo' );
$feed->setFeedLink( $url_raiz.'rss.php' );
$feed->setFeedDesc( 'RSS Ugagogo' );
$feed->setFeedImage( 'RSS Ugagogo', $url_raiz.'img/site/logo.png',$url_raiz.'rss.php');

//Is possible to use setChannelElm() function for setting other optional channel elements
$feed->setChannelElm( 'language', 'pt-br' );
$feed->setAtom($url_raiz.'rss.php');

$db=new mysqlClass($mysql_address,$mysql_username,$mysql_password,$mysql_database);
$novidades = $db->query('SELECT * FROM tb_conteudo WHERE idTipoConteudo = 11 AND inPublicar = 1 LIMIT 10');
foreach($novidades as $novidade){
	//data
	$objDateTime=new DateTime($novidade['dtDataCadastro']);
	$data=$objDateTime->format(DateTime::RSS);
	//Create a new Item
	$item = new Item( );
	//Setting the Item elements
	//Helper -> http://www.rssboard.org/rss-specification
	$item->setItemTitle(utf8_encode($novidade['nmTituloConteudo']));
	$item->setItemLink($url_raiz."interna/conteudo/".$novidade['idConteudo']);
	$item->setItemDate($data);
	$item->setItemDesc(utf8_encode($novidade['nmResumo']));
	$item->setItemEnclosure($url_raiz."arquivos/enviados/image/".$novidade['nmLinkImagem'],filesize($raiz."arquivos/enviados/image/".$novidade['nmLinkImagem']), 'image/jpeg' );
	$item->setItemAuthor( 'contato@ugagogo.com.br (Ugagogo)' );
	$item->setItemElm("guid", $url_raiz."interna/conteudo/".$novidade['idConteudo'],' isPermaLink="true"');
	//As in Channel is possible to use setItemElm() function for setting other optional item elements
	//Adding both created items
	$feed->addItem($item);
}
//Now we're ready to generate the Feed, Awesome!
$feed->genFeed( );
?>