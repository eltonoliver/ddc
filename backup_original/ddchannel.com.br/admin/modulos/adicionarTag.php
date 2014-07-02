<?php
if ($_GET["tipo"] == 'arquivo') {
    $destino = 'controller/act-arquivos';
    $chave = 'idArquivo';
} else if ($_GET["tipo"] == 'conteudo') {
    $destino = 'controller/act-conteudo';
    $chave = 'idConteudo';
}

$qryTags = $db->query("SELECT * FROM tb_categoria WHERE inTipo = 2 ORDER BY nmCategoria");
?>
<table bgcolor="#ffffff" width="100%" border="0" cellspacing="2" cellpadding="0">
    <tr class="FonteTituloPOP">
        <td width="96%" style="color:#000;">Adicionar Tag</td>
        <td width="2%"><img src="<?php echo $url_raiz; ?>admin/js/modalMessage/botFechar.png" onClick="closeMessage();" title="Fechar" onMouseOver="javascript: this.style.cursor = 'pointer';"></td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="5" cellpadding="0" id="tabForm">
    <tr>
        <td align="left" class="FonteGeral">
            <p id="msgErroCadTagP" style="display:none;text-align:center;">
                <img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/>
                <span id="msgErroCadTag">Ocorreu um erro interno do sistema.</span></p>
            <form id="formEnviarTag" name="formEnviarTag" method="post" action="<?php echo $destino; ?>" onsubmit="return false;">
                <table width="100%" border="0" cellpadding="1" cellspacing="0">
                    <tr>
                        <td colspan="2" align="left" class="formLabel"><br/><span class="destaque">* </span>Nome</td>
                    </tr>
                    <tr>
                        <td width="54%" align="left">
                            <select name="idCategoria" id="idCategoriaTagSelecionar" style="top:auto; width:100%;">
                                <option value="">[Selecione]</option>
                                <?php for ($i = 0; $i < count($qryTags); $i++) { ?>
                                    <option value="<?php echo $qryTags[$i]["idCategoria"]; ?>"><?php echo $qryTags[$i]["nmCategoria"]; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td width="46%" align="center">
                            <input type="hidden" name="<?php echo $chave; ?>" id="<?php echo $chave; ?>_tag" value="<?php echo $_GET["idItem"]; ?>">
                            <input type="hidden" name="acao" id="acao" value="adicionarTag">
                            <button name="submit" id="submit" onclick="enviarForm();">Enviar</button>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" align="left" class="formLabel"><br/><span class="destaque">* </span>Nova Tag</td>
                    </tr>
                    <tr>
                        <td width="54%" align="left">
                            <input type="text" style="width:95%;" name="novaTag" id="novaTag">
                        </td>
                        <td width="46%" align="center">
                            <button name="submit" id="submit" onclick="criarNovaTagConteudo();">Adicionar</button>
                        </td>
                    </tr>
                </table>
                <br/>
            </form>
        </td>
    </tr>
</table>
<div id="loading" style="display:none;"><p align="center" style="margin-top:50px;"><img src='img/ajax-loader.gif'></p></div>
<!--- Inicio Tabela Esquerda--->    
<script type="text/javascript">
            var id = '<?php echo $chave . '_tag'; ?>';

            var retornou = function(d) {
                closeMessage();
                if (d.status) {
                    var idEl = getIdTag();
                    if (!document.getElementById(idEl)) {
                        $('#tagsAdd_' + $('#' + id).val()).append('<div style="float:left; padding:5px;" id="' + idEl + '">' +
                                '<img style="cursor:pointer" src="img/erro.gif" onclick="excluirTag(\'' + idEl + '\')">&nbsp;' +
                                $('#idCategoriaTagSelecionar option:selected').text() + '</div>');
                        $('#' + idEl).css('opacity', 0).css('display', 'inline').animate({opacity: 1}, 'slow');
                    }
                } else {

                }
            }

            function getIdTag() {
                return $('#' + id).val() + '_' + $('#idCategoriaTagSelecionar').val();
            }

            function enviarForm() {
                if ($('#idCategoriaTagSelecionar').val() != '') {
                    $('#tabForm').css('display', 'none');
                    $('#loading').css('display', 'inline');
                    var idEl = getIdTag();
                    if (!document.getElementById(idEl)) {
                        ajaxRequest.sendForm('formEnviarTag', 'loading', retornou, 'json');
                    } else {
                        closeMessage();
                    }
                }
            }


            function criarNovaTagConteudo() {
                if (!limparJSX($('#novaTag').val())) {
                    $('#novaTag').empty();
                    $('#msgErroCadTagP').show();
                    setTimeout(function() {
                        $('#msgErroCadTagP').hide();
                    }, 3000);
                    return 0;
                }

                $('#tabForm').css('display', 'none');
                $('#loading').css('display', 'inline');

                var d = {
                    'nmCategoria': $('#novaTag').val(),
                    'idCategoriaPai': 0,
                    '<?php echo $chave; ?>': $('#' + id).val(),
                    'acao': 'adicionarTagNova'
                };


                $.ajax({
                    'type': 'POST',
                    'url': '<?php echo $destino; ?>',
                    'dataTypeString': 'json',
                    'data': d,
                    'success': function(data) {
                        var r = $.parseJSON(data);
                        if (r.id) {
                            closeMessage();
                            var idEl = $('#' + id).val() + '_' + r.id;
                            if (!document.getElementById(idEl)) {
                                $('#tagsAdd_' + $('#' + id).val()).append(
                                        '<div style="float:left; padding:5px;" id="' + idEl + '">' +
                                        '<img style="cursor:pointer" src="img/erro.gif" onclick="excluirTag(\'' + idEl + '\')">&nbsp;' +
                                        $('#novaTag').val() +
                                        '<input type="hidden" name="tags[]" value="' + r.id + '"></div>');
                                $('#' + idEl).css('opacity', 0).css('display', 'inline').animate({opacity: 1}, 'slow');
                            }

                        } else {
                            $('#msgErroCadTagP').css('display', 'inline');
                            if (r.status == 1) {
                                $('#msgErroCadTag').html('Tag digitada já está cadastrada.');
                            } else {
                                $('#msgErroCadTag').html('Ocorreu um erro interno do sistema.');
                            }
                            $('#tabForm').css('display', 'inline');
                            $('#loading').css('display', 'none');
                        }
                    }
                });
            }

            function limparJSX(description) {
                description.replace(/["\'][\s]*javascript:(.*)["\']/gi, "\"\"");
                description = description.replace(/<script(.*)/gi, "");
                description = description.replace(/eval\((.*)\)/gi, "");
                return description;
            }
</script>