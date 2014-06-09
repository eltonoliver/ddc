<table bgcolor="#ffffff" width="100%" border="0" cellspacing="2" cellpadding="0" >
  <tr class="FonteTituloPOP">
    <td width="96%" style="color:#000000;">Excluir Arquivo</td>
    <td width="2%"><img src="<?php echo $url_raiz; ?>admin/js/modalMessage/botFechar.png" onClick="closeMessage();" title="Fechar" onMouseOver="javascript: this.style.cursor = 'pointer';"></td>
  </tr>
</table>

<div id="loading"><p align="center" style="margin-top:40px;">
	Deseja excluir esse item?
</p>

<p align="center"><strong style="cursor:pointer;" onclick="excluirArquivoLista();">Sim</strong>&nbsp;&nbsp;&nbsp;<strong style="cursor:pointer;" onclick="closeMessage();">N&#227;o</strong></p>
</div>
<!--- Inicio Tabela Esquerda--->    
<script type="text/javascript">

	var id = '<?php echo $_REQUEST['idArquivo']; ?>';
	function excluirArquivoLista(){
			closeMessage();
			$('#arquivoReg_' + id).animate({opacity:0}, 'slow', function(){
			$('#arquivoReg_' + id).css('display', 'none');
			$('#arquivoReg_' + id).remove();
			
			var classLinha = 'tbNormal';
			var totalLinas = $('#tbListaArquivos').attr('rows').length;
			
			jQuery.each($('#tbListaArquivos').attr('rows'),(function(index, value) {
				if(index){
					$(this).removeClass(classLinha);
					$(this).removeClass(classLinha+'Alt');
					$(this).addClass(classLinha+(((index+1)%2==0)?'Alt':''));
				}
			}));

			ajaxRequest.sendJson('controller/act-arquivos?acao=excluirArquivo&idArquivo='+id, function(){				
			});
		})
	}
</script>