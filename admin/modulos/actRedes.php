<?php
	
	include('header.php');
	$validar = $raiz.'lib/validaAct.php';
	include($validar);
	
	$query = "
	
		UPDATE	tb_geral 
		
		SET		nmLinkFacebook		= '".$_POST["nmLinkFacebook"]."',
				nmLinkTwitter		= '".$_POST["nmLinkTwitter"]."',
				nmLinkYouTube		= '".$_POST["nmLinkYouTube"]."',
				nmCanalYouTube		= '".$_POST["nmCanalYouTube"]."',
				nmLinkFlickr		= '".$_POST["nmLinkFlickr"]."',
				nmFlickrID			= '".$_POST["nmFlickrID"]."',
				nmLinkGooglePlus	= '".$_POST["nmLinkGooglePlus"]."'
				
		WHERE	idGeral					= ".$_POST["idGeral"]."
		
	";
	
	//Atualiza a tabela com os dados do formulário
	$db->update($query);
	
	//Retorno
	echo "
		<script type='text/javascript'>
			location.href='dadosRedes.php?atualizado';
		</script>
	";

?>