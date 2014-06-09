<?php $qryGalerias = $db->query("SELECT * FROM tb_galeria WHERE 1=1 ORDER BY nmGaleria");?>
<table bgcolor="#ffffff" width="100%" border="0" cellspacing="2" cellpadding="0">
	<tr class="FonteTituloPOP">
	<td width="96%" style="color:#000;">Adicionar Galeria</td>
	<td width="2%"><img src="<?php echo $url_raiz; ?>admin/js/modalMessage/botFechar.png" onClick="closeMessage();" title="Fechar" onMouseOver="javascript: this.style.cursor = 'pointer';"></td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="5" cellpadding="0" id="tabForm">
	<tr>
	<td align="left" class="FonteGeral">
		<table width="100%" border="0" cellpadding="1" cellspacing="0">
		<tr>
			<td colspan="2" align="left" class="formLabel"><br/><span class="destaque">* </span>Nome</td>
		</tr>
		<tr>
			<td width="54%" align="left">
			<select name="idGaleriaSelect" id="idGaleriaSelect" style="top:auto; width:100%;">
				<option value="">[Selecione]</option>
				<?php for($i=0; $i<count($qryGalerias); $i++){ ?>
				<option value="<?php echo $qryGalerias[$i]["idGaleria"]; ?>"><?php echo utf8_encode($qryGalerias[$i]["nmGaleria"]); ?></option>
				<?php } ?>
			</select>
			</td>
			<td width="46%" align="center">
			<button name="submit" id="submit" onclick="adicionarNovoGaleriaConteudo();">Adicionar</button>
			</td>
		</tr>
	</table>
	</td>
	</tr>
</table>
    
<script type="text/javascript">	
	var idConteudoIdGaleria = '<?php echo $_GET["idItem"];?>';
	function adicionarNovoGaleriaConteudo(){
		var idGaleria = $('#idGaleriaSelect :selected').val();
		if(!document.getElementById('galeria_'+idGaleria)){
			var html = '<div style="float:left; padding:5px;" id="galeria_'+idGaleria+'">'+
	        			'<img style="cursor:pointer" src="img/erro.gif" onClick="excluirGaleria(\'galeria_'+idGaleria+'\');">'+
	        			'&nbsp;'+$('#idGaleriaSelect :selected').text()+'<input type="hidden" name="idGaleria[]" value="'+idGaleria+'"></div>';
	        
			$('#'+idConteudoIdGaleria+'Add_'+idConteudoIdGaleria).append(html);
		}
		
		closeMessage();
	}
</script>