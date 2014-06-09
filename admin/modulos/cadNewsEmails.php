<?php 
$key = $_REQUEST['key'];
if(strlen($key)){
	$qryEmail[0] = $_SESSION[$key]['dados'];
	$grupos = $_SESSION[$key]['dados']['grupos'];
	unset($_SESSION[$key]);
}else{
	if(($_REQUEST["id"]) && !is_numeric($_REQUEST["id"])){
		throw new Exception('Erro no códgio.');
	}
	$qryEmail = $_REQUEST["id"]?($db->select(array('idEmail' => $_REQUEST["id"]), 'tb_news_emails')):null;
	$grupos = $_REQUEST["id"]?($db->select(array('idEmail' => $_REQUEST["id"]), 'tb_news_grupo_emails')):null;
	$grupos = $grupos?array_map('next', $grupos):null;
}


$qryGrupos = $db->select(array('orderby' => 'nmGrupo'), 'tb_news_grupo');
?>
<form name="formGeral" id="formGeral" action="controller/act-news" method="post" enctype="multipart/form-data">
<h1>Cadastro de E-mail</h1>
<br/>
<?php include 'sisMensagem.php'; ?>
<h2 class="separadador">Dados do E-mail</h2>
<div align="left">
    <?php if(!strlen($_REQUEST["id"])){ ?>
        <button type="submit"  onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
    <?php } else {?>
        <button type="submit" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" onClick="excluirItem(<?php echo $_REQUEST["id"]; ?>,'controller/act-news','excluirEmail','id')">Excluir</button>
        <button type="button" onClick="location.href='cad-news-emails';">Cadastrar Novo</button>
    <?php } ?>
    <button type="button" name="btVoltar" id="btVoltar"  onclick="location.href='menu-news-emails';">Voltar</button>
</div>
<br/>
<table width="100%" border="0" align="center" class="tabelaForm" style="display:">
	<tr>
		<td width="15%">Nome:</td>
		<td width="85%">
		<input name="nmNome" id="nmNome" size="60" maxlength="100" value="<?php echo $qryEmail[0]["nmNome"]; ?>"/>
		</td>
	</tr>
    <tr>
		<td width="15%">E-mail:</td>
		<td width="85%">
		<input name="nmEmail" id="nmEmail" size="60" maxlength="100" style="top:auto" value="<?php echo $qryEmail[0]["nmEmail"]; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="15%" valign="top">Ativar?</td>
		<td valign="top">
			<select name="inAtivo" id="inAtivo" style="top:auto">
			<option value="1" <?php if($qryEmail[0]["inAtivo"] == 1 || !strlen($qryEmail[0]["inAtivo"])){ echo 'selected'; } ?>>Sim</option>
			<option value="0" <?php if($qryEmail[0]["inAtivo"] == 0 && strlen($qryEmail[0]["inAtivo"])){ echo 'selected'; } ?>>N&atilde;o</option>
			</select>
		</td>
	</tr>
    <tr>
    	<td width="15%" valign="top">Grupos:</td>
        <td valign="top">
        	<?php foreach($qryGrupos as $g): ?>
        	<input type="checkbox" name="grupos[]" <?php echo (in_array($g['idGrupo'], $grupos)?'checked="checked"':''); ?> value="<?php echo $g['idGrupo'];?>"><?php echo $g['nmGrupo']; ?><br/>
            <?php endforeach;?>
        </td>
    </tr>
</table>
<br/>
<div align="left">
    <?php if(!strlen($_REQUEST["id"])){ ?>
        <button type="submit" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
        <input type="hidden" name="acao" value="cadastrarEmail" />
    <?php } else {?>
        <button type="submit" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" onClick="excluirItem(<?php echo $_REQUEST["id"]; ?>,'controller/act-news','excluirEmail','id')">Excluir</button>
        <button type="button" onClick="location.href='cad-news-emails';">Cadastrar Novo</button>
        <input type="hidden" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
        <input type="hidden" name="acao" value="atualizarEmail"/>
    <?php } ?>
    <button type="button" onclick="location.href='menu-news-emails';">Voltar</button>
</div>
<br/>
</form>