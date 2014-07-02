<p>&nbsp;</p>
<p>&nbsp;</p>
<?php if($_SESSION['msgErro']): $msg = $_SESSION['msgErro']; unset($_SESSION['msgErro']);?>
<div class="msgBox">
    <p>
    <img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/>
    <?php echo $msg; ?></p>
</div>
<?php endif; ?>
<p>&nbsp;</p>
<form id="form1" name="form1" method="post" action="<?php echo $urls_raiz ?>admin/controller/act-login" autocomplete="OFF">
<table width="300" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
        <td width="39%" align="right">Login:</td>
        <td width="61%"><input type="text" name="login" id="login" size="22" maxlength="50" style="top:auto"/></td>
    </tr>
    <tr>
        <td width="39%" align="right">Senha:</td>
        <td width="61%"><input type="password" name="senha" id="senha" size="22" maxlength="50" style="top:auto"/></td>
    </tr>
    <tr>
        <td width="39%" align="right">Cod. Segurança:</td>
        <td width="61%">
            <img src="<?php echo $urls_raiz;?>lib/captcha/CaptchaSecurityImages.php" alt="CAPTCHA" width="129" height="40"/>
            <br/>
            <input id="security_code" name="security_code" type="text" size="22" style="top:auto"/>
        </td>
    </tr>
    <tr>
        <td align="right">
            
        </td>
        <td align="left"><button type="submit" name="btAcessar" id="btAcessar" onClick="return validaFormularioSeguro(this.form);" class="btMaior" style="width: 128px;">Acessar</button></td>
    </tr>
    <tr>
        <td align="right"></td>
        <td align="left" style="padding-left:16px;">
        <a href="<?php echo $urls_raiz;?>admin/login-esqueceu-senha"><small>[Esqueci minha senha!]</small></a></td>
    </tr>
</table>
<input type="hidden" name="acao" value="logando">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>