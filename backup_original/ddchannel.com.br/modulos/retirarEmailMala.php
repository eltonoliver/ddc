<?
$remover = (int) $_GET['remover'];
if (!$remover) {
    ?>
    <h1 align="center">Remoção de e-mail</h1>
    <p align="center">Deseja realmente remover o seu e-mail para não mais receber a mala?</p>
    <p align="center">
        <a href="retirar-email-mala/email/<?php echo $_GET['email'] ?>/remover/1">Sim</a>
    </p>
    <?
} else {
    $cod = (int) base64_decode($_GET['email']);
    $removerEmail = $db->update("update tb_news_emails set inAtivo=0 where idEmail=$cod");
    if ($removerEmail) {
        $mensagem = 'Exclusão realizada com sucesso!';
    } else {
        $mensagem = 'Não foi possível remover seu e-mail, favor tente novamente.';
    }
    ?>
    <h1 align="center">Remoção de e-mail</h1>
    <p align="center"><?php echo $mensagem ?></p>
    <?
}
?>
