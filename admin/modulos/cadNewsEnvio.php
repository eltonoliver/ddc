<?php
$key = $_REQUEST['key'];
if (strlen($key)) {
    $qryEnvio[0] = $_SESSION[$key]['dados'];
    $grupos = $_SESSION[$key]['dados']['grupos'];
    $conteudo = $_SESSION[$key]['dados']['conteudo'];
    unset($_SESSION[$key]);
} else {
    $qryEnvio = $_REQUEST["id"] ? ($db->select(array('idEnvio' => $_REQUEST["id"]), 'tb_news_envio')) : null;
    $grupos = $_REQUEST["id"] ? ($db->select(array('idEnvio' => $_REQUEST["id"]), 'tb_news_envio_grupo')) : null;
    $conteudo = $_REQUEST["id"] ? ($db->select(array('idEnvio' => $_REQUEST["id"]), 'tb_news_envio_conteudo')) : null;
    $grupos = $grupos ? array_map('end', $grupos) : null;
    $conteudo = $conteudo ? array_map('end', $conteudo) : null;
}


$qryTipoNews = $db->select(array('idTipoPagina' => 3), 'tb_tipo_conteudo');
$qryGrupos = $db->select(array('orderby' => 'nmGrupo'), 'tb_news_grupo');
$idTipoConteudo = $qryEnvio[0]['idTipoConteudo'];

if ($idTipoConteudo) {
    if ($idTipoConteudo == '-1') {
        $where = ' AND idTipoConteudo IN(11,12,13,19)';
    } else {
        $where = ' AND idTipoConteudo=' . $db->clean($idTipoConteudo);
    }

    $newsConteudo = $db->query('SELECT idConteudo, nmTituloConteudo, nmTipoConteudo FROM vwconteudoarquivo WHERE 1=1' . $where . ' ORDER BY dtDataConteudo DESC');
}
?>
<form name="formGeral" id="formGeral" action="controller/act-news" method="post" enctype="multipart/form-data">
    <h1>Cadastro de Envio</h1>
    <br/>
    <?php include 'sisMensagem.php'; ?>
    <h2 class="separadador">Dados do Envio</h2>
    <div align="left">
        <?php if (!strlen($_REQUEST["id"])) { ?>
            <button type="submit" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
        <?php } else { ?>
            <button type="submit" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
            <button type="button" onClick="excluirItem(<?php echo $_REQUEST["id"]; ?>, 'controller/act-news', 'excluirEnvio', 'id')">Excluir</button>
            <button type="button" onClick="location.href = 'cad-news-envio';">Cadastrar Novo</button>
        <?php } ?>
        <button type="button" name="btVoltar" id="btVoltar"  onclick="location.href = 'menu-news-envio';">Voltar</button>
    </div>
    <br/>
    <table width="100%" border="0" align="center" class="tabelaForm" style="display:">
        <tr>
            <td>Tipo:</td>
            <td>
                <select name="idTipoConteudo" id="idTipoConteudo" style="top:auto" onchange="localizarConteudo()">
                    <option value="">--</option>
                    <option value="11" <?php echo (($idTipoConteudo == 11) ? 'selected' : ''); ?>>Boletim</option>
                    <!--<option value="-1" <?php echo (($idTipoConteudo == -1) ? 'selected' : ''); ?>>Clipping</option>-->
                    <option value="4" <?php echo (($idTipoConteudo == 4) ? 'selected' : ''); ?>>Informativo</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="15%">Newsletters:</td>
            <td width="85%">
                <div id="conteudo_news_scroll">
                    <img src="<?php echo $url_raiz; ?>admin/img/up.png" id="up" title="Use o botao de rolagem do mouse para cima"/><br/>
                    <img src="<?php echo $url_raiz; ?>admin/img/down.png" id="down" title="Use o botao de rolagem do mouse para baixo"/>
                </div>
                <div id="conteudo_news">
                    <div id="conteudo_news_list">
                        <?php
                        if ($newsConteudo):
                            $tipoInp = ($idTipoConteudo == '10') ? 'radio' : 'checkbox';
                            foreach ($newsConteudo as $c):
                                ?>
                                <div>
                                    <input type="<?php echo $tipoInp; ?>" value="<?php echo $c['idConteudo']; ?>" name="conteudo[]" <?php if (in_array($c['idConteudo'], $conteudo)): ?>checked="checked"<?php endif; ?>>[<?php echo $c['nmTipoConteudo']; ?>] <?php echo $c['nmTituloConteudo']; ?>
                                </div>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="15%" valign="top">Ativar?</td>
            <td valign="top">
                <select name="inAtivo" id="inAtivo" style="top:auto">
                    <option value="1" <?php if ($qryEnvio[0]["inAtivo"] == 1 || !strlen($qryEnvio[0]["inAtivo"])) {
                            echo 'selected';
                        } ?>>Sim</option>
                    <option value="0" <?php if ($qryEnvio[0]["inAtivo"] == 0 && strlen($qryEnvio[0]["inAtivo"])) {
                            echo 'selected';
                        } ?>>N&atilde;o</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="15%" valign="top">Grupos:</td>
            <td valign="top">
        <?php foreach ($qryGrupos as $g): ?>
                    <input type="checkbox" name="grupos[]" <?php echo (in_array($g['idGrupo'], $grupos) ? 'checked="checked"' : ''); ?> value="<?php echo $g['idGrupo']; ?>"><?php echo $g['nmGrupo']; ?><br/>
<?php endforeach; ?>
            </td>
        </tr>
    </table>
    <br/>
    <div align="left">
<?php if (!strlen($_REQUEST["id"])) { ?>
            <button type="submit" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
            <input type="hidden" name="acao" value="cadastrarEnvio" />
<?php } else { ?>
            <button type="submit" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
            <button type="button" onClick="excluirItem(<?php echo $_REQUEST["id"]; ?>, 'controller/act-news', 'excluirEnvio', 'id')">Excluir</button>
            <button type="button" onClick="location.href = 'cad-news-emails';">Cadastrar Novo</button>
            <input type="hidden" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" name="acao" value="atualizarEnvio"/>
<?php } ?>
        <button type="button" onclick="location.href = 'menu-news-envio';">Voltar</button>
    </div>
    <br/>
</form>
<script type="text/javascript">
            function localizarConteudo() {
                bloquear = true;
                topSl = 0;
                $('#conteudo_news_list').css('top', 0).html('Aguarde...');
                $.ajax({
                    'type': 'POST',
                    'typeData': 'json',
                    'url': '<?php echo $url_raiz; ?>admin/controller/act-news',
                    'data': {'acao': 'buscarConteudos', 'idTipoConteudo': $('#idTipoConteudo').val()},
                    'success': function(data) {
                        $('#conteudo_news_list').empty();
                        var d = $.parseJSON(data);
                        if ($(d).length) {
                            var tipoInp = $('#idTipoConteudo').val() == '10' ? 'radio' : 'checkbox';
                            for (var id in d) {
                                $('#conteudo_news_list').append(
                                        '<div>' +
                                        '<input type="' + tipoInp + '" value="' + id + '" name="conteudo[]">[' + d[id].nmTipoConteudo + "] " + d[id].nmTituloConteudo +
                                        '</div>'
                                        );
                            }
                        } else {
                            $('#conteudo_news_list').html('Sem conteudo.');
                        }
                        bloquear = false;
                    }
                });
            }

//evento o rodinnha do mause :)

            $('#conteudo_news_list').mousewheel(function(event, delta) {
                if (delta < 0) {
                    anterior();
                } else if (delta > 0) {
                    proximo();
                }
                return false;
            });


            var topSl = 0;
            var proximo;
            var anterior;
            var bloquear = false;

            $('#up').css('cursor', 'pointer').click(proximo = function() {
                if (bloquear) {
                    return true;
                }
                if (topSl >= 0) {
                    return false;
                }
                topSl += 20;
                $('#conteudo_news_list').animate({'top': topSl}, 'fast', function() {
                });
            });

            $('#down').css('cursor', 'pointer').click(anterior = function() {
                if (bloquear) {
                    return true;
                }
                if ((topSl - $('#conteudo_news').outerHeight()) < ($('#conteudo_news_list').outerHeight() * (-1))) {
                    return false;
                }
                topSl -= 20;
                $('#conteudo_news_list').animate({'top': topSl}, 'fast', function() {
                });
            });
</script>