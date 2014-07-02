<h1>Enviar Arquivos</h1>
<form name="formGeral" id="formGeral" action="controller/act-arquivos" method="post" enctype="multipart/form-data">
    <br/>
    <h2 class="separadador">Etapa <span class="destaqueVermelho">3</span> de 3: Associação de tags</h2>
    <span class="destaque_italico">&raquo; Clique em <span class="destaqueForte">Associar Tags</span> para adicionar as marcações nescessárias ao relacionamento do seu arquivo com outros conteúdos do site;<br/>
        &raquo; Voce pode <span class="destaqueForte">Editar</span> ou <span class="destaqueForte">Excluir</span> os arquivos, se necessário;<br/>
        &raquo; <span class="destaqueForte">Nao saia desta página</span> até que o processo seja concluído, para que os arquivos enviados nao sejam excluídos;<br/>
        &raquo; Após concluir, clique em <span class="destaqueForte">Finalizar</span>;</span>
    <br/>
    <br/>
    <div align="left">
        <button type="button" name="btEnviar" id="btEnviar" onClick="finalizar();">Finalizar</button>
        <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href = 'cad-arquivos?continuar&noTopoRodape=<?php echo $_REQUEST['noTopoRodape']; ?>';">Voltar</button>
    </div>

    <br/>
    <fieldset>
        <?php
        //new dBug($_SESSION["ID"]);
        //Busca todos os arquivos que foram enviados mas o processo ainda nao foi finalizado.
        $arq = $db->query("SELECT * FROM tb_arquivo WHERE idStatusGeral = 1"); // AND idUsuarioCadastro = '".$_SESSION["ID"]."'
        ?>
        <table width="100%" border="0" align="center" class="tbLista" id="tbListaArquivos">
            <tr class="tbTitulo">
                <td colspan="3" align="center">Op&ccedil;&otilde;es</td>
                <td width="10%" align="center">Miniatura</td>
                <td width="20%" align="left">Arquivo / Legenda</td>
                <td width="30%" align="left">Descri&ccedil;&atilde;o</td>
                <td align="left">Tags</td>
                <?php /* ?><td align="left" width="15%">�udio</td><?php */ ?>
            </tr>
            <?php
            $coluna = 1;
            //$listaIDS = "1";
            for ($i = 0; $i < count($arq); $i++) {

                if ($coluna % 2 == 0) {
                    $classe = 'tbNormal';
                } else {
                    $classe = 'tbNormalAlt';
                }

                $miniatura = $url_raiz . 'arquivos/enviados/thumbnails/' . $arq[$i]["nmNomeArquivo"];
                $pasta = pastaArquivo($arq[$i]["idTipoArquivo"]);
                $icone = $pasta["icone"];

                //new dbug($pasta);
                //exit;

                $arquivo = $url_raiz . 'arquivos/enviados/' . $pasta["pasta"] . '/' . $arq[$i]["nmNomeArquivo"];

                if ($icone == 'imagem') {
                    
                } else {
                    $miniatura = $url_raiz . 'arquivos/enviados/thumbnails/icones/' . $icone . '.png';
                }
                ?>
                <tr class="<?php echo $classe; ?>" id="arquivoReg_<?php echo $arq[$i]["idArquivo"]; ?>">
                    <td width="3%" align="center"><img style="cursor:pointer;" src="img/del.png" border="0" alt="Excluir Arquivo" title="Excluir Arquivo" onClick="removerArquivo('<?php echo $arq[$i]["idArquivo"]; ?>');"/></td>
                    <td width="3%" align="center"><img style="cursor:pointer;" src="img/editar2.png" border="0" alt="Editar Arquivo" title="Editar Arquivo" onClick="editarArquivo('<?php echo $arq[$i]["idArquivo"]; ?>')"/></td>
                    <td width="3%" align="center"><img src="img/iconeAdicao.png" border="0" alt="Adicionar Tags" title="Adicionar Tags" onClick="adicionarTag('<?php echo $arq[$i]["idArquivo"]; ?>', 'arquivo');" class="linkado"/></td>
                    <?php /* ?><td width="3%" align="center"><img src="<?php echo $url_raiz; ?>img/audio.png" height="40px;" border="0" alt="Adicionar �udio" title="Adicionar �udio" onClick="adcionarLinkExterno('','audio_<?php echo $arq[$i]["idArquivo"]; ?>','4','<?php echo $arq[$i]["idArquivo"]; ?>');" class="linkado"/></td><?php */ ?>
                    <td width="10%" align="center"><img src="<?php echo $miniatura; ?>" border="0"/></td>
                    <td width="20%" align="left">
                        <a href="<?php echo $arquivo; ?>" target="_blank" id="nmTituloArquivo_<?php echo $arq[$i]["idArquivo"]; ?>"><?php echo $arq[$i]["nmTituloArquivo"]; ?></a>
                        <input type="hidden" name="<?php echo $arq[$i]["idArquivo"]; ?>" id="<?php echo $arq[$i]["idArquivo"]; ?>" value="<?php echo $arq[$i]["idArquivo"]; ?>"/>
                    </td>
                    <td width="30%" align="left" id="nmDescricaoArquivo_<?php echo $arq[$i]["idArquivo"]; ?>"><?php echo $arq[$i]["nmDescricaoArquivo"]; ?></td>
                    <td align="left" id="tagsAdd_<?php echo $arq[$i]["idArquivo"]; ?>">
                        <?php
                        $tags = $db->query('SELECT c.* FROM tb_arquivo_categoria ac INNER JOIN tb_categoria c ON ac.idCategoria = c.idCategoria WHERE ac.idArquivo = ' . $arq[$i]["idArquivo"]);
                        if ($tags):
                            foreach ($tags as $t):
                                ?>
                                <div style="float:left; padding:5px;" id="<?php echo $arq[$i]["idArquivo"] . '_' . $t['idCategoria']; ?>">
                                    <img style="cursor:pointer" src="img/erro.gif" onClick="excluirTag('<?php echo $arq[$i]["idArquivo"] . '_' . $t['idCategoria']; ?>');">&nbsp;
                                    <?php echo $t['nmCategoria']; ?>
                                </div>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </td>
                    <?php /* ?><td id="audio_<?php echo $arq[$i]["idArquivo"]; ?>">
                      <?php

                      $a = $db->query('SELECT * FROM tb_arquivo WHERE idArquivo = '.$arq[$i]["nmLinkExterno"]);
                      echo $a[0]['nmTituloArquivo'];

                      ?>
                      </td><?php */ ?>
                </tr>
                <?php
                //$listaIDS = $listaIDS.','.$arq[$i]["idArquivo"];
                $coluna++;
            }
            ?>
        </table>      
    </fieldset>

    <br/>
    <div align="left">
        <input type="hidden" id="totalArquivos" name="totalArquivos" value="<?php echo count($arq); ?>"/> 
        <?php /* ?><input type="hidden" name="listaIDS" id="listaIDS" value="<?php echo $listaIDS; ?>" /><?php */ ?>
        <input type="hidden" name="acao" id="acao" value="Finalizar" />
        <button type="button" name="btEnviar2" id="btEnviar2" onClick="finalizar();">Finalizar</button>
        <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href = 'cad-arquivos?continuar&noTopoRodape=<?php echo $_REQUEST['noTopoRodape']; ?>';">Voltar</button>
    </div>
</form>

<script type="text/javascript">

            var retornoExcluir = function(d) {
                if (d.status) {
                    var idEl = '#' + d.idArquivo + '_' + d.idCategoria;
                    $(idEl).animate({opacity: 0}, 'slow', function() {
                        $(idEl).hide('slow', function() {
                            $(idEl).remove();
                        });
                    })
                }
            }

            function excluirTag(id) {
                var s = new String(id);
                var p = s.indexOf("_");
                var idArquivo = id.substr(0, p);
                var idTag = id.substr(p + 1, s.length);

                ajaxRequest.sendJson('controller/act-arquivos?acao=excluirTag&idArquivo=' + idArquivo + '&idCategoria=' + idTag, retornoExcluir);
            }

            function removerArquivo(id) {
                enableCache = false;
                displayMessage('excluir-arquivo?ajax=1&idArquivo=' + id, 188, 300);
            }

            function editarArquivo(id) {
                enableCache = false;
                displayMessage('editar-arquivo?ajax=1&idArquivo=' + id, 500, 400);
            }

            function adcionarLinkExterno(id, idNome, tipo, idArquivo) {
                enableCache = false;
                displayMessage('popup-adicionar-link-externo?ajax=1&id=' + id + '&idNome=' + idNome + '&tipo=' + tipo + '&idArquivo=' + idArquivo, 188, 400);
            }

            function finalizar() {
                enableCache = false;
                displayMessage('<?php echo $url_raiz ?>admin/popup-msg-aguarde?ajax=1', 188, 300);
                $('#DHTMLSuite_modalBox_contentDiv').css('opacity', 0);
                $('#DHTMLSuite_modalBox_contentDiv').animate({opacity: 1}, 'fast', function() {
                    //var listaIDS = $('#listaIDS').val();
                    //alert(listaIDS);
                    //return false;
                    //ajaxRequest.sendJson('actArquivos.php?acao=validarFinalizar&listaIDS='+listaIDS, function(d){
                    ajaxRequest.sendJson('controller/act-arquivos?acao=validarFinalizar', function(d) {
                        if (!d.status) {
                            $('#msgResposta').html('Nao foi poss�vel finalizar, verifique se os arquivos foram todos associados a pelo menos uma tag.');
                            $('#msgResposta').css('color', '#C83C12');
                        } else {
                            //ajaxRequest.sendJson('actArquivos.php?acao=finalizar&listaIDS='+listaIDS, function(d){
                            ajaxRequest.sendJson('controller/act-arquivos?acao=finalizar', function(d) {
                                if (d.status) {
                                    $('#msgResposta').html('Itens finalizados com sucesso.');
                                    setTimeout(function() {
<?php if (!$_REQUEST['noTopoRodape']): ?>
                                            window.location = 'menu-arquivos';
<?php else: ?>
                                            window.parent.$('#layer_popup_frame').css('display', 'none');
                                            window.parent.$('#layer_popup_conteudo_frame').css('display', 'none');
<?php endif; ?>
                                    }, 1000);
                                } else {
                                    $('#msgResposta').html('Nao foi poss�vel finalizar, favor tente novamente');
                                    $('#msgResposta').css('color', '#C83C12');
                                }
                            });
                        }
                    });
                });
            }
</script>

