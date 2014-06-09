<?php 
$key = $_REQUEST['key'];
if(strlen($key)){
	$qryGeral[0] = $_SESSION[$key]['dados'];
	unset($_SESSION[$key]);
}else{
	$qryGeral = $db->select(array('idGeral' => 1), 'tb_geral');
}

?>
<form name="formGeral" id="formGeral" action="controller/act-config-mail" method="post" enctype="multipart/form-data">
<h1>Configurações de Envio de E-mail</h1>
<br/>
<?php include 'sisMensagem.php'; ?>

<h2 class="separadador">Informações do e-mail</h2>
<div align="left">
        <button type="submit" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
</div>
<br/>
<table width="100%" border="0" align="center" class="tabelaForm" style="display:">

	<tr>
		<td width="15%" valign="top">SMTP?</td>
		<td valign="top">
			<select name="inSmtpMail">
			<option value="1" <?php if($qryGeral[0]["inSmtpMail"] == 1 || !strlen($qryGeral[0]["inSmtpMail"])){ echo 'selected'; } ?>>Sim</option>
			<option value="0" <?php if($qryGeral[0]["inSmtpMail"] == 0 && strlen($qryGeral[0]["inSmtpMail"])){ echo 'selected'; } ?>>N&atilde;o</option>
			</select>
		</td>
	</tr>
    <tr>
		<td width="15%">Host:</td>
		<td width="85%">
		<input name="nmHostMail" size="60" maxlength="100" value="<?php echo $qryGeral[0]["nmHostMail"]; ?>"/>
		</td>
	</tr>
    <tr>
		<td width="15%" valign="top">Autenticação?</td>
		<td valign="top">
			<select name="inAuthMail">
			<option value="1" <?php if($qryGeral[0]["inAuthMail"] == 1 || !strlen($qryGeral[0]["inAuthMail"])){ echo 'selected'; } ?>>Sim</option>
			<option value="0" <?php if($qryGeral[0]["inAuthMail"] == 0 && strlen($qryGeral[0]["inAuthMail"])){ echo 'selected'; } ?>>N&atilde;o</option>
			</select>
		</td>
	</tr>
    <tr>
		<td width="15%">Usuário:</td>
		<td width="85%">
		<input name="nmUserMail" size="60" maxlength="100" value="<?php echo $qryGeral[0]["nmUserMail"]; ?>"/>
		</td>
	</tr>
    <tr>
		<td width="15%">Senha:</td>
		<td width="85%">
		<input type="password" name="nmPassMail" size="60" maxlength="100" value="<?php echo $qryGeral[0]["nmPassMail"]; ?>"/>
		</td>
	</tr>
    <tr>
		<td width="15%">Porta:</td>
		<td width="85%">
		<input name="nrPortMail" size="60" maxlength="100" value="<?php echo $qryGeral[0]["nrPortMail"]; ?>"/>
		</td>
	</tr>
</table>
<br/>
<div align="left">
	<button type="submit" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
	<input type="hidden" name="acao" value="atualizar"/>
</div>
<br/>
</form>