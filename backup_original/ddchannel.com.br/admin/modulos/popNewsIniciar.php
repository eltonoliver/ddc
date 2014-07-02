<?php include('header.php'); $tempoParaVerificar = 0.001;//tempo em horas?>
<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div id="mensagem">
	<div class="msgBox"><p><img src="<?php echo $url_raiz; ?>admin/img/alertaOK.gif" border="0"align="absbottom"/> 
    Deseja realmente iniciar o envio da Newsletter?
    </p></div>
    <p>&nbsp;</p>
    <p align="center">Atenç&atilde;o, após inicar o envio favor n&atilde;o feche essa tela até o término da newsletter</p>
    <p>&nbsp;</p>
    <p align="center">
    <button onClick="iniciar();">Iniciar</button>
    </p>
</div>
<div id="carregando" style="display:none;">
	<p align="center"><img src="<?php  echo $url_raiz;?>admin/img/ajax-loader.gif" border="0"/></p>
    <p align="center">Aguarde o envio, favor n&atilde;o feche esta tela.</p>
</div>
<div id="msgErro" style="display:none;">
 <div class="msgBox"><p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/>
 N&atilde;o foi posssível realizar o envio.</div>
 <p>&nbsp;</p>
    <p align="center">Verifique as configuraç&otilde;es do seu envio e tente novamente.</p>
    <p>&nbsp;</p>
    <p align="center">
    <button onClick="fechar();">Fechar</button>
    </p>
</div>

<div id="mensagemFim" style="display:none;">
	<div class="msgBox"><p><img src="<?php echo $url_raiz; ?>admin/img/alertaOK.gif" border="0"align="absbottom"/> 
    Envio finalizado com sucesso!
    </p></div>
    <p>&nbsp;</p>
    <p align="center">
    <button onClick="window.opener.location.reload(); fechar();">Fechar</button>
    </p>
</div>
    
<script type="text/javascript">
	var idEnvio = '<?php echo $_REQUEST['idEnvio']; ?>';
	var tempo = <?php echo $tempoParaVerificar*60*60*1000;//passando as horas para milissegundo ?>;
	var id = null;
	var opacityCarregando = 0;
	var continua = true;
	function iniciar(){
		if(id){
			clearTimeout(id);
		}
		id = setTimeout('iniciar()', tempo);
		
		if(!continua){
			return 0;
		}
		continua = false;
		
		$('#mensagem').animate({'opacity':0}, 'medium', function(){
			$(this).css('display', 'none');
			opacityCarregando = id?1:0;
			$('#carregando').css('opacity', opacityCarregando).css('display', 'inline').animate({'opacity':1}, 'medium', function(){
					$.ajax({
						url 		: '<?php echo $url_raiz; ?>admin/controller/act-news',
						type 		: 'post',
						data 		: {'id':idEnvio, 'acao':'comecarEnvio'},
						dataType 	: 'json',
						success 	: function(data){
							switch(data.status){
								//ocorreu algum erro
								case '0':
									clearTimeout(id);
									$('#carregando').animate({'opacity':0}, 'medium', function(){
										$(this).css('display', 'none');
										$('#msgErro').css('opacity', 0).css('display', 'inline').animate({'opacity':1}, 'medium');
									});
								break;
								//concluio o envio
								case '1':
									clearTimeout(id);
									$('#carregando').animate({'opacity':0}, 'medium', function(){
										$(this).css('display', 'none');
										$('#mensagemFim').css('opacity', 0).css('display', 'inline').animate({'opacity':1}, 'medium');
									});
								break;
								
								//continuar o envio
								default: 
									continua = true;
								break;
							}
						}
					});
			});
		});
	}
	
	function fechar(){
		window.close();
	}
	
	//jQuery(window).bind("beforeunload", function(){return confirm("Fechando esta janela o envio será interrompido. \nVoce poderá reiniciá-lo novamente depois. \nDeseja realmente fazer isso?", "Interromper o envio");})
</script>
</body>
</hmtl>