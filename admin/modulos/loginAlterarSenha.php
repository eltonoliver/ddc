<p>&nbsp;</p>
<p>&nbsp;</p>
<?php 
	if($_REQUEST['hash']){
		$hash = $_REQUEST['hash'];
	}else{
		$hash = $_SESSION['hash'];
	}
	unset($_SESSION['hash']);
	if($_SESSION['resposta']):
	unset($_SESSION['resposta']);
?>
	<div class="msgBox"><p style="text-align:center;">Senha alterada com sucesso.</p></div>
    <p>&nbsp;</p>
    <p style="text-align:center;"><button type="button" onclick="window.location = '<?php echo $url_raiz; ?>admin/login';">Voltar</button></p>
<?php
	else:
?>
<p align="center"><strong>Digite as informações solicitadas nos campos abaixo.</strong></p>
<?php if($_SESSION['msgErro']): $msg = $_SESSION['msgErro']; unset($_SESSION['msgErro']);?>
<div class="msgBox">
    <p>
    <img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/>
    <?php echo $msg; ?></p>
</div>
<?php endif; ?>
<p>
<form id="form1" name="form1" method="post" action="<?php echo $url_raiz ?>admin/controller/act-login">
<table width="300" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
        <td width="39%" align="right">Nova senha:</td>
        <td width="61%"><input type="password" name="senha" size="20" maxlength="50" style="top:auto"/></td>
    </tr>
    <tr>
        <td width="39%" align="right">Confirmação:</td>
        <td width="61%"><input type="password" name="senhaConfirmacao" size="20" maxlength="50" style="top:auto"/></td>
    </tr>
    <tr>
        <td align="right">
            
        </td>
        <td align="left"><button type="submit" onClick="return validaFormularioSeguro(this.form);">Alterar</button></td>
    </tr>
</table>
<input type="hidden" name="acao" value="alterarSenha"/>
        <input type="hidden" name="hash" value="<?php echo $hash; ?>">
</form>
</p>
<?php endif; ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>