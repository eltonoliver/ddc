<?php

	function dataFormatoBanco(){
		
		date_default_timezone_set("America/Halifax");
	
		$string = date("d/m/Y H:i:s");
		
		$ano = substr($string,6,4);
		$mes = substr($string,3,2);
		$dia = substr($string,0,2);
		
		
		$hora = substr($string,11,2);
		$minuto = substr($string,14,2);
		$segundo = substr($string,17,2);
		
		$dataCompleta = $ano.'-'.$mes.'-'.$dia.' '.$hora.':'.$minuto.':'.$segundo;
		
		$dateConvertida = strtotime($dataCompleta);
		$data = date("Y-m-d H:i:s",$dateConvertida);
		
		return $data;
	}
	
	function dataBarrasBR($data){
		return	date('d/m/Y', strtotime($data));
	}
	
	function dataTimeFeed(){
		//Wed, 02 Oct 2002 08:00:00 EST
		$data = date('D, d M Y H:i:s T');
		$data = substr($data,0,29);
		
		return $data;
	}
	
	function encurtaLocal($url)
	{
		$linkAPI = 'http://ugago.it/apiGerar.php?url=%s';
		//define('novaMinURL',$linkAPI);
		$minURL = $linkAPI;
		
		//define('novaMinURL', 'http://tinyurl.com/api-create.php?url=%s');
		$requestURL = sprintf($minURL, $url);
	
		$curl = (bool) function_exists('curl_init');
		$allow_url = (bool) ini_get('allow_url_fopen');
	
		if ($curl AND !$allow_url) 
		{
			$ch = curl_init($requestURL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$resultado = curl_exec($ch);
			curl_close($ch);
		} 
		else if ($allow_url) 
		{
			$resultado = file_get_contents($requestURL);
			if (!$resultado) 
			{
				$handle = @fopen($requestURL, "r");
				$resultado = '';
				if ($handle) while (!feof($handle)) $resultado .= fgets($handle, 4096);
			}
		} 
		else 
		{
			trigger_error('tinyURL: Não é possível usar o cURL nem URLs com fopen!', E_USER_ERROR);
			$resultado = $url;
		}
		return ((isset($resultado) AND !empty($resultado)) ? trim($resultado) : $url);
	}


?>