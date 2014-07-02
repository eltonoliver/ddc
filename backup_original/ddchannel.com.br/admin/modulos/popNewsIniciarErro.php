<?php include('header.php'); ?>
<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div id="msgErro">
 <div class="msgBox"><p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/>
 N&atilde;o foi posssível realizar o envio.</div>
 <p>&nbsp;</p>
    <p align="center">Verifique as configuraç&otilde;es do seu envio e tente novamente.</p>
    <p>&nbsp;</p>
    <p align="center">
    <button onClick="fechar();">Fechar</button>
    </p>
</div>
    
<script type="text/javascript">
	function fechar(){
		window.close();
	}
</script>
</body>
</hmtl>