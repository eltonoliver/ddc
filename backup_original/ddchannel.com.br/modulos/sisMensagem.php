<?php
    $msgOk = $_SESSION['msgOk'];
    unset($_SESSION['msgOk']);
    $msgErro = $_SESSION['msgErro'];
    unset($_SESSION['msgErro']);
?>

<?php if ($msgOk) { ?>
<div class="msgBox" id="msgok">
        <p>
            <img src="<?php echo $url_raiz; ?>admin/img/alertaOK.gif" />
            <?php echo $msgOk; ?>
        </p>
    </div>
<?php } elseif ($msgErro) { ?>
<div class="msgBox" id="msgerror">
        <p>
            <img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" />
            <?php echo $msgErro; ?>
        </p>
    </div>
<?php } ?>

<?php
    $erros = $_SESSION['errosMsgArray'];
    unset($_SESSION['errosMsgArray']);
    if ($erros):
?>

    <ul class="listaErros">
        <?php foreach ($erros as $e): ?>
            <li><?php echo $e; ?></li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

<script type="text/javascript">
    $().ready(function(e) {
        setTimeout(function() {
            $('div.msgBox').animate({opacity: 0}, 'medium', function() {
                $(this).hide('medium');
            });

        }, 10000);
    });
</script>
