<?php
	$qryCategoria = $db->query("SELECT * FROM tb_categoria WHERE idCategoria = ".$db->clean($_REQUEST["idCategoria"]));
	$qryTipoConteudo = $db->query("SELECT * FROM tb_tipo_conteudo ORDER BY nmTipoConteudo");
	
	if(!vazio( $qryCategoria[0]["nmPalavraChave"])){
		$nmPalavraChave =  $qryCategoria[0]["nmPalavraChave"];	
	} else {
		$nmPalavraChave =  'geral';	
	}
	
	$ordemAtual = 1;
	if($qryCategoria){
		if(vazio($qryCategoria[0]["nrOrdem"])){
			$ordemAtual = 1;
		} else {
			$ordemAtual = $qryCategoria[0]["nrOrdem"];
		}
		
	} else {
		
		$query = "
			SELECT	nrOrdem
			FROM	tb_categoria
			WHERE	idCategoria	= ".$db->clean($_REQUEST["idCategoria"])."
			AND		inTipo = 3
			LIMIT 	1
		";
		$qryOrdem = $db->query($query);
		
		if($qryOrdem){
			$ordemAtual = 1;
		} else {
			$ordemAtual = $qryOrdem[0]["nrOrdem"];
		}
	}
?>
     	
<form name="formGeral" id="formGeral" action="controller/act-secoes" method="post" enctype="multipart/form-data">
<h1>Cadastro de Se&ccedil;&otilde;es de Menu</h1>
<br/>

<?php include('sisMensagem.php'); ?>

<h2 class="separadador">Dados da Se&ccedil;&atilde;o</h2>
<div align="left">
    <?php if(!isset($_REQUEST["idCategoria"])){ ?>
        <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
    <?php } else {?>
        <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" name="btExcluir" id="btExcluir" onclick="excluirItem('<?php echo $qryCategoria[0]["idCategoria"]; ?>','controller/act-secoes','Excluir','idCategoria');">Excluir</button>
        <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-secoes';">Cadastrar Nova</button>
    <?php } ?>
    <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href='menu-secoes';">Voltar</button>
</div>
<br/>
<table width="100%" border="0" align="center" class="tabelaForm" style="display:">
    <tr>
        <td width="15%">Nome:</td>
        <td width="85%"><input name="nmCategoria" id="nmCategoria" size="60" maxlength="100" style="top:auto" value="<?php echo $qryCategoria[0]["nmCategoria"]; ?>"/></td>
    </tr>
    <?php if($_SESSION["PERFIL"] == 1){ ?>
    <tr>
        <td width="15%">Palavra-chave:</td>
        <td width="85%">
        <span class="destaque_italico">&raquo; Digite uma palavra única (minúsculo, sem espaços, acentos ou caracteres especiais), para identificar esta seção.</span>
        <br/>
        <input name="nmPalavraChave" id="nmPalavraChave" size="30" maxlength="20" style="top:auto" value="<?php echo $nmPalavraChave; ?>"/>
        </td>
    </tr>
    <?php } else { ?>
        <input type="hidden" name="nmPalavraChave" id="nmPalavraChave" value="geral"/>
    <?php } ?> 
    <tr>
        <td width="15%">Ordem</td>
        <td colspan="2">
            <select name="nrOrdem" id="nrOrdem"  style="top:auto;">
            <option value="" selected>--</option>
                <?php for($i=1; $i<=50; $i++){ ?>
                    <option value="<?php echo $i; ?>" <?php if($i == $ordemAtual){ echo 'selected="selected"'; } ?>><?php echo $i; ?></option>
                <?php } ?>
          </select>                    
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
     <?php if($_SESSION["PERFIL"] == 1){ ?>
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
    <?php } else { ?> 
    <tr>
        <td width="10%" valign="top">Tipos de Conte&uacute;do:</td>
        <td valign="top">
            <input type="hidden" name="nmListaTipoConteudo" id="nmListaTipoConteudo[]" value="4"/> P�ginas
        </td>
    </tr> 
    <?php } ?> 
</table>

<br/>
<div align="left">
    <?php if(!isset($_REQUEST["idCategoria"])){ ?>
        <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
        <input type="hidden" id="acao" name="acao" value="Cadastrar" />
    <?php } else {?>
        <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" name="btExcluir2" id="btExcluir2" onclick="excluirItem('<?php echo $qryCategoria[0]["idCategoria"]; ?>','controller/act-secoes','Excluir','idCategoria');">Excluir</button>
        <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href='cad-secoes';">Cadastrar Nova</button>
        <input type="hidden" id="idCategoria" name="idCategoria" value="<?php echo $_REQUEST["idCategoria"]; ?>" />
        <input type="hidden" id="acao" name="acao" value="Atualizar" />
    <?php } ?>
    <input type="hidden" name="idCategoriaPai" value="0"/><!-- Somente para este projeto -->
    <input type="hidden" name="inDestaque" value="0"/><!-- Somente para este projeto -->
    <input type="hidden" name="inTipo" value="3"/><!-- Somente para este projeto -->
    <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href='menu-secoes';">Voltar</button>
</div>
<br/>
</form>