<p>&nbsp;</p>
<p>&nbsp;</p>
<?php 
	$resposta = $_SESSION['resposta'];
	if($resposta):
		unset($_SESSION['resposta']);
		if($resposta == 1):
?>
		<div class="msgBox">
		<p style="text-align:center;">Erro no envio de sua solicitação, favor tente novamente.</p>
        </div>
<?php 
		elseif($resposta == 2):
?>
		<div class="msgBox">
		<p style="text-align:center;">Solicitação enviada com sucesso,<br> após 2hs essa solicitação não terá mais validade.</p>
        </div>
<?php 
		endif;
?>
		<p>&nbsp;</p>
		<p style="text-align:center;"><button type="button" onclick="window.location = '<?php echo $url_raiz; ?>admin/login';">Voltar</button></p>
<?php
	else:
?>
<p style="text-align:center;"><strong>Informa seu e-mail cadastrado para receber as informações para recuperação da senha.</strong></p>
<?php if($_SESSION['msgErro']): $msg = $_SESSION['msgErro']; unset($_SESSION['msgErro']);?>
<div class="msgBox">
    <p>
    <img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/>
    <?php echo $msg; ?></p>
</div>
<?php endif; ?>
<p>
<form id="form1" name="form1" method="post" action="<?php echo $url_raiz ?>admin/controller/act-login">
<table width="400" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
        <td width="39%" align="right">E-mail:</td>
        <td width="61%"><input type="text" name="email" size="50" maxlength="50" style="top:auto"/></td>
    </tr>
    <tr>
        <td align="right">
        </td>
        <td align="left">
            <button type="submit" onClick="return validaFormularioSeguro(this.form);">Solicitar</button>
            <button type="button" onclick="javascript: location.href='<?php echo $url_raiz ?>admin/login';">Voltar</button>
        </td>
    </tr>
</table>
<input type="hidden" name="acao" value="esqueciSenha"/>
</form>
</p>
<?php endif; ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>