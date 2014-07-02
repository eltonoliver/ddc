<?php
$strCamposConteudo = "
		SELECT 	column_name AS nomeCampo,
				column_comment AS nmDescricaoCampo
		FROM 	information_schema.columns 
		WHERE 	table_schema='" . $mysql_database . "' 
		AND 	table_name='tb_conteudo'
		AND		column_name NOT IN ('idConteudo','idTipoConteudo','idUsuarioCadastro','dtDataCadastro')
	";
$qryCamposConteudo = $db->query($strCamposConteudo);

$qryTipoConteudo = $db->query('SELECT * FROM tb_tipo_conteudo WHERE idTipoConteudo = ' . $db->clean($_REQUEST['idTipoConteudo']));
$qryTipoPagina = $db->query('SELECT * FROM tb_tipo_pagina');
?>        	
<form name="formGeral" id="formGeral" action="controller/act-tipo-conteudo" method="post" enctype="multipart/form-data">

    <h1>Cadastro de Tipos de Conteúdos</h1>
    <br/>

    <?php include('sisMensagem.php'); ?>

    <h2 class="separadador">Dados da Categoria</h2>
    <div align="left">
        <?php if (!isset($_REQUEST["idTipoConteudo"])) { ?>
            <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
        <?php } else { ?>
            <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
            <?php if ($_SESSION["PERFIL"] == 1 || $_SESSION["PERFIL"] == 8) { ?>
                <button type="button" name="btExcluir" id="btExcluir" onClick="excluirItem('<?php echo $_GET["idTipoConteudo"]; ?>', 'actTipoConteudo.php', 'Excluir', 'idTipoConteudo');">Excluir</button>
            <?php } ?>
            <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href = 'cad-tipo-conteudo';">Cadastrar Novo</button>
        <?php } ?>
        <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href = 'menu-tipo-conteudo';">Voltar</button>
    </div>
    <br/>

    <table width="100%" border="0" align="center" class="tabelaForm">
        <tr>
            <td width="15%">Nome:</td>
            <td width="85%">
                <input name="nmTipoConteudo" id="nmTipoConteudo" size="30" maxlength="30" style="top:auto" value="<?php echo $qryTipoConteudo[0]['nmTipoConteudo']; ?>"/>
            </td>
        </tr>
        <tr>
            <td width="15%">Tipo página:</td>
            <td width="85%">
                <select name="idTipoPagina" id="idTipoPagina"  style="top:auto;">
                    <option value="">[Selecione]</option>
                    <?php
                    if ($qryTipoPagina):
                        foreach ($qryTipoPagina as $p):
                            ?>
                            <option value="<?php echo $p['idTipoPagina']; ?>" <?php if ($p['idTipoPagina'] == $qryTipoConteudo[0]['idTipoPagina']) echo 'selected="selected"'; ?>>
                                <?php echo $p['nmTipoPagina']; ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </td>
        </tr>
        <?php
        //Busca os arquivos para montar a playlist								
        $diretorio = $raiz . '/modulos';
        $dir->setDiretorio($diretorio);
        $dir->setExtensao('php');
        $arq = $dir->listaArquivos();
        ?>
        <tr>
            <td width="15%">Arquivo:</td>
            <td width="85%">
                <select name="nmPaginaConteudo" id="nmPaginaConteudo"  style="top:auto;">
                    <option value="">[Selecione]</option>
                    <option value="#" <?php
                    if ($qryTipoConteudo[0]["nmPaginaConteudo"] == '#') {
                        echo 'selected';
                    }
                    ?>>[Não aplicável]</option>
                            <?php
                            for ($i = 1; $i < count($arq); $i++) {
                                $nomePagina = montaNome($arq[$i]);
                                ?>
                        <option value="<?php echo $nomePagina; ?>" <?php
                        if ($nomePagina == $qryTipoConteudo[0]["nmPaginaConteudo"]): echo 'selected';
                        endif;
                        ?>><?php echo $nomePagina; ?></option>
                            <?php } ?>
                </select>
            </td>
        </tr>
        <?php

        function montaNome($paginaPHP) {
            $pagina = preg_replace('/(?<!^)([A-Z])/', '-\\1', $paginaPHP);
            $pagina = str_replace('.php', '', $pagina);
            $pagina = strtolower($pagina);
            return $pagina;
        }
        ?>
        <tr>
            <td width="10%" valign="top">Campos utilizados:</td>
            <td valign="top">
                <?php
                $arrayCampos = explode(',', $qryTipoConteudo[0]["nmListaCampos"]);
                for ($i = 0; $i < count($qryCamposConteudo); $i++) {
                    ?>

                    <input type="checkbox" 	id="nomeCampo" 
                           name="nomeCampo[]" 
                           value="<?php echo $qryCamposConteudo[$i]["nomeCampo"]; ?>"
                           <?php
                           if (in_array($qryCamposConteudo[$i]["nomeCampo"], $arrayCampos)) {
                               echo 'checked="checked"';
                           }
                           ?>
                           /> <?php echo $qryCamposConteudo[$i]["nmDescricaoCampo"]; ?><br/>
                       <?php } ?>	
            </td>
        </tr>                          
    </table>
    <br/>
    <div align="left">
        <?php if (!isset($_REQUEST["idTipoConteudo"])) { ?>
            <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
            <input type="hidden" id="acao" name="acao" value="Cadastrar"/>
        <?php } else { ?>
            <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
            <?php if ($_SESSION["PERFIL"] == 1 || $_SESSION["PERFIL"] == 8) { ?>
                <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirItem('<?php echo $_GET["idTipoConteudo"]; ?>', 'controller/act-tipo-conteudo', 'Excluir', 'idTipoConteudo');">Excluir</button>
            <?php } ?>
            <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href = 'cad-tipo-conteudo';">Cadastrar Novo</button>
            <input type="hidden" id="idTipoConteudo" name="idTipoConteudo" value="<?php echo $_GET["idTipoConteudo"]; ?>" />
            <input type="hidden" id="acao" name="acao" value="Atualizar" />
        <?php } ?>
        <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href = 'menu-tipo-conteudo';">Voltar</button>
    </div>
    <br/>
</form>