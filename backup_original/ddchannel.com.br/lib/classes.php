<?php

//Classes 
require_once($raiz . "/class/mysql.php");
require_once($raiz . "/class/erros.php");
require_once($raiz . "/class/dBug.php");
require_once($raiz . "/class/maps/googlemap.php");
require_once($raiz . "/class/upload.class.php");
require_once($raiz . "/class/upload.php");
require_once($raiz . "/class/diretorios.php");
require_once($raiz . "/class/auth.php");
require_once($raiz . "/class/arrayUtil.php");
require_once($raiz . "/class/moveFile.php");

$db = new mysqlClass($mysql_address, $mysql_username, $mysql_password, $mysql_database);
$up = new upload();
$dir = new diretorios();
$auth = new auth($db);
$aUtil = new ArrayUtil();
$map = new GOOGLE_API_3();
$map2 = new GOOGLE_API_3();
/*
//Facebook álbum
define('PAGE_ID', '393760197354749');
define('APP_ID', '456124044421449');
define('APP_SECRET', '549c01aca0b6f79a88bd961daa5aa325');
include($raiz . "class/fbAlbum/phpcUrl.php");
*/

$geralConfig = $db->query('SELECT YEAR(A.dtLancamento) as anoLancamento,A.* FROM tb_geral A ORDER BY A.idGeral DESC LIMIT  1');

require_once('phpMailer/class.phpmailer.php');
// Inicia a classe PHPMailer
$mail = new PHPMailer();
// Define os dados do servidor e tipo de conexao
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->SMTPSecure = 'ssl';
if ($geralConfig[0]['inSmtpMail']) {
    $mail->IsSMTP(); // Define que a mensagem ser� SMTP
}
$mail->Host = $geralConfig[0]['nmHostMail']; // Endereço do servidor SMTP
if ($geralConfig[0]['inAuthMail']) {
    $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
}
$mail->Username = $geralConfig[0]['nmUserMail']; // Usuário do servidor SMTP
$mail->Password = $geralConfig[0]['nmPassMail']; // Senha do servidor SMTP
$mail->Port = $geralConfig[0]['nrPortMail'];
//Redes Sociais
$redesSociais = $db->query('SELECT * FROM tb_rede_social ORDER BY idRedeSocial');
?>