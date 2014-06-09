<?php if (!$_REQUEST['buscarndo']): ?>
    <table bgcolor="#ffffff" width="100%" border="0" cellspacing="2" cellpadding="0" >
        <tr class="FonteTituloPOP">
            <td width="96%" style="color:#000;">&nbsp;Arquivo associado</td>
            <td width="2%"><img src="<?php echo $url_raiz; ?>admin/js/modalMessage/botFechar.png" onClick="closeMessage();" title="Fechar" onMouseOver="javascript: this.style.cursor = 'pointer';"></td>
        </tr>
    </table>
    <div id="tb_img_pp_select">
    <?php endif; ?>
    <?php
    $filtro = $_REQUEST['nmTituloArquivo'] ? (' AND nmTituloArquivo LIKE ' . $db->clean('%' . $_REQUEST['nmTituloArquivo'] . '%')) : '';
    $tipo = $_REQUEST['tipo'];
    if ($tipo == 3) {
        $qryImagens = $db->query('SELECT * FROM tb_arquivo WHERE idTipoArquivo != 1' . $filtro . ' ORDER BY idArquivo DESC');
    } else {
        $qryImagens = $db->query('SELECT * FROM tb_arquivo WHERE idTipoArquivo NOT IN (6,1)' . $filtro . ' ORDER BY idArquivo DESC');
    }
    if ($qryImagens || $_REQUEST['buscarndo']):
        $larguraImg = '100';
        $qtdeImg = count($qryImagens);
        $larguraTotalImgs = $larguraImg * $qtdeImg;
        ?>

        <table border="0" width="400px;" cellpadding="0" cellpadding="0">
            <tr>
                <td valign="middle" align="center"><a style="cursor:pointer; color:#000000; font-size:25px;" onclick="anterior();">&laquo;</a></td>
                <td>
                    <?php if ($qryImagens): ?>
                        <div style="height:90px; width:300px; margin:auto; overflow:hidden;">
                            <div id="slider" style="width:<?php echo $larguraTotalImgs; ?>px; position: relative;">
                                <?php
                                foreach ($qryImagens as $img):

                                    $miniatura = $url_raiz . 'arquivos/enviados/thumbnails/' . $img["nmNomeArquivo"];
                                    $pasta = pastaArquivo($img["idTipoArquivo"]);
                                    $icone = $pasta["icone"];

                                    $arquivo = $url_raiz . 'arquivos/enviados/' . $pasta["pasta"] . '/' . $img["nmNomeArquivo"];

                                    if ($icone == 'imagem') {
                                        
                                    } else {
                                        $miniatura = $url_raiz . 'arquivos/enviados/thumbnails/icones/' . $icone . '.png';
                                    }
                                    ?>
                                    <div style="width:90px; padding:5px; float:left;">
                                        <p align="center">
                                            <img title=" <?php echo utf8_encode($img['nmTituloArquivo']); ?>" src="<?php echo $miniatura; ?>" style="cursor:pointer;" onclick="selectionaImagem('<?php echo $img['idArquivo']; ?>', '<?php echo utf8_encode($img['nmTituloArquivo']); ?>')"/></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p align="center" style="margin-top:50px;">N&atilde;o h&aacute; imagens cadastradas</p>
                    <?php endif ?>
                </td>
                <td valign="middle" align="center"><a style="cursor:pointer; color:#000000; font-size:25px;" onclick="proximo();">&raquo;</a></td>
            <tr>
            <tr>
                <td colspan="3" valign="middle" align="center"	>
                    <input type="text"  style="width:250px;" value="<?php echo $_REQUEST['nmTituloArquivo']; ?>" id="nm_n_arq"><button onclick="buscar();">Buscar</button>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
                var larguraImg = '<?php echo $larguraImg; ?>';
                var qtdeImg = '<?php echo $qtdeImg; ?>';
                var larguraTotalImgs = '<?php echo $larguraTotalImgs; ?>';
                var index = 3;
                var leftSlider = 0;
        </script>
    <?php else: ?>
        <p align="center" style="margin-top:50px;">N&atilde;o h&aacute; arquivos<br/><br/></p>
    <?php endif; ?>
    <?php if (!$_REQUEST['buscarndo']): ?>
    </div>
    <div id="loading" style="display:none;"><p align="center" style="margin-top:50px;" id="msgResposta"><img width="60" src='img/ajax-loader.gif'></p></div>
    <script type="text/javascript">

        var id = '<?php echo $_REQUEST['id']; ?>';
        var idNome = '<?php echo $_REQUEST['idNome']; ?>';
        var idArquivo = '<?php echo $_REQUEST['idArquivo']; ?>';
        var urlImg = '<?php echo $url_raiz . 'img/'; ?>';
        var urlActArquivos = '<?php echo $url_raiz . 'admin/controller/act-arquivos?'; ?>';
        var tipo = '<?php echo $tipo; ?>';

        function proximo() {
            if ((leftSlider - (larguraImg * index)) <= (-1) * larguraTotalImgs) {
                return false;
            }
            leftSlider = leftSlider - (larguraImg * index);
            $('#slider').animate({left: leftSlider}, 'slow');
        }

        function anterior() {
            if ((leftSlider + (larguraImg * index)) > 0) {
                return false;
            }
            leftSlider = leftSlider + (larguraImg * index);
            $('#slider').animate({left: leftSlider}, 'slow');
        }

        function selectionaImagem(idLLL, nmNomeArquivo) {
            $('#' + idNome).html(nmNomeArquivo);
            if (idArquivo) {
                ajaxRequest.sendJson(urlActArquivos + 'idArquivo=' + idArquivo + '&idAudio=' + idLLL + '&acao=adicionarAudio');
            } else {
                $('#' + id).val(idLLL);
            }
            closeMessage();
        }

        function buscar() {
            var d = {'buscarndo': 1, 'nmTituloArquivo': $('#nm_n_arq').val(), 'tipo': tipo}
            $('#tb_img_pp_select').css('display', 'none');
            $('#loading').css('display', 'inline');

            $.ajax({
                url: '<?php echo $url_raiz; ?>admin/popup-adicionar-link-externo?ajax=1',
                type: 'POST',
                dataType: 'html',
                data: d,
                success: function(data) {
                    $('#tb_img_pp_select').html(data);
                    $('#tb_img_pp_select').css('display', 'inline');
                    $('#loading').css('display', 'none');
                }
            });
        }
    </script>
<?php endif; ?>