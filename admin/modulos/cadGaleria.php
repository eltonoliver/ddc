<?php



$strGal = $db->query('SELECT * FROM tb_galeria_categoria');
?>
<?php
$key = $_REQUEST['key'];
$id = $_REQUEST['id'];
$atualizar = false;
if ($key) {
    $atualizar = true;
    $dados = $_SESSION[$key]['dados'];
    $idConteudo = $_SESSION[$key]['dados']['idGaleria'];
} elseif ($id) {
    $d = $db->select(array('idGaleria' => $id), 'tb_galeria');
    if ($d) {
        $atualizar = true;
        $key = md5(uniqid());
        $_SESSION[$key]['dados'] = $dados = current($d);
    }
    $idConteudo = $id;
}
$strListGal = $db->query("SELECT * FROM tb_galeria WHERE idGaleria = '$idConteudo'");
//new dBug($_SESSION);

if ($dados) {
    $imagens = $db->select(array('idGaleria' => $dados['idGaleria'], 'orderby' => 'inOrdem'), 'tb_galeria_imagem');
    if ($imagens) {
        $f = new moveFile();
        foreach ($imagens as $i => $ig) {
            $imagens[$i]['nmImagemUrl'] = $url_raiz . 'arquivos/enviados/thumbnails/' . $f->montarSubDirUrl($dados['idGaleria']) . $ig['nmImagem'];
        }
    }

    $_SESSION[$key]['dados']['imagens'] = $imagens;
}
?>
<form name="formGeral" id="formGeral" action="controller/act-galeria" method="post" enctype="multipart/form-data">
    <h1>Cadastro de Galeria</h1>
    <br/>
    <?php include('sisMensagem.php'); ?>
    <div align="left">
        <?php if (!$atualizar) { ?>
            <button type="submit">Cadastrar</button>
        <?php } else { ?>
            <button type="submit">Atualizar</button>
            <button type="button">Excluir</button>
            <button type="button" onClick="location.href = '<?php echo $url_raiz; ?>admin/cad-galeria';">Cadastrar Novo</button>
        <?php } ?>
        <button type="button" onClick="location.href = '<?php echo $url_raiz; ?>admin/menu-galeria';">Voltar</button>
    </div>
    <br/>

    <table width="100%" border="0" align="center" class="tabelaForm">
        <tr>
            <td width="15%">T&iacute;tulo / Nome:</td>
            <td><input name="nmGaleria" maxlength="200" size="80" value="<?php echo $dados['nmGaleria']; ?>"/></td>
        </tr>
        <tr>
            <td width="15%">Local:</td>
            <td><input name="nmLocal" maxlength="200" size="80" value="<?php echo $dados['nmLocal']; ?>"/></td>
        </tr>
        <tr>
            <td width="15%">Categoria:</td>
            <td><select name="nmCategoriaGaleria" id="nmPaginaConteudo"  style="top:auto;">
                    <option value="">[Selecione]</option>
                    <?php
                    if ($strGal):
                        foreach ($strGal as $p):
                            ?>
                            <option value="<?php echo $p['idCategoria']; ?>" <?php if ($p['idCategoria'] == $strListGal[0]['idCategoriaGaleria']) echo 'selected="selected"'; ?>>
                                <?php echo $p['nmCategoria']; ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                </td>
        </tr>
        <tr>
            <td width="15%">Descri&ccedil;&atilde;o:<br><span class="destaque">(<span id="contador">0/1000</span>)</span></td>
            <td><textarea id="nmResumo" name="nmDescricao" rows="10" cols="77" onKeyUp="limitadorCampo(this, 1000, 'contador', 'nmResumo');"><?php echo $dados['nmDescricao']; ?></textarea></td>
        </tr>
        <tr>
            <td width="15%">Data:</td>
            <td><input name="dtDataGaleria" id="dtDataGaleria" class="jdpicker" readonly="readonly" maxlength="200" size="80" value="<?php echo $dados['dtDataGaleria'] ? dataBarrasBR($dados['dtDataGaleria']) : date("d/m/Y"); ?>"/></td>
        </tr>
        <tr>
            <td width="15%" valign="top">Ativar?<img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" class="imgHover" id="ImgsHelpAtivar"/></td>
            <td valign="top">
                <input type="radio" name="inAtivo" value="1" <?php echo (($dados["inAtivo"] || !$dados) ? 'checked="checked"' : ''); ?>/> Sim
                <input type="radio" name="inAtivo" value="0" <?php echo (($dados["inAtivo"] == 0 && $dados) ? 'checked="checked"' : ''); ?>/> N&atilde;o
            </td>
        </tr>
        <tr>
            <td width="15%" valign="top">Destacar?<img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" class="imgHover" id="ImgsHelpDestacar"/></td>
            <td valign="top">
                <input type="radio" name="inDestaque" value="1" <?php echo (($dados["inDestaque"] || !$dados) ? 'checked="checked"' : ''); ?>/> Sim
                <input type="radio" name="inDestaque" value="0" <?php echo (($dados["inDestaque"] == 0 && $dados) ? 'checked="checked"' : ''); ?>/> N&atilde;o
            </td>
        </tr>
        <!--<tr>
            <td width="15%" valign="top">Publica&ccedil;&atilde;o?<img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" class="imgHover" id="ImgsHelpPublicar"/></td>
            <td valign="top">
                <input type="radio" name="inPublicacao" value="1" <?php echo (($dados["inPublicacao"] || !$dados) ? 'checked="checked"' : ''); ?>/> Sim
                <input type="radio" name="inPublicacao" value="0" <?php echo (($dados["inPublicacao"] == 0 && $dados) ? 'checked="checked"' : ''); ?>/> N&atilde;o
            </td>
        </tr>
        <tr>
            <td width="15%" valign="top">Dispon&iacute;vel para?<img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" class="imgHover" id="ImgsHelpVisibilidade"/></td>
            <td valign="top">
                <select name="inVisibilidade" id="inVisibilidade">
                    <option value="1" <?php
        if ($dados && $dados["inVisibilidade"] == 1) {
            echo 'selected';
        }
        ?>>P&uacute;blico</option>
                    <option value="0" <?php
        if ($dados && $dados["inVisibilidade"] == 0) {
            echo 'selected';
        }
        ?>>Privado</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="15%" valign="top">Se&ccedil;&atilde;o de Menu</td>
            <td width="85%" valign="top">
        <?php $qrySecao = $db->query("SELECT * FROM tb_secao ORDER BY nrOrdem ASC"); ?>
                <select name="idSecao">
                    <option value="">[Selecione]</option>
        <?php if ($qrySecao): for ($i = 0; $i < count($qrySecao); $i++): ?>
                                                                                                                                                                                                                            <option value="<?php echo $qrySecao[$i]["idSecao"]; ?>" <?php
                if ($qrySecao[$i]["idSecao"] == $dados["idSecao"]): echo 'selected';
                endif;
                ?>><?php echo $qrySecao[$i]["nmSecao"]; ?></option>
                <?php
            endfor;
        endif;
        ?>
                </select>
            </td>
        </tr>
        <tr>
            <td width="15%" valign="top">Arquivo PDF
                <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" class="imgHover" id="ImgsHelpArq"/>
            </td>
            <td width="85%" valign="top">
                <input type="file" name="nmArquivo"><span>&nbsp;<?php echo substr($dados['nmArquivo'], 33); ?></span>
            </td>
        </tr>-->
        <?php if ($idConteudo) { ?>
            <tr>
                <td width="15%" valign="top">
                    Imagens:
                    <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" class="imgHover" id="ImgsHelp"/>
                    &nbsp;<a href="#" onclick="uploadGaleria();"><img style="width:16px; border:0;" src="<?php echo $url_raiz ?>admin/img/iconesUpload.png" title="Clique aqui para enviar novas imagens"></a>
                </td>
                <td>
                    <div id="galeriaImagens"></div>
                </td>
            </tr>
        <?php } ?>
    </table>
    <br/>
    <div align="left">
        <?php if (!$atualizar) { ?>
            <button type="submit">Cadastrar</button>
            <input type="hidden" name="acao" value="cadastrarGaleria"/>
        <?php } else { ?>
            <button type="submit">Atualizar</button>
            <button type="button">Excluir</button>
            <button type="button" onClick="location.href = '<?php echo $url_raiz; ?>admin/cad-galeria';">Cadastrar Novo</button>
            <input type="hidden" name="key" value="<?php echo $key; ?>" />
            <input type="hidden" name="acao" value="atualizarGaleria" />
        <?php } ?>	
        <button type="button" onClick="location.href = '<?php echo $url_raiz; ?>admin/menu-galeria';">Voltar</button>
    </div>
</form>

<script type="text/javascript">
            var url_raiz = '<?php echo $url_raiz; ?>';
            //envio de várias fotos
            function uploadGaleria() {
                popupFrame('<?php echo $url_raiz; ?>admin/cad-arquivos?noTopoRodape=1&idConteudo=<?php echo $idConteudo; ?>', 500, 1060);
            }

            var giPGal = $('#galeriaImagens');
            var giPInd = 0;
            function giPAdd(a, o) {
                a['idImagem'] = a['idImagem'] ? a['idImagem'] : null;
                a['nmImagem'] = a['nmImagem'] ? a['nmImagem'] : null;
                a['nmLegenda'] = a['nmLegenda'] ? a['nmLegenda'] : null;
                a['statusImagem'] = a['statusImagem'] ? a['statusImagem'] : 'i';

                var b = $('<div class="blockImagem"></div>')
                        .append(
                        $('<div></div>')
                        .append('<div class="labelG">' + (a['nmImagem'] ? '&nbsp;<img id="giPVer_' + giPInd + '">' : '&nbsp') + '</div>')
                        .append(
                        $('<div class="campoG"></div>')
                        .append('Capa')
                        .append($('<input type="radio" name="capa" value="' + giPInd + '">').attr({checked: (a['inCapa'] == 1 ? true : false)}))
                        .append('&nbsp;&nbsp;&nbsp;Imagem:')
                        .append('<input type="file" name="imagem[' + giPInd + ']"/><input type="hidden" name="statusImagem[' + giPInd + ']" value="' + a['statusImagem'] + '">')
                        .append('&nbsp;&nbsp;Legenda:')
                        .append(
                        $('<input type="text" maxlength="300" name="legenda[' + giPInd + ']">').val(a['nmLegenda'])
                        ).append('&nbsp;&nbsp;Ordem:').append(
                        $('<input type="text" maxlength="3" size="2" name="inOrdem[' + giPInd + ']">').val(a['inOrdem'])
                        )
                        .append(
                        $('<img/>').css({cursor: 'pointer'}).attr({
                    width: 16,
                    src: url_raiz + 'admin/img/del.png',
                    title: 'Remove a imagem selecionada',
                    desfazer: 'false'
                }).click(function() {

                    if ($(giPGal).children().length > 1 && $(giPGal).find('img[desfazer="false"]').length > 1) {
                        var o = $(this).parent().parent().parent(),
                                io = $(this).parent().parent().parent().attr('indeximg');

                        if ($(this).attr('desfazer') == 'false') {
                            if ($(o).find('input[name="statusImagem[' + io + ']"]').val() == 'i') {
                                $(o).animate({opacity: 0}, 'fast', function() {
                                    $(this).hide('fast', function() {
                                        $(o).remove();
                                    });
                                });
                            } else {
                                $(o).find('input[name="statusImagem[' + io + ']"]').val('d');
                                $(o).find('*').attr('disabled', true);
                                $(o).find('input[name="statusImagem[' + io + ']"]').attr('disabled', false);

                                $(this).attr({
                                    desfazer: 'true',
                                    src: url_raiz + 'admin/img/iconeAtualizar.png',
                                    title: 'Desfazer a remoção da imagem'
                                });
                            }
                        } else {
                            $(o).find('*').attr('disabled', false);
                            $(o).find('input[name="statusImagem[' + io + ']"]').val('a');

                            $(this).attr({
                                desfazer: 'false',
                                src: url_raiz + 'admin/img/del.png',
                                title: 'Remove a imagem selecionada'
                            });
                        }
                    }
                })
                        )
                        .append('&nbsp;')
                        .append(
                        $('<img/>').css({cursor: 'pointer'}).attr({
                    width: 16,
                    src: url_raiz + 'admin/img/iconeAdicao.png',
                    title: 'Adicionar uma imagem'
                }).click(function() {
                    giPAdd(new Array(), $(this).parent().parent().parent());
                })
                        )
                        )
                        )
                        .append('<br style="clear:both;">');



                $(b).attr('indeximg', giPInd).hide().css({opacity: 0});
                if (o) {
                    $(o).after(b);
                } else {
                    $(giPGal).append(b);
                }
                $(b).show('fast', function() {
                    $(this).animate({opacity: 1}, 'fast');
                })

                if (a['nmImagem']) {
                    $('#giPVer_' + giPInd).attr('src', a['nmImagem']);
                    ugaAlert('giPVer_' + giPInd, '<img width="200" src="' + a['nmImagem'] + '">');
                }

                giPInd++;

            }


<?php if ($imagens): ?>
    <?php foreach ($imagens as $ig): ?>
                    giPAdd({
                        nmImagem: '<?php echo $ig['nmImagemUrl']; ?>',
                        nmLegenda: '<?php echo $ig['nmLegenda']; ?>',
                        inOrdem: '<?php echo $ig['inOrdem']; ?>',
                        inCapa: '<?php echo $ig['inCapa']; ?>',
                        statusImagem: 'a'
                    });
    <?php endforeach; ?>
<?php else: ?>
                giPAdd(new Array());
<?php endif; ?>

            ugaAlert('ImgsHelp',
                    '<span class="destaque_italico">&raquo; Selecione uma imagem para ser a capa da galeria clicando em "Capa"</span><br>' +
                    '<span class="destaque_italico">&raquo; Para excluir uma imagem clique no "x"</span><br>' +
                    '<span class="destaque_italico">&raquo; Para adicionar uma imagem clique no "+"</span><br>');

            ugaAlert('ImgsHelpArq', 'Esse arquivo vai ser usado para o publica&ccedil;&otilde;es<br>no caso se o navegar n&atilde;o suportar a revista on-line<br>esse ser&aacute; usado para fazer o download do pdf diretamente.');

            ugaAlert('ImgsHelpAtivar',
                    '<span class="destaque_italico">Ativar a exibi&ccedil;&atilde;o da galeria no site</span><br>'
                    );

            ugaAlert('ImgsHelpDestacar',
                    '<span class="destaque_italico">P&otilde;e a galeria em destaque na p&aacute;gina principal.</span><br>'
                    );

            ugaAlert('ImgsHelpPublicar',
                    '<span class="destaque_italico">Faz com que a galaria seja uma publica&ccedil;&atilde;o na revista eletr&ocirc;nica.</span><br>'
                    );
</script>

<script type="text/javascript">

    $(function() {
        // Datepicker
        $('#dtDataGaleria').datepicker({
            inline: true,
            dateFormat: 'dd/mm/yy'
        });

        limitadorCampo(document.getElementById('nmResumo'), 1000, 'contador', 'nmResumo');
    });
</script>