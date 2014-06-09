<?php
	$qryCategoria = $db->query("SELECT * FROM tb_categoria WHERE idCategoria = ".$db->clean($_REQUEST["idCategoria"]));
	$qryCategoriasPai = $db->query("SELECT * FROM tb_categoria WHERE inTipo <> 3 AND idCategoriaPai = 0 ORDER BY nmCategoria DESC");
	
	$qryTipoConteudo = $db->query("SELECT * FROM tb_tipo_conteudo ORDER BY nmTipoConteudo");
?>
     	
<form name="formGeral" id="formGeral" action="controller/act-categoria" method="post" enctype="multipart/form-data">
<h1>Cadastro de Categorias</h1>
<br/>

<?php include('sisMensagem.php'); ?>

<h2 class="separadador">Dados da Categoria</h2>
<div align="left">
    <?php if(!isset($_REQUEST["idCategoria"])){ ?>
        <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
    <?php } else {?>
        <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" name="btExcluir" id="btExcluir" onClick="excluirCategoria('<?php echo $_GET["idCategoria"]; ?>');">Excluir</button>
        <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-categoria';">Cadastrar Nova</button>
    <?php } ?>
    <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href='menu-categorias';">Voltar</button>
</div>
<br/>
<table width="100%" border="0" align="center" class="tabelaForm" style="display:">
    <tr>
        <td width="15%">Nome:</td>
        <td width="85%"><input name="nmCategoria" id="nmCategoria" size="60" maxlength="100" style="top:auto" value="<?php echo $qryCategoria[0]["nmCategoria"]; ?>"/></td>
    </tr>
    <tr>
        <td width="15%" valign="top">Classifica&ccedil;&atilde;o:</td>
        <td width="85%" valign="top">
          <select name="inTipo" id="inTipo"  style="top:auto"> <!--  onChange="ocultaPai(this);" -->
            <option value="">Selecione</option>
            <option value="1" <?php if($qryCategoria[0]["inTipo"] == 1){ echo 'selected'; } ?>>Categoria</option>
            <option value="2" <?php if($qryCategoria[0]["inTipo"] == 2){ echo 'selected'; } ?>>Tag</option>
          </select>
            
            <input type="hidden" name="idCategoriaPai" value="0"/><!-- Somente para este projeto -->
          <input type="hidden" name="inDestaque" value="0"/><!-- Somente para este projeto -->
        </td>
    </tr>
    <?php /* NAO UTILIZADO NESTE PROJETO
    <tr>
        <td width="15%" valign="top">Destacar no banner?</td>
        <td width="85%" valign="top">
            <select name="inDestaque" id="inDestaque" style="top:auto">
                <option value="1" <?php if($qryCategoria[0]["inDestaque"] == 1){ echo 'selected';} ?>>Sim</option>
                <option value="0" <?php if($qryCategoria[0]["inDestaque"] == 0){ echo 'selected';} ?>>Nao</option>
            </select>
        </td>
    </tr>
	*/?>
    <tr>
        <td width="10%" valign="top">Tipos de Conte&uacute;do:</td>
        <td valign="top">
            <?php 
            
                $arrayTipos = explode(',',$qryCategoria[0]["nmListaTipoConteudo"]);//nmListaTipoConteudo
                for($i=0; $i<count($qryTipoConteudo); $i++){ ?>
                        
          <input type="checkbox" 	id="nmListaTipoConteudo" 
                                        name="nmListaTipoConteudo[]" 
                                        value="<?php echo $qryTipoConteudo[$i]["idTipoConteudo"]; ?>"
                                        <?php if($qryCategoria && in_array($qryTipoConteudo[$i]["idTipoConteudo"],$arrayTipos)){ echo 'checked="checked"'; }?>
                /> <?php echo $qryTipoConteudo[$i]["nmTipoConteudo"]; ?><br/>
                <?php } ?>	
        </td>
    </tr>                          
</table>

<br/>
<div align="left">
    <?php if(!isset($_REQUEST["idCategoria"])){ ?>
        <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
        <input type="hidden" id="acao" name="acao" value="Cadastrar" />
    <?php } else {?>
        <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirCategoria('<?php echo $_GET["idCategoria"]; ?>');">Excluir</button>
        <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href='cad-categoria';">Cadastrar Nova</button>
        <input type="hidden" id="idCategoria" name="idCategoria" value="<?php echo $_REQUEST["idCategoria"]; ?>" />
        <input type="hidden" id="acao" name="acao" value="Atualizar" />
    <?php } ?>
    <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href='menu-categorias';">Voltar</button>
</div>
<br/>
</form>