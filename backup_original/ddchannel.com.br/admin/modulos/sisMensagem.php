<?php
$msg = $_SESSION['msg']; unset($_SESSION['msg']);
$msgErro = $_SESSION['msgErro']; unset($_SESSION['msgErro']);
?>
<?php if($msg){ ?>
<div class="msgBox">
	<p>
		<img src="<?php echo $url_raiz; ?>admin/img/alertaOK.gif" border="0"
			align="absbottom" />
		<?php echo $msg ?>
	</p>
</div>
<?php } elseif($msgErro){?>
<div class="msgBox">
	<p>
		<img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"
			align="absbottom" />
		<?php echo $msgErro; ?>
	</p>
</div>
<?php } ?>