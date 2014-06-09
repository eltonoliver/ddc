<?php

$meses = array();
$meses[0]["nomeMes"] = "Janeiro";
$meses[1]["nomeMes"] = "Fevereiro";
$meses[2]["nomeMes"] = "Março";
$meses[3]["nomeMes"] = "Abril";
$meses[4]["nomeMes"] = "Maio";
$meses[5]["nomeMes"] = "Junho";
$meses[6]["nomeMes"] = "Julho";
$meses[7]["nomeMes"] = "Agosto";
$meses[8]["nomeMes"] = "Setembro";
$meses[9]["nomeMes"] = "Outubro";
$meses[10]["nomeMes"] = "Novembro";
$meses[11]["nomeMes"] = "Dezembro";

$meses[0]["siglaMes"] = "Jan";
$meses[1]["siglaMes"] = "Fev";
$meses[2]["siglaMes"] = "Mar";
$meses[3]["siglaMes"] = "Abr";
$meses[4]["siglaMes"] = "Mai";
$meses[5]["siglaMes"] = "Jun";
$meses[6]["siglaMes"] = "Jul";
$meses[7]["siglaMes"] = "Ago";
$meses[8]["siglaMes"] = "Set";
$meses[9]["siglaMes"] = "Out";
$meses[10]["siglaMes"] = "Nov";
$meses[11]["siglaMes"] = "Dez";

$meses[0]["numeroMes"] = 01;
$meses[1]["numeroMes"] = 02;
$meses[2]["numeroMes"] = 03;
$meses[3]["numeroMes"] = 04;
$meses[4]["numeroMes"] = 05;
$meses[5]["numeroMes"] = 06;
$meses[6]["numeroMes"] = 07;
$meses[7]["numeroMes"] = 08;
$meses[8]["numeroMes"] = 09;
$meses[9]["numeroMes"] = 10;
$meses[10]["numeroMes"] = 11;
$meses[11]["numeroMes"] = 12;

$estados = array();
$estados[0][0] = "AC";
$estados[0][1] = "Acre";
$estados[1][0] = "AL";
$estados[1][1] = "Alagoas";
$estados[2][0] = "AP";
$estados[2][1] = "Amapá";
$estados[3][0] = "AM";
$estados[3][1] = "Amazonas";
$estados[4][0] = "BA";
$estados[4][1] = "Bahia";
$estados[5][0] = "CE";
$estados[5][1] = "Ceará";
$estados[6][0] = "DF";
$estados[6][1] = "Distrito Federal";
$estados[7][0] = "ES";
$estados[7][1] = "Espírito Santo";
$estados[8][0] = "GO";
$estados[8][1] = "Goiás";
$estados[9][0] = "MA";
$estados[9][1] = "Maranhão";
$estados[10][0] = "MG";
$estados[10][1] = "Minas Gerais";
$estados[11][0] = "MT";
$estados[11][1] = "Mato Grosso";
$estados[12][0] = "MS";
$estados[12][1] = "Mato Grosso do Sul";
$estados[13][0] = "PA";
$estados[13][1] = "Pará";
$estados[14][0] = "PB";
$estados[14][1] = "Paraíba";
$estados[15][0] = "PR";
$estados[15][1] = "Paraná";
$estados[16][0] = "PE";
$estados[16][1] = "Pernambuco";
$estados[17][0] = "PI";
$estados[17][1] = "Piauí";
$estados[18][0] = "RJ";
$estados[18][1] = "Rio de Janeiro";
$estados[19][0] = "RN";
$estados[19][1] = "Rio Grande do Norte";
$estados[20][0] = "RS";
$estados[20][1] = "Rio Grande do Sul";
$estados[21][0] = "RO";
$estados[21][1] = "Rondônia";
$estados[22][0] = "RR";
$estados[22][1] = "Roraima";
$estados[23][0] = "SC";
$estados[23][1] = "Santa Catarina";
$estados[24][0] = "SP";
$estados[24][1] = "São Paulo";
$estados[25][0] = "SE";
$estados[25][1] = "Sergipe";
$estados[26][0] = "TO";
$estados[26][1] = "Tocantins";

//date('w');
$diasSemana = array();
$diasSemana[0]["pt"] = "Domingo";
$diasSemana[1]["pt"] = "Segunda-feira";
$diasSemana[2]["pt"] = "Terça-feira";
$diasSemana[3]["pt"] = "Quarta-feira";
$diasSemana[4]["pt"] = "Quinta-feira";
$diasSemana[5]["pt"] = "Sexta-feira";
$diasSemana[6]["pt"] = "Sábado";
$diasSemana[0]["en"] = "Sunday";
$diasSemana[1]["en"] = "Monday";
$diasSemana[2]["en"] = "Tuesday";
$diasSemana[3]["en"] = "Wednesday";
$diasSemana[4]["en"] = "Thursday";
$diasSemana[5]["en"] = "Friday";
$diasSemana[6]["en"] = "Saturday";

function valorMoedaIngles($valor) {
    //formata para $ 5555.22	
    $valor = str_replace(".", "", $valor); //6555,99
    $valor = str_replace(",", ".", $valor); //6555.99

    return($valor);
}

//Gera uma string randomicamente
function gerarStringRandom($tamanho) {
    $length = $tamanho;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = "";

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}

/* * ********************
  MOEDA BR
 * ********************* */

function valorMoedaBR($valor) {

    $numero = $valor;

    //formata para R$ 6.555,99
    $valor = number_format($numero, 2, ',', '.');

    return($valor);
}

function formataData01($data) {
    $aux = explode("-", $data);
    $ano = $aux[0];
    $mes = $aux[1];
    $dia = $aux[2];
    $dataFormatada = $dia . "/" . $mes . "/" . $ano;
    return($dataFormatada);
}

function formataData02($data) {
    $aux = explode("/", $data);

    $dia = $aux[0];
    $mes = $aux[1];
    $ano = $aux[2];

    $dataFormatada = $ano . "-" . $dia . "-" . $mes;

    return($dataFormatada);
}

function tratastr($string) {
    $string = mysql_real_escape_string(strip_tags(trim($string)));
    return $string;
}

?>