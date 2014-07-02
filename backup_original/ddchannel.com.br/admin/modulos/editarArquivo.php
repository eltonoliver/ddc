<?php 
	header('Content-Type: text/html; charset=utf-8');
	$idArquivo = $_REQUEST['idArquivo'];
	$qryArquivo = $db->query('SELECT * FROM tb_arquivo WHERE idArquivo = ' . $db->clean($idArquivo));
	if($qryArquivo){
		$qryArquivo = current($qryArquivo);
	}

?>
<table bgcolor="#ffffff" width="100%" border="0" cellspacing="2" cellpadding="0" >
  <tr class="FonteTituloPOP">
    <td width="96%" style="color:#000;">Editar Arquivo</td>
    <td width="2%"><img src="<?php echo $url_raiz; ?>admin/js/modalMessage/botFechar.png" onClick="closeMessage();" title="Fechar" onMouseOver="javascript: this.style.cursor = 'pointer';"></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="5" cellpadding="0" id="tabFormEditar">
	<tr>
		<td align="left" class="FonteGeral">
		<form id="formEnviarArquivo" name="formEnviarArquivo" method="post" action="<?php echo $url_raiz ?>admin/controller/act-arquivos" onsubmit="return false;">
		  <table width="100%" border="0" cellpadding="1" cellspacing="0">
		  <tr>
		    <td align="left" class="formLabel"><table width="100%" border="0" cellpadding="1" cellspacing="0">
		      <tr>
		        <td colspan="2" align="left" class="formLabel"><div style="color:#C83C12; display:none;" id="msgErroTituloArquivo">Campo obrigat&#243;rio</div>
		          <span class="destaque">* </span>Arquivo / Legenda </td>
		        </tr>
		      <tr>
		        <td colspan="2" align="left"><input type="text" name="nmTituloArquivo" id="nmTituloArquivo22" value="<?php echo utf8_encode($qryArquivo['nmTituloArquivo']); ?>" style="width: 280px;"/></td>
		        </tr>
		      <tr>
		        <td align="right" valign="middle" class="formLabel">&nbsp;</td>
		        <td valign="middle">&nbsp;</td>
		        </tr>
		      <tr>
		        <td style="width:235px;" align="left" valign="middle" class="formLabel">Dispon&iacute;vel para?</td>
		        <td valign="middle"><select name="inVisibilidade" style="width: 85px;">
		          <option value="1" <?php if($qryArquivo['inVisibilidade'] == 1)echo 'selected'; ?>>P&uacute;blico</option>
		          <option value="0" <?php if($qryArquivo['inVisibilidade'] == 0)echo 'selected'; ?>>Private</option>
		          </select></td>
		        </tr>
		      <tr>
		        <td align="left" valign="middle" class="formLabel">Publicar?</td>
		        <td valign="middle"><select name="inPublicar" style="width: 85px;">
		          <option value="1" <?php if($qryArquivo['inPublicar'] == 1)echo 'selected'; ?>>Sim</option>
		          <option value="0" <?php if($qryArquivo['inPublicar'] == 0)echo 'selected'; ?>>N&atilde;o</option>
		          </select></td>
		        </tr>
		      <tr>
		        <td align="left" valign="middle" class="formLabel">Permitir Impress&atilde;o?</td>
		        <td valign="middle"><select name="inImpressao" style="width: 85px;">
		          <option value="1" <?php if($qryArquivo['inImpressao'] == 1)echo 'selected'; ?>>Sim</option>
		          <option value="0" <?php if($qryArquivo['inImpressao'] == 0)echo 'selected'; ?>>N&atilde;o</option>
		          </select></td>
		        </tr>
		      <tr>
		        <td colspan="2" align="left" class="formLabel"><br/>
		          Descri&#231;&#227;o</td>
		        </tr>
		      <tr>
		        <td colspan="2" width="54%" align="left"><textarea name="nmDescricaoArquivo" id="nmDescricaoArquivo" rows="8" cols="51"><?php echo utf8_encode($qryArquivo['nmDescricaoArquivo']); ?></textarea></td>
		        </tr>
	        </table></td>
		  </tr>
		  </table>
      <br/>
			<div align="left">
				<input type="hidden" name="idArquivo" id="idArquivo" value="<?php echo $qryArquivo["idArquivo"]; ?>">
				<input type="hidden" name="acao" id="acao" value="editarArquivo">
				<button name="submit" id="submit" onclick="enviarDados();">Enviar</button>
			</div>
		</form>
		</td>
	</tr>
</table>
<div id="loading" style="display:none;"><p align="center" style="margin-top:144px;"><img src='img/ajax-loader.gif'></p></div>
<!--- Inicio Tabela Esquerda--->    
<script type="text/javascript">

	var id = '<?php echo $qryArquivo["idArquivo"]; ?>';
	function enviarDados(){
		if(!$('#nmTituloArquivo22').val().length){
			$('#msgErroTituloArquivo').show('fast');
		}else{
			$('#tabFormEditar').css('display', 'none');
			$('#loading').css('display', 'inline');
			ajaxRequest.sendForm('formEnviarArquivo', null, function(d){
				if(d.status){
					$('#nmTituloArquivo_' + id).html(d.nmTituloArquivo);
					$('#nmDescricaoArquivo_' + id).html(d.nmDescricaoArquivo);
				}else{
					alert('erro');
				}
				closeMessage();
			}, 'json');
		}
	}
</script>