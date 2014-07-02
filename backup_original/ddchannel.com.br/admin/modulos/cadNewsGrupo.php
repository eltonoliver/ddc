<?php 
$key = $_REQUEST['key'];
if(strlen($key)){
	$qryGrupo[0] = $_SESSION[$key]['dados'];
	unset($_SESSION[$key]);
}else{
	$qryGrupo = $_REQUEST["id"]?($db->select(array('idGrupo' => $_REQUEST["id"]), 'tb_news_grupo')):null;
}
?>
<form name="formGeral" id="formGeral" action="controller/act-news" method="post" enctype="multipart/form-data">
<h1>Cadastro de Grupo</h1>
<br/>
<?php include 'sisMensagem.php'; ?>

<h2 class="separadador">Dados do Grupo</h2>
<div align="left">
    <?php if(!strlen($_REQUEST["id"])){ ?>
        <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
    <?php } else {?>
        <button type="submit" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" onClick="excluirItem(<?php echo $_REQUEST["id"]; ?>,'controller/act-news','excluirGrupo','id')">Excluir</button>
        <button type="button" onClick="location.href='cad-news-grupo';">Cadastrar Novo</button>
    <?php } ?>
    <button type="button" name="btVoltar" id="btVoltar"  onclick="location.href='menu-news-grupo';">Voltar</button>
</div>
<br/>
<table width="100%" border="0" align="center" class="tabelaForm" style="display:">
	<tr>
		<td width="15%">Nome:</td>
		<td width="85%">
		<input name="nmGrupo" id="nmGrupo" size="60" maxlength="100" style="top:auto" value="<?php echo $qryGrupo[0]["nmGrupo"]; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="15%" valign="top">Ativar?</td>
		<td valign="top">
			<select name="inAtivo" id="inAtivo"  style="top:auto">
			<option value="1" <?php if($qryGrupo[0]["inAtivo"] == 1 || !strlen($qryGrupo[0]["inAtivo"])){ echo 'selected'; } ?>>Sim</option>
			<option value="0" <?php if($qryGrupo[0]["inAtivo"] == 0 && strlen($qryGrupo[0]["inAtivo"])){ echo 'selected'; } ?>>N&atilde;o</option>
			</select>
		</td>
	</tr>
</table>
<br/>
<div align="left">
    <?php if(!strlen($_REQUEST["id"])){ ?>
        <button type="submit" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
        <input type="hidden" name="acao" value="cadastrarGrupo" />
    <?php } else {?>
        <button type="submit" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" onClick="excluirItem(<?php echo $_REQUEST["id"]; ?>,'controller/act-news','excluirGrupo','id')">Excluir</button>
        <button type="button" onClick="location.href='cad-news-grupo';">Cadastrar Novo</button>
        <input type="hidden" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
        <input type="hidden" name="acao" value="atualizarGrupo"/>
    <?php } ?>
    <button type="button" onclick="location.href='menu-news-grupo';">Voltar</button>
</div>
<br/>
</form>