<?php
if (
        ($_REQUEST["idConteudo"] && !is_numeric($_REQUEST["idConteudo"])) ||
        ($_REQUEST["idTipoConteudo"] && !is_numeric($_REQUEST["idTipoConteudo"]))) {
    throw new Exception('Erro no código');
}

$qryTipoConteudo = $db->query("SELECT * FROM tb_tipo_conteudo WHERE idTipoPagina IN(1,3) AND idTipoConteudo <> 13 ORDER BY nmTipoConteudo");
$qryConteudo = $db->query($s = "SELECT * FROM tb_conteudo WHERE idConteudo = " . $db->clean($_REQUEST["idConteudo"]));
$diaSemana = $qryConteudo[0]["nrDiaSemana"];
$qryPerfis = $db->query("SELECT * FROM tb_perfil WHERE idPerfil > 1 ORDER BY idPerfil ASC");

if ($_REQUEST["idTipoConteudo"]) {
    $idTipoConteudo = $_REQUEST["idTipoConteudo"];
    $qryCamposConteudo = $db->query("SELECT nmListaCampos FROM tb_tipo_conteudo WHERE idTipoConteudo = " . $idTipoConteudo . " LIMIT 1");
    $arrayCampos = explode(',', $qryCamposConteudo[0]["nmListaCampos"]);
}

//seguran�a para alterar somente quem solicitado
if ($_REQUEST["idConteudo"] && $qryConteudo) {
    $key = gerKeyDados();
    $_SESSION[$key]['idConteudo'] = $_REQUEST["idConteudo"];
    $qryConteudo[0]["idConteudo"] = $key;
    $idConteudoPt = $qryConteudo[0]["idConteudoPt"] > 0 ? $qryConteudo[0]["idConteudoPt"] : $_SESSION[$key]['idConteudo'];
}
if ($_REQUEST["idConteudoPt"]) {
    $qryConteudoLang = $db->query($s = "SELECT idConteudo FROM tb_conteudo WHERE nmLang = '" . $_REQUEST["lang"] . "' and idConteudoPt = " . $db->clean($_REQUEST["idConteudoPt"]));
    if ($qryConteudoLang) {
        $qryConteudo = $db->query($s = "SELECT * FROM tb_conteudo WHERE idConteudo = " . $qryConteudoLang[0]["idConteudo"]);
        $idConteudoPt = $_REQUEST["idConteudoPt"];
        $key = gerKeyDados();
        $_SESSION[$key]['idConteudo'] = $qryConteudo[0]["idConteudo"];
        $qryConteudo[0]["idConteudo"] = $key;
    } else {
        $idConteudoPt = $_REQUEST["idConteudoPt"];
    }
}
?>
<script type="text/javascript">
    function mudaTipo() {
        var tipo = document.getElementById("idTipoConteudo").value;
        location.href = 'cad-conteudo?idTipoConteudo=' + tipo;
    }
    function mudaTipo2(lang) {
        lang = lang.value;
        var rel = $("#idConteudoPt").val();
        var tipo = $("#idTipoConteudo").val();
        if (lang != "pt-br") {
            location.href = 'cad-conteudo?idTipoConteudo=' + tipo + '&idConteudoPt=' + rel + '&lang=' + lang;
        }
        else {
            location.href = 'cad-conteudo?idTipoConteudo=' + tipo + '&idConteudo=' + rel + '&lang=' + lang;
        }
    }
</script>   	
<form name="formGeral" id="formGeral" action="controller/act-conteudo" method="post" enctype="multipart/form-data">
    <input type="hidden" name="idConteudoPt" id="idConteudoPt" value="<?php echo $idConteudoPt; ?>">
    <h1>Publica&ccedil;&atilde;o de Conte&uacute;dos</h1>
    <br/>

    <h2 class="separadador">Tipo de Conte&uacute;do</h2>
    <form name="formConteudo" id="formConteudo" action="" method="post">
        <div align="left">
            Selecione: 
            <select name="idTipoConteudo" id="idTipoConteudo" onChange="mudaTipo();">
                <option value="">Todos</option>
                <?php for ($i = 0; $i < count($qryTipoConteudo); $i++) { ?>
                    <option value="<?php echo $qryTipoConteudo[$i]["idTipoConteudo"]; ?>" <?php
                    if ($qryTipoConteudo[$i]["idTipoConteudo"] == $idTipoConteudo) {
                        echo 'selected';
                        $tipoSelecionado = $qryTipoConteudo[$i]["nmTipoConteudo"];
                    }
                    ?>><?php echo $qryTipoConteudo[$i]["nmTipoConteudo"]; ?></option>
                        <?php } ?>
            </select>
            <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href = 'menu-conteudo?idTipoConteudo=<?php echo $idTipoConteudo; ?>';">Voltar</button>
        </div>
        <br/>

        <?php include('sisMensagem.php'); ?>

        <?php if (isset($_REQUEST["idTipoConteudo"]) && strlen($_REQUEST["idTipoConteudo"]) > 0) { //Se o tipo de conte�do foi definido na URL    ?>

            <h2 class="separadador">Dados do Conte&uacute;do "<?php echo $tipoSelecionado; ?>"</h2>
            <div align="left">
                <?php if (!$qryConteudo) { ?>
                    <button type="submit" name="btEnviar" id="btEnviar"  onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                <?php } else { ?>
                    <button type="submit" name="btEnviar" id="btEnviar"  onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir" id="btExcluir" onClick="excluirConteudo('<?php echo $qryConteudo[0]["idConteudo"]; ?>');">Excluir</button>
                    <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href = 'cad-conteudo?idTipoConteudo=<?php echo $idTipoConteudo; ?>';">Cadastrar Novo</button>
                <?php } ?>
            </div>
            <br/>

            <table width="100%" border="0" align="center" class="tabelaForm">
                <?php
                $nomeCampo = "nmLang";
                if (in_array($nomeCampo, $arrayCampos)):
                    ?>
                    <tr>
                        <td width="15%">Idioma:</td>
                        <td>
                            <?php if (isset($qryConteudo[0]["idConteudo"]) or isset($idConteudoPt)) { ?>
                                <select name="nmLang" id="nmLang" onChange="mudaTipo2(this);" style="top:auto">
                                    <option value="pt-br" <?php if (("pt-br" == $qryConteudo[0][$nomeCampo]) or ("pt-br" == $_REQUEST['lang'])): ?>selected="selected"<?php endif; ?>>Português</option>
                                    <option value="en-us" <?php if (("en-us" == $qryConteudo[0][$nomeCampo]) or ("en-us" == $_REQUEST['lang'])): ?>selected="selected"<?php endif; ?>>Inglês</option>
                                </select>
                                <?php
                            }
                            else {
                                ?>
                                <select name="nmLang" id="nmLang" onChange="mudaTipo2(this);" style="top:auto">
                                    <option value="pt-br">Português</option>
                                </select>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php
                $nomeCampo = "nmTituloConteudo";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">T&iacute;tulo / Nome:</td>
                        <td><input name="nmTituloConteudo" id="nmTituloConteudo" size="60" maxlength="100" style="top:auto" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="nmTituloConteudo" id="nmTituloConteudo" size="60" maxlength="100" value=""/>
                <?php } ?>

                <?php
                $nomeCampo = "nmTituloAmigavel";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <input type="hidden" name="nmTituloAmigavel" id="nmTituloAmigavel" value="1"/>
                <?php } ?>

                <?php
                $nomeCampo = "nmLocal";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Local:</td>
                        <td><input name="nmLocal" id="nmLocal" size="60" maxlength="100" style="top:auto" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="nmLocal" id="nmLocal" size="60" maxlength="100" value=""/>
                <?php } ?>

                <?php
                $nomeCampo = "hrAbertura";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Abertura dos Portões:</td>
                        <td><input name="hrAbertura" id="hrAbertura" size="60" maxlength="100" style="top:auto" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="hrAbertura" id="hrAbertura" size="60" maxlength="100" value=""/>
                <?php } ?>
                <?php
                $nomeCampo = "idConteudoRelacionado";
                if (in_array($nomeCampo, $arrayCampos)):
                    ?>
                    <tr>
                        <td width="15%">
                            <?php
                            if ($idTipoConteudo == 26)
                                echo "Autor";
                            else
                                echo "Conteúdo Relacionado";
                            ?>                            
                            :</td>
                        <td>
                            <?php
                            if ($idTipoConteudo == 26)
                                $conteudosRelacionados = $db->query('SELECT idConteudo, nmTituloConteudo FROM tb_conteudo WHERE idTipoConteudo=15');
                            else
                                $conteudosRelacionados = $db->query('SELECT idConteudo, nmTituloConteudo FROM tb_conteudo WHERE idTipoConteudo=4');
                            ?>
                            <select name="idConteudoRelacionado" id="idConteudoRelacionado">
                                <option value="">Selecione</option>
                                <?php foreach ($conteudosRelacionados as $cr): ?>
                                    <option value="<?php echo $cr['idConteudo'] ?>" <?php if ($cr['idConteudo'] == $qryConteudo[0][$nomeCampo]): ?>selected="selected"<?php endif; ?>><?php echo $cr['nmTituloConteudo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php
                $nomeCampo = "nmTagConteudo";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Tag:</td>
                        <td>
                            <span class="destaque_italico">&raquo; Digite aqui um nome para tag de referencia deste item, sem caracteres especiais, em letras minúsculas e sem espaços.</span>
                            <br/>
                            <span class="destaque_italico">&raquo; Exemplo: Se o título for <span class="destaquePreto">Janjao do Samba-05</span>, digite <span class="destaqueVermelho">janjaodosambacinco</span>.</span>
                            <br/>
                            <input name="nmTagConteudo" id="nmTagConteudo" size="60" maxlength="50" style="top:auto" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="nmTagConteudo" id="nmTagConteudo" size="60" maxlength="50" value=""/>
                <?php } ?>

                <?php
                $nomeCampo = "dtDataConteudo";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Data:</td>
                        <td>
                            <?php if (!$qryConteudo) { ?>
                                <input type="text" name="dtDataConteudo" id="dtDataConteudo" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y'); ?>"/>
                            <?php } else { ?>
                                <input type="text" name="dtDataConteudo" id="dtDataConteudo" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y', strtotime($qryConteudo[0]["dtDataConteudo"])); ?>"/>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php if (!$qryConteudo) { ?>
                        <input type="hidden" name="dtDataConteudo" id="dtDataConteudo" size="10" value="<?php echo date('d/m/Y'); ?>"/>
                    <?php } else { ?>
                        <input type="hidden" name="dtDataConteudo" id="dtDataConteudo" size="10" value="<?php echo date('d/m/Y', strtotime($qryConteudo[0]["dtDataConteudo"])); ?>"/>
                    <?php } ?>
                <?php } ?>

                <?php
                $nomeCampo = "dtDataExpiracao";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Data de Encerramento:</td>
                        <td>
                            <?php if (!$qryConteudo) { ?>
                                <input type="text" name="dtDataExpiracao" id="dtDataExpiracao" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y'); ?>"/>
                            <?php } else { ?>
                                <input type="text" name="dtDataExpiracao" id="dtDataExpiracao" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y', strtotime($qryConteudo[0]["dtDataExpiracao"])); ?>"/>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php if (!$qryConteudo) { ?>
                        <input type="hidden" name="dtDataExpiracao" id="dtDataExpiracao" size="10" value="<?php echo date('d/m/Y'); ?>"/>
                    <?php } else { ?>
                        <input type="hidden" name="dtDataExpiracao" id="dtDataExpiracao" size="10" value="<?php echo date('d/m/Y', strtotime($qryConteudo[0]["dtDataExpiracao"])); ?>"/>
                    <?php } ?>
                <?php } ?>

                <?php
                $nomeCampo = "nmObservacoes";
                if (in_array($nomeCampo, $arrayCampos)) {
                    if ($idTipoConteudo == 15) {
                        ?>
                        <tr>
                            <td width="15%">Cargo:</td>
                            <td>
                                <input type="text" name="nmObservacoes" id="nmObservacoes" size="60" maxlength="100" value="<?php echo $qryConteudo[0]["nmObservacoes"]; ?>"/>
                            </td>
                        </tr>                        
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td width="15%">Observa&ccedil;&otilde;es:<br/> 
                                <span class="destaque">(<span id="contadorObservacoes">0/500</span>)</span></td>
                            <td>
                                <textarea name="nmObservacoes"cols="60" rows="5" id="nmObservacoes" onKeyUp="limitadorCampo(this, 500, 'contadorObservacoes', 'nmObservacoes');"  wrap="virtual"><?php echo $qryConteudo[0]["nmObservacoes"]; ?></textarea>
                            </td>
                        </tr>   
                        <?php
                    }
                } else {
                    ?>
                    <input type="hidden" name="nmObservacoes" id="nmObservacoes" size="60" maxlength="100" value=""/>
                <?php } ?>

                <?php
                $nomeCampo = "nmResumo";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Resumo / Descri&ccedil;&atilde;o:<br/>
                            <span class="destaque">(<span id="contador">0/500</span>)</span></td>
                        <td>
                            <span class="destaque_italico">&raquo; Descri&ccedil;&atilde;o resumida, sinpose, etc.</span>
                            <br/>
                            <textarea name="nmResumo"cols="60" rows="5" id="nmResumo" onKeyUp="limitadorCampo(this, 500, 'contador', 'nmResumo');"  wrap="virtual"><?php echo $qryConteudo[0]["nmResumo"]; ?></textarea>
                        </td>
                    </tr>   
                <?php } else { ?>
                    <input type="hidden" name="nmResumo" id="nmResumo" size="60" maxlength="100" value=""/>
                <?php } ?>

                <?php
                $nomeCampo = "nmFonteExterna";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Nome da Fonte:</td>
                        <td>
                            <span class="destaque_italico">&raquo; Cite a fonte (nome do autor do texto, pessoa que produziu, site de onde foi copiado, etc).</span><br/>
                            <input name="nmFonteExterna" id="nmFonteExterna" size="60" maxlength="100" value="<?php echo $qryConteudo[0]["nmFonteExterna"]; ?>"/>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="nmFonteExterna" id="nmFonteExterna" size="60" maxlength="100" value=""/>
                <?php } ?>

                <?php
                $nomeCampo = "nmLinkArquivo";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Arquivo associado:</td>
                        <td>
                            <span class="destaque_italico">&raquo; Utilize esta área somente se você deseja associar um arquivo a este conteúdo (um arquivo para download, um vídeo para acesso, etc);</span>
                            <br/>
                            <?php
                            if ($qryConteudo[0]['nmLinkArquivo']) {
                                $idArquivo = $qryConteudo[0]['nmLinkArquivo'];
                                $arquivoRelacionadoPagina = $db->query('SELECT nmTituloArquivo FROM tb_arquivo WHERE idArquivo = ' . $idArquivo);
                            }
                            ?>
                            <a style="cursor:pointer" onclick="removerArq()"><img style="width:16px;" src="<?php echo $url_raiz ?>admin/img/del.png" title="Remove o arquivo selecionado"></a>&nbsp;
                            <a style="cursor:pointer;" onClick="adcionarLinkExterno('nmLinkArquivo', 'nomeLinkExternoArquivo', '1');"><img style="width:16px;" src="<?php echo $url_raiz ?>admin/img/iconeAdicao.png" title="Seleciona um arquivo"></a>
                            &nbsp;<a href="#" onclick="novoArquivo();"><img style="width:16px;" src="<?php echo $url_raiz ?>admin/img/iconesUpload.png" title="Clique aqui para enviar novos arquivos"></a>
                            <span class="destaqueVermelho" id="nomeLinkExternoArquivo"><?php echo $arquivoRelacionadoPagina[0]['nmTituloArquivo']; ?></span>
                            <input type="hidden" name="nmLinkArquivo" id="nmLinkArquivo" style="width:207px;" value="<?php echo $qryConteudo[0]["nmLinkArquivo"]; ?>"/>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="nmLinkArquivo" id="nmLinkArquivo" size="60" maxlength="100" value=""/>
                <?php } ?>

                <?php
                $nomeCampo = "nmLinkExterno";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Link Externo:</td>
                        <td>
                            <input type="text" name="nmLinkExterno" id="nmLinkExterno" style="width:207px;" value="<?php echo $qryConteudo[0]["nmLinkExterno"]; ?>"/>
                            <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" height="16" class="imgHover" id="linkExternoHelp"/>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="nmLinkExterno" id="nmLinkExterno" size="60" maxlength="100" value=""/>
                <?php } ?>

                <?php
                $nomeCampo = "nmLinkImagem";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" rowspan="2">Imagem Destaque</td>
                        <td id="imgSelected">
                            <?php if ($qryConteudo && strlen($qryConteudo[0]["nmLinkImagem"]) > 0) { ?>
                                <img src="<?php echo $url_raiz . 'arquivos/enviados/thumbnails/' . $qryConteudo[0]["nmLinkImagem"]; ?>">
                            <?php } else { ?>
                                Nenhum imagem adicionada.
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a style="cursor:pointer" onclick="removerImg()"><img style="width:16px;" src="<?php echo $url_raiz ?>admin/img/del.png" title="Remove a imagem selecionada"></a>&nbsp;
                            <?php if ($qryConteudo && strlen($qryConteudo[0]["nmLinkImagem"]) > 0) { ?>
                                <a style="cursor:pointer" onClick="selecionarImagem('imgSelected', 'nmLinkImagem');"><img style="width:16px;" src="<?php echo $url_raiz ?>admin/img/iconeAdicao.png" title="Seleciona uma imagem"></a>
                            <?php } else { ?>
                                <a style="cursor:pointer" onClick="selecionarImagem('imgSelected', 'nmLinkImagem');"><img style="width:16px;" src="<?php echo $url_raiz ?>admin/img/iconeAdicao.png" title="Seleciona uma imagem"></a>
                            <?php } ?>
                            &nbsp;<a href="#" onclick="novoArquivo();"><img style="width:16px; border:0;" src="<?php echo $url_raiz ?>admin/img/iconesUpload.png" title="Clique aqui para enviar novas imagens"></a>
                            <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" height="16" class="imgHover" id="imgHelp"/>
                            <input type="hidden" name="nmLinkImagem" id="nmLinkImagem" value="<?php echo $qryConteudo[0]["nmLinkImagem"]; ?>"/>
                            <br/>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="nmLinkImagem" id="nmLinkImagem" size="60" maxlength="100" value=""/>
                <?php } ?>
                <?php
                $nomeCampo = "nmLinkImagem2";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" rowspan="2">Imagem Sobreposta</td>
                        <td id="imgSelected2">
                            <?php if ($qryConteudo && strlen($qryConteudo[0]["nmLinkImagem2"]) > 0) { ?>
                                <img src="<?php echo $url_raiz . 'arquivos/enviados/thumbnails/' . $qryConteudo[0]["nmLinkImagem2"]; ?>">
                            <?php } else { ?>
                                Nenhum imagem adicionada.
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a style="cursor:pointer" onclick="removerImg2()"><img style="width:16px;" src="<?php echo $url_raiz ?>admin/img/del.png" title="Remove a imagem selecionada"></a>&nbsp;
                            <?php if ($qryConteudo && strlen($qryConteudo[0]["nmLinkImagem2"]) > 0) { ?>
                                <a style="cursor:pointer" onClick="selecionarImagem('imgSelected2', 'nmLinkImagem2');"><img style="width:16px;" src="<?php echo $url_raiz ?>admin/img/iconeAdicao.png" title="Seleciona uma imagem"></a>
                            <?php } else { ?>
                                <a style="cursor:pointer" onClick="selecionarImagem('imgSelected2', 'nmLinkImagem2');"><img style="width:16px;" src="<?php echo $url_raiz ?>admin/img/iconeAdicao.png" title="Seleciona uma imagem"></a>
                            <?php } ?>
                            &nbsp;<a href="#" onclick="novoArquivo();"><img style="width:16px; border:0;" src="<?php echo $url_raiz ?>admin/img/iconesUpload.png" title="Clique aqui para enviar novas imagens"></a>
                            <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" height="16" class="imgHover" id="imgHelp"/>
                            <input type="hidden" name="nmLinkImagem2" id="nmLinkImagem2" value="<?php echo $qryConteudo[0]["nmLinkImagem2"]; ?>"/>
                            <br/>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="nmLinkImagem2" id="nmLinkImagem2" size="60" maxlength="100" value=""/>
                <?php } ?>

                <?php
                $nomeCampo = "valor";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Valor:</td>
                        <td><input name="valor" id="valor" size="40" maxlength="100" style="top:auto" value="<?php echo $qryConteudo[0]['valor']; ?>"/></td>
                    </tr>
                <?php } ?>                   

                <?php
                $nomeCampo = "nmLinkMiniatura";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                <?php } else { ?>
                    <input type="hidden" name="nmLinkMiniatura" id="nmLinkMiniatura" size="60" maxlength="100" value=""/>
                <?php } ?>                   

                <?php
                $nomeCampo = "nmConteudo";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Texto do Conte&uacute;do</td>
                        <td>
                            <span class="destaque_italico">&raquo; Aqui vai o texto completo do conte&uacute;do que voc&ecirc; deseja publicar.</span>
                            <br/>
                            <?php if ($idTipoConteudo != 7) { ?>
                                <span class="destaque_italico">&raquo; Se desejar publicar um v&iacute;deo do You Tube, clique em &quot;C&oacute;digo Fonte&quot;, cole o c&oacute;digo de incorpora&ccedil;&atilde;o fornecido, e clique em &quot;C&oacute;digo Fonte&quot; novamente ao terminar.</span>
                                <br/>
                            <?php } ?>
                            <textarea cols="60" rows="8" name="nmConteudo" style="visibility: hidden; display: none;">
                                <?php echo stripslashes($qryConteudo[0]["nmConteudo"]); ?>
                            </textarea>

                            <script type="text/javascript">
        //<![CDATA[
        window.CKEDITOR_BASEPATH = '<?php echo $url_raiz; ?>admin/ckeditor/';
        //]]>
                            </script>

                            <script src="<?php echo $url_raiz; ?>admin/ckeditor/ckeditor.js?t=B8DJ5M3" type="text/javascript"></script>
                            <script type="text/javascript">
                                //<![CDATA[
                                CKEDITOR.replace('nmConteudo', {"width": 1000, "height": 500,
                                    "filebrowserBrowseUrl": "<?php echo $url_raiz; ?>admin\/ckfinder\/ckfinder.html",
                                    "filebrowserImageBrowseUrl": "<?php echo $url_raiz; ?>admin\/ckfinder\/ckfinder.html?type=Images",
                                    "filebrowserFlashBrowseUrl": "<?php echo $url_raiz; ?>admin\/ckfinder\/ckfinder.html?type=Flash",
                                    "filebrowserUploadUrl": "<?php echo $url_raiz; ?>admin\/ckfinder\/core\/connector\/php\/connector.php?command=QuickUpload&type=Files",
                                    "filebrowserImageUploadUrl": "<?php echo $url_raiz; ?>admin\/ckfinder\/core\/connector\/php\/connector.php?command=QuickUpload&type=Images",
                                    "filebrowserFlashUploadUrl": "<?php echo $url_raiz; ?>admin\/ckfinder\/core\/connector\/php\/connector.php?command=QuickUpload&type=Flash"});
                                //]]>
                            </script>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="nmConteudo" id="nmConteudo" size="60" maxlength="100" value=""/>
                <?php } ?>
                <?php
                $nomeCampo = "linkFacebook";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Link Facebook:</td>
                        <td><input name="linkFacebook" id="linkFacebook" size="60" maxlength="100" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } ?>
                <?php
                $nomeCampo = "linkTwitter";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Link Twitter:</td>
                        <td><input name="linkTwitter" id="linkTwitter" size="60" maxlength="100" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } ?>
                <?php
                $nomeCampo = "linkLinkedin";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Link Linkedin:</td>
                        <td><input name="linkLinkedin" id="linkLinkedin" size="60" maxlength="100" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } ?>
                <?php
                $nomeCampo = "linkWikipedia";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Link Wikipedia:</td>
                        <td><input name="linkWikipedia" id="linkWikipedia" size="60" maxlength="100" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } ?>
                <?php
                $nomeCampo = "linkInstagram";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Link Instagram:</td>
                        <td><input name="linkInstagram" id="linkInstagram" size="60" maxlength="100" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } ?>
                <?php
                $nomeCampo = "linkGoogle";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Link Google:</td>
                        <td><input name="linkGoogle" id="linkGoogle" size="60" maxlength="100" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } ?>
                <?php
                $nomeCampo = "ordem";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%">Ordem:</td>
                        <td><input name="ordem" id="ordem" size="60" maxlength="100" style="top:auto" value="<?php echo $qryConteudo[0][$nomeCampo]; ?>"/></td>
                    </tr>
                <?php } ?>
            </table>

            <h2 class="separadador">Op&ccedil;&otilde;es Gerais</h2>
            <table width="100%" border="0" align="center" class="tabelaForm">
                <?php
                //PUBLICAR
                $nomeCampo = "inPublicar";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" valign="top">Publicar?</td>
                        <td valign="top">
                            <input type="radio" id="inPublicar" name="inPublicar" value="1" <?php
                            if ($qryConteudo && $qryConteudo[0]["inPublicar"] == 1) {
                                echo 'checked="checked"';
                            } else if (!$qryConteudo) {
                                echo 'checked="checked"';
                            }
                            ?>/> Sim
                            <input type="radio" id="inPublicar" name="inPublicar" value="0" <?php
                            if ($qryConteudo && $qryConteudo[0]["inPublicar"] == 0) {
                                echo 'checked="checked"';
                            }
                            ?>/> N&atilde;o
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="inPublicar" id="inPublicar" value="1"/>
                <?php } ?>

                <?php
                //DESTACAR
                $nomeCampo = "inDestaque";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" valign="top">Destacar?</td>
                        <td valign="top">
                            <input type="radio" id="inDestaque" name="inDestaque" value="1" <?php
                            if ($qryConteudo && $qryConteudo[0]["inDestaque"] == 1) {
                                echo 'checked="checked"';
                            }
                            ?>/> Sim
                            <input type="radio" id="inDestaque" name="inDestaque" value="0" <?php
                            if ($qryConteudo && $qryConteudo[0]["inDestaque"] == 0) {
                                echo 'checked="checked"';
                            } else if (!$qryConteudo) {
                                echo 'checked="checked"';
                            }
                            ?>/> N&atilde;o
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="inDestaque" id="inDestaque" value="0"/>
                <?php } ?>

                <?php
                //DESTACAR
                $nomeCampo = "inComentario";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" valign="top">Permitir Comentários?</td>
                        <td valign="top">
                            <input type="radio" id="inComentario" name="inComentario" value="1" <?php
                            if ($qryConteudo && $qryConteudo[0]["inComentario"] == 1) {
                                echo 'checked="checked"';
                            } else if (!$qryConteudo) {
                                echo 'checked="checked"';
                            }
                            ?>/> Sim
                            <input type="radio" id="inComentario" name="inComentario" value="0" <?php
                            if ($qryConteudo && $qryConteudo[0]["inComentario"] == 0) {
                                echo 'checked="checked"';
                            }
                            ?>/> Não
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="inComentario" id="inComentario" value="0"/>
                <?php } ?>

                <?php
                //IMPRESSAO
                $nomeCampo = "inImpressao";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" valign="top">Permitir Impressão?</td>
                        <td valign="top">
                            <input type="radio" id="inImpressao" name="inImpressao" value="1" <?php
                            if ($qryConteudo && $qryConteudo[0]["inImpressao"] == 1) {
                                echo 'checked="checked"';
                            } else if (!$qryConteudo) {
                                echo 'checked="checked"';
                            }
                            ?>/> Sim
                            <input type="radio" id="inImpressao" name="inImpressao" value="0" <?php
                            if ($qryConteudo && $qryConteudo[0]["inImpressao"] == 0) {
                                echo 'checked="checked"';
                            }
                            ?>/> Não
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="inImpressao" id="inImpressao" value="1"/>
                <?php } ?>

                <?php /*
                  //VISIBILIDADE
                  $nomeCampo = "inVisibilidade";
                  if(in_array($nomeCampo,$arrayCampos)){?>
                  <tr>
                  <td width="15%" valign="top">Dispon�vel para?</td>
                  <td valign="top">
                  <select name="inVisibilidade" id="inVisibilidade"  style="top:auto">
                  <option value="1" <?php if($qryConteudo && $qryConteudo[0]["inVisibilidade"] == 1){ echo 'selected'; } ?>>P�blico</option>
                  <option value="0" <?php if($qryConteudo && $qryConteudo[0]["inVisibilidade"] == 0){ echo 'selected'; } ?>>Privado</option>
                  </select>
                  </td>
                  </tr>
                  <?php } else { ?>
                  <input type="hidden" name="inVisibilidade" id="inVisibilidade" value="1"/>
                  <?php } */ ?>

                <?php
                //VISIBILIDADE
                $nomeCampo = "inVisibilidade";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" valign="top">Mostrar no Banner?</td>
                        <td valign="top">
                            <input type="radio" id="inVisibilidade" name="inVisibilidade" value="1" <?php
                            if ($qryConteudo && $qryConteudo[0]["inVisibilidade"] == 1) {
                                echo 'checked="checked"';
                            }
                            ?>/> Sim
                            <input type="radio" id="inVisibilidade" name="inVisibilidade" value="0" <?php
                            if ($qryConteudo && $qryConteudo[0]["inVisibilidade"] == 0) {
                                echo 'checked="checked"';
                            } else if (!$qryConteudo) {
                                echo 'checked="checked"';
                            }
                            ?>/> Não
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="inVisibilidade" id="inVisibilidade" value="1"/>
                <?php } ?> 
            </table>
            <?php
            $strRedeSocial = "SELECT COUNT(*) AS total FROM vwredesocialapi WHERE idRedeSocial = 1"; //Para este projeto, estou usando somente os dados do Twitter
            $qryRedeSocial = $db->query($strRedeSocial);
            $nomeCampo = "inCompartilhar";
            if (in_array($nomeCampo, $arrayCampos) && $qryRedeSocial[0]["total"] > 0) {
                ?>
                <h2 class="separadador">Redes Sociais</h2>
                <table width="100%" border="0" align="center" class="tabelaForm">
                    <tr>
                        <td width="15%" valign="top">Compartilhar no Twitter?</td>
                        <td valign="top">
                            <input type="radio" id="inCompartilhar" name="inCompartilhar" value="1" <?php
                            if ($qryConteudo && $qryConteudo[0]["inCompartilhar"] == 1) {
                                echo 'checked="checked"';
                            }
                            ?>/> Sim
                            <input type="radio" id="inCompartilhar" name="inCompartilhar" value="0" <?php
                            if ($qryConteudo && $qryConteudo[0]["inCompartilhar"] == 0) {
                                echo 'checked="checked"';
                            } else if (!$qryConteudo) {
                                echo 'checked="checked"';
                            }
                            ?>/> Não
                        </td>
                    </tr>
                </table>
            <?php } else { ?> 
                <input type="hidden" name="inCompartilhar" id="inCompartilhar" value="0"/>
            <?php } ?>


            <h2 class="separadador" id="titulo_s_tag_cat">Tags e Categoria</h2>
            <table width="100%" border="0" align="center" class="tabelaForm" id="tabela_s_tag_cat"> 
                <?php
                $nomeCampo = "idCategoria";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" valign="top">Categoria</td>
                        <td width="85%" valign="top">
                            <?php
                            $strcat = "SELECT * FROM vwtipoconteudocategoria WHERE idTipoConteudo = " . $db->clean($idTipoConteudo) . " ORDER BY nmCategoria ASC";
                            //new dBug($strcat);
                            $qryCategorias = $db->query($strcat);
                            ?>
                            <select name="idCategoria" id="idCategoria" style="top:auto;">
                                <option value="">[Sem Categoria]</option>
                                <?php
                                if ($qryCategorias) {
                                    foreach ($qryCategorias as $ccat) {
                                        ?>
                                        <option value="<?php echo $ccat["idCategoria"]; ?>" <?php
                                        if ($ccat["idCategoria"] == $qryConteudo[0]["idCategoria"]): echo 'selected';
                                        endif;
                                        ?>><?php echo $ccat["nmCategoria"]; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                            <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" height="16" class="imgHover" id="helpCategoriaAlbum"/>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="idCategoria" value="0"/>
                <?php } ?>
                <?php
                $nomeCampo = "idSecao";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" valign="top">Seção de Menu</td>
                        <td width="85%" valign="top">
                            <?php
                            $qrySecao = $db->query("SELECT * FROM tb_categoria WHERE inTipo = 3 ORDER BY nmCategoria ASC");
                            ?>
                            <select name="idSecao" id="idSecao"  style="top:auto;">
                                <option value="">[Selecione]</option>
                                <?php if ($qrySecao): for ($i = 0; $i < count($qrySecao); $i++): ?>
                                        <option value="<?php echo $qrySecao[$i]["idCategoria"]; ?>" <?php
                                        if ($qrySecao[$i]["idCategoria"] == $qryConteudo[0]["idSecao"]): echo 'selected';
                                        endif;
                                        ?>><?php echo $qrySecao[$i]["nmCategoria"]; ?></option>
                                                <?php
                                            endfor;
                                        endif;
                                        ?>
                            </select>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="idSecao" id="idSecao" value="0"/>
                <?php } ?>

                <?php
                $nomeCampo = "nmListaTags";
                if (in_array($nomeCampo, $arrayCampos)) {
                    ?>
                    <tr>
                        <td width="15%" valign="top">Tags</td>
                        <td valign="top">&nbsp;
                            <div style="float:left;"><a style="cursor:pointer" onClick="adicionarTagConteudo('tags');">Adicionar</a></div>
                            <?php
                            $qryTags = $db->query(
                                    'SELECT c.* FROM tb_conteudo_tag t INNER JOIN tb_categoria c ON t.idCategoria = c.idCategoria WHERE t.idConteudo=' . (int) $_GET["idConteudo"]
                            );
                            ?>
                            <div id="tagsAdd_tags">
                                <?php
                                if ($qryTags):
                                    foreach ($qryTags as $tag):
                                        ?>
                                        <div style="float:left; padding:5px;" id="tags_<?php echo $tag['idCategoria']; ?>"><img style="cursor:pointer" src="img/erro.gif" onClick="excluirTag('tags_<?php echo $tag['idCategoria']; ?>');">
                                            &nbsp;<?php echo $tag['nmCategoria']; ?><input type="hidden" name="tags[]" value="<?php echo $tag['idCategoria']; ?>"></div>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </td>
                    </tr>  
                <?php } else { ?>
                    <input type="hidden" name="nmListaTags" id="nmListaTags" value=""/>
                <?php } ?>

            </table>
            <br/>
            <div align="left">
                <?php if (!$qryConteudo) { ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2">Cadastrar</button>
                    <input type="hidden" id="acao" name="acao" value="Cadastrar"  onClick="return validaFormularioSeguro(this.form);"/>
                <?php } else { ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2"  onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirConteudo('<?php echo $_GET["idConteudo"]; ?>');">Excluir</button>
                    <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href = 'cad-conteudo?idTipoConteudo=<?php echo $idTipoConteudo; ?>';">Cadastrar Novo</button>
                    <input type="hidden" id="idConteudo" name="idConteudo" value="<?php echo $key; ?>" />
                    <input type="hidden" id="acao" name="acao" value="Atualizar" />
                <?php } ?>

                <input type="hidden" id="idUsuarioCadastro" name="idUsuarioCadastro" value="<?php echo $_SESSION["ID"]; ?>" />
            </div>
            <br/>
        </form>

    <?php }//Fim - Se o tipo de conte�do est� definido na URL         ?>
    <script type="text/javascript">
                                $("#valor").setMask("decimal");

                                function selecionarImagem(id, idInput) {
                                    enableCache = false;
                                    displayMessage('<?php echo $url_raiz; ?>admin/popup-selecionar-imagem?ajax=1&id=' + id + '&idInput=' + idInput, 188, 400);
                                }

                                function adicionarTagConteudo(id) {
                                    //pode ser usada para adicionar tag a um arquivo ou a um conteudo
                                    displayMessage('<?php echo $url_raiz; ?>admin/adicionar-tag-conteudo?ajax=1&idItem=' + id, 200, 300);
                                }

                                function excluirTag(id) {
                                    var s = new String(id);
                                    var p = s.indexOf("_");
                                    var idArquivo = id.substr(0, p);
                                    var idTag = id.substr(p + 1, s.length);

                                    var idEl = '#' + idArquivo + '_' + idTag;
                                    $(idEl).animate({opacity: 0}, 'slow', function() {
                                        $(idEl).hide('slow', function() {
                                            $(idEl).remove();
                                        });
                                    })
                                }

                                function adcionarLinkExterno(id, idNome, tipo) {
                                    enableCache = false;
                                    displayMessage('<?php echo $url_raiz; ?>admin/popup-adicionar-link-externo?ajax=1&id=' + id + '&idNome=' + idNome + '&tipo=' + tipo, 188, 400);
                                }

                                if (!$('#tabela_s_tag_cat tr').length) {
                                    $('#titulo_s_tag_cat').css('display', 'none');
                                    $('#tabela_s_tag_cat').css('display', 'none');
                                }

                                function novoArquivo() {
                                    popupFrame('<?php echo $url_raiz; ?>admin/cad-arquivos?noTopoRodape=1', 500, 1060);
                                }

                                function removerImg() {
                                    $('#nmLinkImagem').val('');
                                    $('#imgSelected').html('Nenhum image adicionada.');
                                }

                                function removerImg2() {
                                    $('#nmLinkImagem2').val('');
                                    $('#imgSelected2').html('Nenhum image adicionada.');
                                }

                                function removerArq() {
                                    $('#nomeLinkExternoArquivo').val('');
                                    $('#nmLinkArquivo').empty();
                                }

                                ugaAlert('imgHelp',
                                        '(<span class="destaque_vermelho">IMPORTANTE!</span>) As imagens devem ser tratadas de acordo com as dimensoes especificadas na se&ccedil;&atilde;o Layout / ' + 'Modelos e arquivos, e enviadas pela aplicaçao de "Arquivos e Médias".<br/>' +
                                        '&raquo; Banner Pequeno: Largura: 200px x Altura: 80px;<br/>' +
                                        '&raquo; Banner Destaque, Servi&ccedil;os, etc: Largura: 970px x Altura: 214px;<br/>' +
                                        '&raquo; Imagens para Not&iacute;cias, Servi&ccedil;os, etc: Largura: 500px x Altura: 310px;'
                                        );

                                ugaAlert('helpCategoriaAlbum',
                                        '&raquo; A categoria vincula um tipo de conteúdo a uma subseção.<br/>' +
                                        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Exemplo:<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tipo de Conteúdo: Vídeo;<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Categoria: Depoimentos.'
                                        );


                                ugaAlert('linkExternoHelp',
                                        '<span class="destaque_italico">&raquo; Se o conteúdo desta página refere-se a um texto ou material de fontes externas, informe aqui o link de acesso;</span>' +
                                        '<br/>' +
                                        '<span class="destaque_italico">&raquo; Se deseja simplesmente incluir um link para um site externo, preencha este campo com a URL completa.</span>' +
                                        '<br/>' +
                                        '<span class="destaque_italico">&raquo; Se voce está cadastrando um VÍDEO, cole aqui o código que vem do YouTube.</span>' +
                                        '<br/>' +
                                        '<span class="destaque_italico">&raquo; Exemplo: <span class="destaquePreto">http://www.youtube.com/watch?v=</span>' +
                                        '<span class="destaqueVermelho">6uRF6kOs5FI</span> (Cole aqui a parte em vermelho).</span>'
                                        );
    </script>