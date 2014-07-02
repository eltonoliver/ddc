<?php

//function calculaTempo($data1,$data2){
//	$date1 = $data1;//dataFormatoBanco();//"2008-11-01 22:45:00"; 
//	
//	$date2 = $data2;//"2012-10-07 00:00:00";
//	
//	$diff = abs(strtotime($date2) - strtotime($date1)); 
//	
//	$years   = floor($diff / (365*60*60*24)); 
//	$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
//	$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
//	
//	$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
//	
//	$minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
//	
//	$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 
//	
//	//printf("%d years, %d months, %d days, %d hours, %d minuts\n, %d seconds\n", $years, $months, $days, $hours, $minuts, $seconds);
//	$retorno = array('anos'=>$years,'meses'=>$months,'dias'=>$days,'horas'=>$hours,'minutos'=>$minuts,'segundos'=>$seconds);
//	return $retorno;
//}
//Verifica se uma string está contida em outra
function comparaDatas($data1, $data2, $operador) {
    $novaData1 = strtotime($data1);
    $novaData2 = strtotime($data2);

    switch ($operador) {
        case "==":
            return ($novaData1 == $novaData2) ? true : false;
            break;
        case ">=":
            return ($novaData1 >= $novaData2) ? true : false;
            break;
        case "<=":
            return ($novaData1 <= $novaData2) ? true : false;
            break;
        case "<":
            return ($novaData1 < $novaData2) ? true : false;
            break;
        case ">":
            return ($novaData1 > $novaData2) ? true : false;
            break;
        default:
            return ($novaData1 == $novaData2) ? true : false;
            break;
    }
}

function gerKeyDados() {
    $key = md5(uniqid());
    $_SESSION[$key] = array();
    return $key;
}

// EXAMPLE CALLBACK
$fn = create_function('&$key, &$value', 'if ($key === "key2") {
    $key = "BY REF KEY EXAMPLE"; // THIS IS THE WHOLE POINT OF THIS POST
}
if ($value === "value2") {
    $value = "BY REF VALUE EXAMPLE";
}');
/* $fn = function (&$key, &$value) { // NOTE THE FUNCTION'S SIGNATURE (USING & FOR BOTH $key AND $value)
  if ($key === 'key2') {
  $key = 'BY REF KEY EXAMPLE'; // THIS IS THE WHOLE POINT OF THIS POST
  }
  if ($value === 'value2') {
  $value = 'BY REF VALUE EXAMPLE';
  }
  };
 */

function c2sdecrypt($s, $k) {
    $k = base64_decode(urldecode($k));
    $s = urldecode($s);
    $k = str_split(str_pad('', strlen($s), $k));
    $sa = str_split($s);
    foreach ($sa as $i => $v) {
        $t = ord($v) - ord($k[$i]);
        $sa[$i] = chr($t < 0 ? ($t + 256) : $t);
    }
    return utf8_decode(urldecode(join('', $sa)));
}

function GetDomain($url) {
    $nowww = ereg_replace('www\.', '', $url);
    $domain = parse_url($nowww);
    if (!empty($domain["host"])) {
        return $domain["host"];
    } else {
        return $domain["path"];
    }
}

function redirecionar($url) {
    ob_end_clean();
    header('Location: ' . $url);
    exit;
}

function resume($string, $char) {

    $novaString = mb_substr($string, 0, $char, "UTF-8");
    if (strlen($string) > $char) {
        $novaString.= '...';
    }
    return $novaString;
}

function validaURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
}

function vazio($string) {
    if (strlen($string) > 0) {
        return false;
    } else {
        return true;
    }
}

function vdump($obj) {

    return new dBug($obj);
}

function encurtaLocal($url) {
    $linkAPI = 'http://ugago.it/apiGerar.php?url=%s';
    define('minURL', $linkAPI);

    //define('minURL', 'http://tinyurl.com/api-create.php?url=%s');
    $requestURL = sprintf(minURL, $url);

    $curl = (bool) function_exists('curl_init');
    $allow_url = (bool) ini_get('allow_url_fopen');

    if ($curl AND !$allow_url) {
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
    return ((isset($resultado) AND !empty($resultado)) ? trim($resultado) : $url);
}

function achaString($str, $content, $ignorecase = true) {
    if ($ignorecase) {
        $str = strtoupper($str);
        $content = strtoupper($content);
    }
    return strpos($content, $str) ? true : false;
}

function montaPopTags($campoConteudo) {

    //transformar a string em um array, isolando os blocos dos links
    $arrayTeste = explode('+', $campoConteudo);
    //var_dump($arrayTeste);
    //exit;
    //Isolar os nomes dos arquivos que o usuário deseja adicionar
    $arrayLink;
    $j = 0;

    for ($i = 0; $i < count($arrayTeste); $i++) {
        //echo achaString($arrayTeste[$i],'',true);
        if (achaString('.ADD', $arrayTeste[$i], true)) {
            $final = strlen($arrayTeste[$i]) - 6;
            //$arrayLink[$j]["referencia"] = substr($arrayTeste[$i],2,$final);	
            //$arrayLink[$j]["tipo"] = substr($arrayTeste[$i],0,1);	
            $arrayLink[$j] = array(
                'referencia' => substr($arrayTeste[$i], 2, $final),
                'tipo' => substr($arrayTeste[$i], 0, 1)
            );
            $j++;
        }
    }
    //new dBUg($arrayLink);
    //exit;
    //Montar as novas strings
    $novaString;
    for ($i = 0; $i < count($arrayLink); $i++) {

        $novaString[$i]["completa"] = '<a href="#" onClick="chamaPop(\'' . $arrayLink[$i]["referencia"] . '\');">TESTE</a>';
        $novaString[$i]["antiga"] = '<a href="http://+' . $arrayLink[$i]["tipo"] . '.' . $arrayLink[$i]["referencia"] . '.ADD+">';

        switch ($arrayLink[$i]["tipo"]) {

            case 'A':
                $funcao = 'chamaPopArquivo';
                break;

            case 'P':
                $funcao = 'chamaPopPagina';
                break;

            default:
                $funcao = 'chamaPop';
                break;
        }


        $novaString[$i]["nova"] = '<a href="#" onClick="' . $funcao . '(\'' . $arrayLink[$i]["referencia"] . '\');">';
    }

    //Substituir as novas strings no texto principal
    $novoTexto = stripslashes($campoConteudo);
    for ($i = 0; $i < count($arrayLink); $i++) {

        $novoTexto = str_replace($novaString[$i]["antiga"], $novaString[$i]["nova"], $novoTexto);
    }
    return $novoTexto;
    //new dBug($novoTexto);
}

function min_url($url) {
    define('minURL', 'http://migre.me/api.txt?url=%s');
    //define('minURL', 'http://tinyurl.com/api-create.php?url=%s');
    $requestURL = sprintf(minURL, $url);

    $curl = (bool) function_exists('curl_init');
    $allow_url = (bool) ini_get('allow_url_fopen');

    if ($curl AND !$allow_url) {
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
    }
    return ((isset($resultado) AND !empty($resultado)) ? trim($resultado) : $url);
}

//$conexao = mysql_connect('localhost','admin','vertrigo');
//$conectar = mysql_select_db('dbMemorial',$conexao);

function getArvoreMenus($idMenuPai = 0, $idTipoMenu = 1) { //,$nivel=0
    //$nivel++;
    $str = "SELECT * FROM tb_menu WHERE idMenuPai = " . $idMenuPai . " AND idTipoMenu = " . $idTipoMenu . " ORDER BY ordemMenu";
    $qryMontaMenu = mysql_query($str);
    echo '<ul>';
    while ($qryMenusInfo = mysql_fetch_assoc($qryMontaMenu)) {
        ?>
        <li><a href="<?php echo $qryMenusInfo["linkMenu"]; ?>"><?php echo $qryMenusInfo["nmMenu"]; ?></a>

            <?php
            $qryVerificaFilho = 'SELECT idMenu FROM tb_menu WHERE idMenuPai = ' . $qryMenusInfo["idMenu"] . ' LIMIT 1';
            $resVerificaFilho = mysql_query($qryVerificaFilho);
            $totalFilhos = mysql_num_rows($resVerificaFilho);

            if ($totalFilhos > 0) {
                getArvoreMenus($qryMenusInfo["idMenu"]);
            }
            ?>

        </li>
        <?php
    }
    echo '</ul>';
}

//Fim-função

function getArvoreMenusLista($idMenuPai = 0, $idTipoMenu = 1, $listaMenus) { //,$nivel=0
    //$nivel++;
    $str = "SELECT * FROM tb_menu WHERE idMenuPai = " . $idMenuPai . " AND idTipoMenu = " . $idTipoMenu . " AND idMenu in (" . $listaMenus . ") ORDER BY ordemMenu";
    $qryMontaMenu = mysql_query($str);
    echo '<ul>';
    while ($qryMenusInfo = mysql_fetch_assoc($qryMontaMenu)) {
        ?>
        <li><a href="<?php echo $qryMenusInfo["linkMenu"]; ?>"><?php echo $qryMenusInfo["nmMenu"]; ?></a>

            <?php
            $qryVerificaFilho = 'SELECT idMenu FROM tb_menu WHERE idMenuPai = ' . $qryMenusInfo["idMenu"] . ' AND idMenu in (' . $listaMenus . ') LIMIT 1';
            $resVerificaFilho = mysql_query($qryVerificaFilho);
            $totalFilhos = mysql_num_rows($resVerificaFilho);

            if ($totalFilhos > 0) {
                getArvoreMenusLista($qryMenusInfo["idMenu"], 1, $listaMenus);
            }
            ?>

        </li>
        <?php
    }
    echo '</ul>';
}

//Fim-função

function getArvoreMenusLista2($idMenuPai = 0, $idTipoMenu = 1, $listaMenus, $menuMontado) { //,$nivel=0
    //$nivel++;
    $str = "SELECT * FROM tb_menu WHERE idMenuPai = " . $idMenuPai . " AND idTipoMenu = " . $idTipoMenu . " AND idMenu in (" . $listaMenus . ") ORDER BY ordemMenu";
    $qryMontaMenu = mysql_query($str);
    $menuMontado.= '<ul>';
    while ($qryMenusInfo = mysql_fetch_assoc($qryMontaMenu)) {

        //$menuMontado.='<li><a href="'.$qryMenusInfo["linkMenu"].'">'.$qryMenusInfo["nmMenu"].'</a>';
        $menuMontado.='<li><a href="' . strtolower(preg_replace(array('/[A-Z]/', '/.php/'), array('-${0}', ''), $qryMenusInfo["linkMenu"])) . '">' . $qryMenusInfo["nmMenu"] . '</a>';


        $qryVerificaFilho = 'SELECT idMenu FROM tb_menu WHERE idMenuPai = ' . $qryMenusInfo["idMenu"] . ' AND idMenu in (' . $listaMenus . ') LIMIT 1';
        $resVerificaFilho = mysql_query($qryVerificaFilho);
        $totalFilhos = mysql_num_rows($resVerificaFilho);
        //$menuMontado.= '<ul><li><a>TESTE   '.$cont.'</a></li></ul>';
        if ($totalFilhos > 0) {
            $menuMontado = getArvoreMenusLista2($qryMenusInfo["idMenu"], $idTipoMenu, $listaMenus, $menuMontado);
        }

        $menuMontado.= '</li>';
    }
    $menuMontado.= '</ul>';
    return $menuMontado;
}

//Fim-função

function geraMenusPerfil($matrizMenus) {

    $listaMenus = campoMatrizParaLista('', $matrizMenus, 'idMenu');
    $menuMontado = getArvoreMenusLista2($idMenuPai = 0, $idTipoMenu = 1, $listaMenus, '');
    return $menuMontado;
}

//Fim-função
///Busca todos os menus para gerar CheckBox
function geraChecksMenus($matrizMenus, $arrayMenus) {
    $listaMenus = campoMatrizParaLista('', $matrizMenus, 'idMenu');
    $menuMontado = getArvoreCheckMenus($idMenuPai = 0, $idTipoMenu = 1, $listaMenus, '', $arrayMenus);
    return $menuMontado;
}

//Fim-função

function getArvoreCheckMenus($idMenuPai = 0, $idTipoMenu = 1, $listaMenus, $menuMontado, $arrayMenus) { //,$nivel=0
    //$nivel++;
    $str = "SELECT * FROM tb_menu WHERE idMenuPai = " . $idMenuPai . " AND idTipoMenu = " . $idTipoMenu . " AND idMenu in (" . $listaMenus . ") ORDER BY ordemMenu";
    $qryMontaMenu = mysql_query($str);
    $menuMontado.= '<ul>';
    while ($qryMenusInfo = mysql_fetch_assoc($qryMontaMenu)) {
        if (in_array($qryMenusInfo["idMenu"], $arrayMenus)) {
            $check = 'checked="checked"';
        }

//				vdump($qryMenusInfo["idMenu"]);
//				vdump($arrayMenus);
        //vdump(in_array($qryMenusInfo["idMenu"],$arrayMenus));
        //exit;
        //$menuMontado.='<li><a href="'.$qryMenusInfo["linkMenu"].'">'.$qryMenusInfo["nmMenu"].'</a>';
        $menuMontado.='<li><input type="checkbox" name="idMenu[]" id="idMenu_' . $qryMenusInfo["idMenu"] . '" value="' . $qryMenusInfo["idMenu"] . '" ' . $check . '/> ' . $qryMenusInfo["nmMenu"] . '';


        $qryVerificaFilho = 'SELECT idMenu FROM tb_menu WHERE idMenuPai = ' . $qryMenusInfo["idMenu"] . ' AND idMenu in (' . $listaMenus . ') LIMIT 1';
        $resVerificaFilho = mysql_query($qryVerificaFilho);
        $totalFilhos = mysql_num_rows($resVerificaFilho);
        //$menuMontado.= '<ul><li><a>TESTE   '.$cont.'</a></li></ul>';
        if ($totalFilhos > 0) {
            $menuMontado = getArvoreCheckMenus($qryMenusInfo["idMenu"], $idTipoMenu, $listaMenus, $menuMontado, $arrayMenus);
        }

        $menuMontado.= '</li>';
    }
    $menuMontado.= '</ul>';
    return $menuMontado;
}

//Fim-função

function geraSelectMenus($matrizMenus) {

    $listaMenus = campoMatrizParaLista('', $matrizMenus, 'idMenu');
    $menuMontado = getArvoreSelectMenus($idMenuPai = 0, $idTipoMenu = 1, $listaMenus, '');
    return $menuMontado;
}

//Fim-função

function getArvoreSelectMenus($idMenuPai = 0, $idTipoMenu = 1, $listaMenus, $menuMontado) { //,$nivel=0
    //$nivel++;
    $str = "SELECT * FROM tb_menu WHERE idMenuPai = " . $idMenuPai . " AND idTipoMenu = " . $idTipoMenu . " AND idMenu in (" . $listaMenus . ") ORDER BY ordemMenu";
    $qryMontaMenu = mysql_query($str);
    $menuMontado.= '<ul>';
    while ($qryMenusInfo = mysql_fetch_assoc($qryMontaMenu)) {

        //$menuMontado.='<li><a href="'.$qryMenusInfo["linkMenu"].'">'.$qryMenusInfo["nmMenu"].'</a>';
        $menuMontado.='<li><a href="' . strtolower(preg_replace(array('/[A-Z]/', '/.php/'), array('-${0}', ''), $qryMenusInfo["linkMenu"])) . '">' . $qryMenusInfo["nmMenu"] . '</a>';


        $qryVerificaFilho = 'SELECT idMenu FROM tb_menu WHERE idMenuPai = ' . $qryMenusInfo["idMenu"] . ' AND idMenu in (' . $listaMenus . ') LIMIT 1';
        $resVerificaFilho = mysql_query($qryVerificaFilho);
        $totalFilhos = mysql_num_rows($resVerificaFilho);
        //$menuMontado.= '<ul><li><a>TESTE   '.$cont.'</a></li></ul>';
        if ($totalFilhos > 0) {
            $menuMontado = getArvoreSelectMenus($qryMenusInfo["idMenu"], $idTipoMenu, $listaMenus, $menuMontado);
        }

        $menuMontado.= '</li>';
    }
    $menuMontado.= '</ul>';
    return $menuMontado;
}

//Fim-função
//NAO ESTA EM USO
/*
  function montaMenusPefil($matrizMenus){

  //return getArvoreMenusLista($idMenuPai=0,$idTipoMenu=1,$listaMenus);

  $listaMenus = campoMatrizParaLista('',$matrizMenus,'idMenu');

  //new dbug($listaMenus);

  return getArvoreMenusLista($idMenuPai=0,$idTipoMenu=1,$listaMenus);


  }//Fim-função
 */
?>


<?php

function campoMatrizParaLista($valorInicial = '', $matriz, $campo) {

    $lista = $valorInicial;
    for ($cont = 0; $cont < count($matriz); $cont++) {
        if (strlen($lista) > 0) {
            $lista = $lista . ',';
        }
        $lista = $lista . '' . $matriz[$cont][$campo];
    }

    return $lista;
}

function campoMatrizParaArray($valorInicial, $matriz, $campo) {

    $lista = $valorInicial;
    for ($cont = 0; $cont < count($matriz); $cont++) {
        if (strlen($lista) > 0) {
            $lista = $lista . ',';
        }


        $lista = $lista . '' . $matriz[$cont][$campo];
    }
    //new dBUg($lista);
    //exit;

    $array = explode(',', $lista);

    return $array;
}

function limpaString($string) {
    $novaString = str_replace('.', '', $string);
    $novaString = str_replace(',', '', $novaString);
    $novaString = str_replace('/', '', $novaString);
    $novaString = str_replace('\\', '', $novaString);
    $novaString = str_replace('-', '', $novaString);
    $novaString = str_replace('(', '', $novaString);
    $novaString = str_replace(')', '', $novaString);

    return $novaString;
}

function limpaStringArquivo($string) {
    $novaString = str_replace(',', '', $string);
    $novaString = str_replace('/', '', $novaString);
    $novaString = str_replace('\\', '', $novaString);
    $novaString = str_replace('-', '', $novaString);
    $novaString = str_replace('(', '', $novaString);
    $novaString = str_replace(')', '', $novaString);
    $novaString = str_replace(" ", "-", $novaString);
    $novaString = str_replace("&amp;", "e", $novaString);
    return $novaString;
}

function renomearArquivo($string) {

    $string = limpaStringArquivo($string);
    $busca = array('Á', 'À', 'Ã', 'É', 'Ê', 'Í', 'Ô', 'Ó', 'Õ', 'Ú', 'Ç', 'á', 'à', 'ã', 'é', 'ê', 'í', 'ô', 'ó', 'õ', 'ú', 'ç', '"', "'");
    $retira = array('A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'U', 'C', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'c', '', "");
    $string = str_replace($busca, $retira, $string);

    setlocale(LC_CTYPE, "pt_BR");
    $novaString = trim(strtolower($string));

    return $novaString;
}

;

//DESCOPACTAR
//Permite descompactar arquivos .zip no servidor 
function descompactar($arquivo, $destino) {

    $zip = new ZipArchive; //Classe nativa do PHP
    $res = $zip->open($arquivo);
    if ($res === TRUE) {
        $zip->extractTo($destino . '/');
        $zip->close();
        return true;
    } else {
        return false;
    }
}

//CONVERTE DATA
//Converte uma data no formato 20/07/2011 para 2011-07-20 00:00:00
function converteData($string) {
    $ano = substr($string, 6, 4);
    $mes = substr($string, 3, 2);
    $dia = substr($string, 0, 2);
    $dataCompleta = $ano . '-' . $mes . '-' . $dia . ' 00:00:00';
    $dateConvertida = strtotime($dataCompleta);
    $data = date("Y-m-d H:i:s", $dateConvertida);

    return $data;
}

function dataFormatoBanco() {

    date_default_timezone_set("America/Halifax");

    $string = date("d/m/Y H:i:s");

    $ano = substr($string, 6, 4);
    $mes = substr($string, 3, 2);
    $dia = substr($string, 0, 2);


    $hora = substr($string, 11, 2);
    $minuto = substr($string, 14, 2);
    $segundo = substr($string, 17, 2);

    $dataCompleta = $ano . '-' . $mes . '-' . $dia . ' ' . $hora . ':' . $minuto . ':' . $segundo;

    $dateConvertida = strtotime($dataCompleta);
    $data = date("Y-m-d H:i:s", $dateConvertida);

    return $data;
}

function dataFormatoBancoSimples() {

    date_default_timezone_set("America/Manaus");

    $string = date("d/m/Y H:i:s");

    $ano = substr($string, 6, 4);
    $mes = substr($string, 3, 2);
    $dia = substr($string, 0, 2);


    $hora = '00';
    $minuto = '00';
    $segundo = '00';

    $dataCompleta = $ano . '-' . $mes . '-' . $dia . ' ' . $hora . ':' . $minuto . ':' . $segundo;

    $dateConvertida = strtotime($dataCompleta);
    $data = date("Y-m-d H:i:s", $dateConvertida);

    return $data;
}

//BUSCA PASTA
//Permite buscar uma pasta em um diretório
function buscaPasta($diretorio) {
    $pasta = opendir($diretorio);
    while ($file = readdir($pasta)) {
        if ($file != "." && $file != "..") {
            
        }
        $arquivo = $file;
    }
    closedir($pasta);
    return $arquivo;
}

//SELECIONA PÁGINA
//Verifica se a página atual é a página passada
function selecionaPagina($pagina) {

    if (strpos(trim(strtolower(urlAtualCompleta())), strtolower($pagina)) == 0) {
        return false;
    } else {
        return true;
    }
}

function verificaPaginasURL($lista) {

    $arrayPaginas = explode(",", $lista);

    $teste = 0;
    for ($i = 0; $i < count($arrayPaginas); $i++) {
        if (strpos(trim(strtolower(urlAtualCompleta())), trim(strtolower($arrayPaginas[$i]))) == 0) {
            $teste++;
        }
    }

    if ($teste > 0) {
        return true;
    } else {
        return false;
    }
}

function verificaPaginasConteudo($lista, $pagina_conteudo) {

    $arrayPaginas = explode(",", $lista);

    $teste = 0;
    for ($i = 0; $i < count($arrayPaginas); $i++) {
        if (trim(strtolower($pagina_conteudo)) == trim(strtolower($arrayPaginas[$i]))) {
            $teste++;
        }
    }

    if ($teste > 0) {
        return true;
    } else {
        return false;
    }
}

//Verifica se o sistema operacional atual é um dos que suportam flash
function verificaFlash() {
    $listaFlash = 'windows,macintosh,x11,mac_powerpc,'; //Linux
    //$listaSemFlash = 'iphone,ipad,android,symbian';
    $arrayFlash = explode(",", $listaFlash);

    $teste = 0;
    for ($i = 0; $i < count($arrayFlash); $i++) {
        $teste = $teste + strpos(strtolower($_SERVER['HTTP_USER_AGENT']), $arrayFlash[$i]);
    }

    if ($teste > 0) {
        return true;
    } else {
        return false;
    }
}

function verificaOS($nome) {

    $teste = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), $nome);
    if ($teste > 0) {
        return true;
    } else {
        return false;
    }
}

//Verifica se uma string está contida em outra
function contains($str, $content, $ignorecase = true) {
    if ($ignorecase) {
        $str = strtolower($str);
        $content = strtolower($content);
    }
    return strpos($content, $str) ? true : false;
}

//URL COMPLETA
//Busca a URL atual completa, com protocolo, servidor, arquivo atual e parâmetros passados
function urlAtualCompleta() {
    $protocolo = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false) ? 'http' : 'https';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $parametros = $_SERVER['QUERY_STRING'];

    if (strlen($parametros) == 0) {
        $parametros = 'qs=null';
    }
    $urlAtualCompleta = $protocolo . '://' . $host . $script . '?' . $parametros;

    return $urlAtualCompleta;
}

//NAVEGAÇÃO PAGINADOR
//Monta a navegação para um paginador
function navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina) {
    if ($total_registros > $limite) {

        echo '<ul class="paginacao">';

        $fimResultado = $pagina * $limite;
        $inicioResultado = $fimResultado - $limite + 1;

        if ($fimResultado > $total_registros) {
            $fimResultado = $total_registros;
        }

        if ($pagina != 1) {
            echo '<li><a href="' . $stringPagina . '&pag=1">In&iacute;cio</a></li>';
            echo '<li><a href="' . $stringPagina . '&pag=' . ($pagina - 1) . '">Página anterior</a></li>';
        } else {
            echo '<li><a class="off" href="javascript:void(0);">Início</a></li>';
            echo '<li><a class="off" href="javascript:void(0);">Página Anterior</a></li>';
        }

        echo '<li>( Resultados <b>' . $inicioResultado . '</b> a <b>' . $fimResultado . '</b> de <b>' . $total_registros . '</b>  )</li>';

        if ($pagina != $total_paginas) {
            echo '<li><a href="' . $stringPagina . '&pag=' . ($pagina + 1) . '">Próxima página</a></li>';
            echo '<li><a href="' . $stringPagina . '&pag=' . $total_paginas . '">Fim</a></li>';
        } else {
            echo '<li><a class="off" href="javascript:void(0);">Próxima Página</a></li>';
            echo '<li><a class="off" href="javascript:void(0);">Fim</a></li>';
        }
        echo '</ul>';
    }
}

//Monta a navegação para um paginador
function navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz) {
    if ($total_registros > $limite) {

        echo '<ul class="paginacao">';

        $fimResultado = $pagina * $limite;
        $inicioResultado = $fimResultado - $limite + 1;

        if ($fimResultado > $total_registros) {
            $fimResultado = $total_registros;
        }

        if ($pagina != 1) {
            //echo '<li><a href="' . $stringPagina . '/pag/1">Início</a></li>';
            echo '<li><a href="' . $stringPagina . '/pag/' . ($pagina - 1) . '">Página anterior</a></li>';
        } else {
            //echo '<li><a class="off" href="javascript:void(0);">Início</a></li>';
            echo '<li><a class="off" href="javascript:void(0);">Página anterior</a></li>';
        }

        echo '<li>( Resultados <b>' . $inicioResultado . '</b> a <b>' . $fimResultado . '</b> de <b>' . $total_registros . '</b> )</li> ';

        if ($pagina != $total_paginas) {
            echo '<li><a href="' . $stringPagina . '/pag/' . ($pagina + 1) . '">Próxima página</a></li>';
            //echo '<li><a href="' . $stringPagina . '/pag/' . $total_paginas . '">Fim</a></li>';
        } else {
            echo '<li><a class="off" href="javascript:void(0);">Próxima página</a></li>';
            //echo '<li><a class="off" href="javascript:void(0);">Fim</a></li>';
        }
        echo '</ul>';
    }
}

function nomeMes($mes) {

    if ($mes == 1) {
        $nomeMes = 'Janeiro';
    } else if ($mes == 2) {
        $nomeMes = 'Fevereiro';
    } else if ($mes == 3) {
        $nomeMes = 'Mar&ccedil;o';
    } else if ($mes == 4) {
        $nomeMes = 'Abril';
    } else if ($mes == 5) {
        $nomeMes = 'Maio';
    } else if ($mes == 6) {
        $nomeMes = 'Junho';
    } else if ($mes == 7) {
        $nomeMes = 'Julho';
    } else if ($mes == 8) {
        $nomeMes = 'Agosto';
    } else if ($mes == 9) {
        $nomeMes = 'Setembro';
    } else if ($mes == 10) {
        $nomeMes = 'Outubro';
    } else if ($mes == 11) {
        $nomeMes = 'Novembro';
    } else if ($mes == 12) {
        $nomeMes = 'Dezembro';
    }

    return $nomeMes;
}

//Retorna a data no formato 31 de Janeiro e 2011
function dataExtensoBR($data) {
    return date('d', strtotime($data)) . ' de ' . nomeMes(date('m', strtotime($data))) . ' de ' . date('Y', strtotime($data));
}

function dataBarrasBR($data) {
    return date('d/m/Y', strtotime($data));
}

function timeBR($data) {
    return date('H:i:s', strtotime($data));
}

function dataTimeBarrasBR($data) {
    return date('d/m/Y H:i:s', strtotime($data));
}

function dataTimeBarrasBR2($data) {
    $data1 = date('d/m/Y', strtotime($data));
    $data2 = date('H:i:s', strtotime($data));
    $dataFinal = $data1 . ' &agrave;s ' . $data2;
    return $dataFinal;
}

function nomeMesData($data) {
    return nomeMes(date('m', strtotime($data)));
}

function diaData($data) {
    return date('d', strtotime($data));
}

//QUERY OF QUERY
//Permite fazer uma consulta dentro de um array-resultado de uma consulta ao banco de dados
//Autor: Tom Muck
//Fonte: http://www.tom-muck.com/blog/index.cfm?newsid=37
/*


  Implementação:

  //Realiza a consulta
  $rs = mysql_query($strCategorias) or die(mysql_error());

  //Exemplo de filtragem dos IDS que quero extrair da consulta original
  $listaID = '';
  while ($resultado = mysql_fetch_array($rs)) {
  if(strlen($listaID) > 0){
  $listaID = $listaID. ',';
  }

  $listaID = $listaID. '' .$resultado["idCategoria"];
  }

  //Faz um select na consulta original filtrando pelo campo idCategoriaPai in ($listaID)
  $qryCategorias 	  = queryOfQuery($rs, "*", false, "idCategoriaPai", $listaID);
 */

function queryOfQuery($rs, // The recordset to query
        $fields = "*", // optional comma-separated list of fields to return, or * for all fields 
        $distinct = false, // optional true for distinct records
        $fieldToMatch = null, // optional database field name to match
        $valueToMatch = null) { // optional value to match in the field, as a comma-separated list
    $newRs = Array();
    $row = Array();
    $valueToMatch = explode(",", $valueToMatch);
    $matched = true;
    mysql_data_seek($rs, 0);
    if ($rs) {
        while ($row_rs = mysql_fetch_assoc($rs)) {
            if ($fields == "*") {
                if ($fieldToMatch != null) {
                    $matched = false;
                    if (is_integer(array_search($row_rs[$fieldToMatch], $valueToMatch))) {
                        $matched = true;
                    }
                }
                if ($matched)
                    $row = $row_rs;
            }else {
                $fieldsArray = explode(",", $fields);
                foreach ($fields as $field) {
                    if ($fieldToMatch != null) {
                        $matched = false;
                        if (is_integer(array_search($row_rs[$fieldToMatch], $valueToMatch))) {
                            $matched = true;
                        }
                    }
                    if ($matched)
                        $row[$field] = $row_rs[$field];
                }
            }
            if ($matched)
                array_push($newRs, $row);
        };
        if ($distinct) {
            sort($newRs);
            for ($i = count($newRs) - 1; $i > 0; $i--) {
                if ($newRs[$i] == $newRs[$i - 1])
                    unset($newRs[$i]);
            }
        }
    }
    mysql_data_seek($rs, 0);
    return $newRs;
}

function apagar($dir) {
    if (is_dir($dir)) {
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if (($file == ".") or ($file == "..")) {
                    continue;
                }
                if (is_dir($dir . $file)) {
                    apagar($dir . $file . "/");
                } else {
                    unlink($dir . $file);
                }
            }
        } else {
            print("nao foi possivel abrir o arquivo.");
            return false;
        }

// fecha a pasta aberta
        closedir($handle);

// apaga a pasta, que agora esta vazia
        rmdir($dir);
    } else {
        print("diretorio informado invalido");
        return false;
    }
}

/**
 * Cycles through each argument added
 * Based on Rails `cycle` method
 *
 * if last argument begins with ":"
 * then it will change the namespace
 * (allows multiple cycle calls within a loop)
 *
 * @param mixed $values infinite amount can be added
 * @return mixed
 * @author Baylor Rae'
 */
function cycle($first_value, $values = '*') {
    // keeps up with all counters
    static $count = array();

    // get all arguments passed
    $values = func_get_args();

    // set the default name to use
    $name = 'default';

    // check if last items begins with ":"
    $last_item = end($values);
    if (substr($last_item, 0, 1) === ':') {

        // change the name to the new one
        $name = substr($last_item, 1);

        // remove the last item from the array
        array_pop($values);
    }

    // make sure counter starts at zero for the name
    if (!isset($count[$name]))
        $count[$name] = 0;

    // get the current item for its index
    $index = $count[$name] % count($values);

    // update the count and return the value
    $count[$name]++;
    return $values[$index];
}

/**
 *
 * Redsize de imagem
 *
 * @param string $file_name - (Ex: nome.png)
 * @param array $options
 * -upload_dir - (Ex: $raiz.'arquivos/enviados/temp')
 * -upload_dir_thumbnail - (Ex: $raiz.'/arquivos/enviados/thumbnails/')
 * -max_width - (Ex: 80)
 * -max_height - (Ex: 80)
 *
 * @return boolean
 */
function create_scaled_image($file_name, $options) {
    $file_path = $options['upload_dir'] . $file_name;
    $new_file_path = $options['upload_dir_thumbnail'] . $file_name;
    list($img_width, $img_height) = @getimagesize($file_path);
    if (!$img_width || !$img_height) {
        return false;
    }
    $scale = min(
            $options['max_width'] / $img_width, $options['max_height'] / $img_height
    );
    if ($scale > 1) {
        $scale = 1;
    }
    $new_width = $img_width * $scale;
    $new_height = $img_height * $scale;
    $new_img = @imagecreatetruecolor($new_width, $new_height);
    switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
        case 'jpg':
        case 'jpeg':
            $src_img = @imagecreatefromjpeg($file_path);
            $write_image = 'imagejpeg';
            break;
        case 'gif':
            @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
            $src_img = @imagecreatefromgif($file_path);
            $write_image = 'imagegif';
            break;
        case 'png':
            @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
            @imagealphablending($new_img, false);
            @imagesavealpha($new_img, true);
            $src_img = @imagecreatefrompng($file_path);
            $write_image = 'imagepng';
            break;
        default:
            $src_img = $image_method = null;
    }
    $success = $src_img && @imagecopyresampled(
                    $new_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height
            ) && $write_image($new_img, $new_file_path);
    // Free up memory (imagedestroy does not delete files):
    @imagedestroy($src_img);
    @imagedestroy($new_img);
    return $success;
}
?>