<?php
$qryCategorias = $db->query("SELECT * FROM tb_categoria WHERE inTipo = 2 ORDER BY nmCategoria");
$qryTipoArquivo = $db->query("SELECT * FROM tb_tipo_arquivo ORDER BY nmTipoArquivo");
?>

<?php
//PREPARA�AO DO PAGINADOR
//Define o total de registros por p�gina
$limite = 20;

//Pega o n�mero da p�gina que vem pela URL
$pagina = $_GET['pag'];

//Seta a p�gina inicial
if (!$pagina) {
    $pagina = 1;
}

//Calcula os registros inicial e final as serem pesquisados no banco de dados
$inicio = ($pagina * $limite) - $limite;
?>

<?php
//QUERY PAGINADA
//Exemplo: "SELECT * FROM nome_da_tabela LIMIT $inicio,$limite"
//Seta um filtro vazio
$filtro = '';

//Se veio o tipo no FORM ou URL, adiciona ao filtro
if (isset($_REQUEST["nmTituloArquivo"]) && strlen($_REQUEST["nmTituloArquivo"]) > 0) {
    $nmTituloArquivo = $_REQUEST["nmTituloArquivo"];
    $filtro .= ' AND nmTituloArquivo like ' . $db->clean('%' . $nmTituloArquivo . '%');
}

if (isset($_REQUEST["idTipoArquivo"]) && strlen($_REQUEST["idTipoArquivo"]) > 0) {
    $idTipoArquivo = $_REQUEST["idTipoArquivo"];
    $filtro .= " AND idTipoArquivo = " . $db->clean($idTipoArquivo);
}

if (isset($_REQUEST["idArquivo"]) && strlen($_REQUEST["idArquivo"]) > 0) {
    $idArquivo = $_REQUEST["idArquivo"];
    $filtro .= " AND idArquivo = " . $db->clean($idArquivo);
}


if (isset($_REQUEST["idCategoria"]) && strlen($_REQUEST["idCategoria"]) > 0) {
    $idCategoria = $_REQUEST["idCategoria"];

    $strTags = "SELECT DISTINCT(idArquivo) FROM tb_arquivo_categoria WHERE idCategoria = " . $db->clean($idCategoria);
    $qryTags = $db->query($strTags);
    $listaCategoria = campoMatrizParaLista('', $qryTags, 'idArquivo');

    if (strlen($listaCategoria) > 0) {
        $filtro .= " AND idArquivo in (" . $listaCategoria . ")";
    }
}


//Busca o total de registros da consulta nao paginada
$qrTotal = "SELECT COUNT(idArquivo) as total_registros FROM tb_arquivo WHERE idStatusGeral = 2" . $filtro;

$total_registros = $db->query($qrTotal);
$total_registros = $total_registros[0]["total_registros"];

if (!$total_registros) {
    $total_registros = 0;
}

$qryCont = "
			SELECT 		*
					
			FROM 		tb_arquivo
			
			WHERE 		idStatusGeral = 2
			
			" . $filtro . " 
			ORDER BY 	idArquivo DESC
			LIMIT 		" . $inicio . "," . $limite . "		
		";
//new dBug($qryCont);

$arq = $db->query($qryCont);
?>        
<form name="formGeral" id="formGeral" action="menu-arquivos" method="post">
    <h1>Manutenção de Arquivos</h1>
    <br/>

    <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
    <div align="left">
        <table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td width="7%">Filtrar por ID</td>
                <td width="93%"><input name="idArquivo" id="idArquivo" size="10" maxlength="20" style="top:auto" value="<?php echo $idArquivo; ?>"/></td>
            </tr>
            <tr>
                <td width="7%">Filtrar por Nome</td>
                <td width="93%"><input name="nmTituloArquivo" id="nmTituloArquivo" size="30" maxlength="200" style="top:auto" value="<?php echo $nmTituloArquivo; ?>"/></td>
            </tr>
            <tr>
                <td width="7%">Filtrar por Tipo</td>
                <td width="93%">
                    <select name="idTipoArquivo" id="idTipoArquivo"  style="top:auto">
                        <option value="">Todos</option>
<?php for ($i = 0; $i < count($qryTipoArquivo); $i++) { ?>
                            <option value="<?php echo $qryTipoArquivo[$i]["idTipoArquivo"]; ?>" <?php if ($qryTipoArquivo[$i]["idTipoArquivo"] == $idTipoArquivo) {
        echo 'selected';
    } ?>><?php echo $qryTipoArquivo[$i]["nmTipoArquivo"]; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="7%">Filtrar por Tag</td>
                <td width="93%">
                    <select name="idCategoria" id="idCategoria"  style="top:auto">
                        <option value="">Todas</option>
                        <?php for ($i = 0; $i < count($qryCategorias); $i++) { ?>
                            <option value="<?php echo $qryCategorias[$i]["idCategoria"]; ?>" <?php if ($qryCategorias[$i]["idCategoria"] == $idCategoria) {
                            echo 'selected';
                        } ?>><?php echo $qryCategorias[$i]["nmCategoria"]; ?></option>
<?php } ?>
                    </select>
                </td>
            </tr>
        </table>
        <button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
        <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href = 'cad-arquivos';">Enviar Arquivos</button>

    </div>
    <br/>
</form>

<?php if ($total_registros == 0) { //Se a consulta voltou sem nenhum resultado. ?>
    <div class="msgBox">
        <p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/> Nenhum resultado para a consulta realizada.</p>
    </div>

<?php } else { //Se foram encontrados resultados.  ?>

    <div align="center">
        <?php
        //NAVEGA�AO DO PAGINADOR
        //Calcula o total de p�ginas
        $total_paginas = ceil($total_registros / $limite);

        //Define a p�gina de direcionamento dos links
        //Seta um filtro vazio
        $filtro2 = '';

        //Se est� definido o tipo, adiciona consulta do ano ao filtro
        if (strlen($_REQUEST["nmTituloArquivo"]) > 0) {
            $filtro2 = $filtro2 . '' . $filtro2 = '&nmTituloArquivo=' . $nmTituloArquivo;
        }
        if (strlen($_REQUEST["idTipoArquivo"]) > 0) {
            $filtro2 = $filtro2 . '' . $filtro2 = '&idTipoArquivo=' . $idTipoArquivo;
        }
        if (strlen($_REQUEST["idCategoria"]) > 0) {
            $filtro2 = $filtro2 . '' . $filtro2 = '&idCategoria=' . $idCategoria;
        }
        if (strlen($_REQUEST["idArquivo"]) > 0) {
            $filtro2 = $filtro2 . '' . $filtro2 = '&idArquivo=' . $idArquivo;
        }
        //Nome da p�gina 
        $stringPagina = '?pesquisa' . $filtro2;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
        ?>
    </div>
    <fieldset>
        <table width="100%" border="0" align="center" class="tbLista" id="tbListaArquivos">
            <tr class="tbTitulo">
                <td width="12%" colspan="4" align="center">Op&ccedil;&otilde;es</td>
                <td width="10%" align="center">Miniatura</td>
                <td width="5%" align="center">ID</td>
                <td width="10%" align="left">Arquivo / Legenda</td>
                <td width="30%" align="left">Descri&ccedil;&atilde;o</td>
                <td align="left">Tags</td>
                <?php /* ?> <td align="left" width="15%">�udio</td><?php */ ?>
            </tr>
            <?php
            $coluna = 1;
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
                    <td width="3%" align="center">
                        <img src="<?php echo $url_raiz; ?>admin/img/<?php echo ($arq[$i]["inPublicar"] ? 'bola_verde.png' : 'bola_cinza.png'); ?>" 
                             border="0" 
                             class="linkado" 
                             onclick="ativarArquivo('<?php echo $arq[$i]["idArquivo"]; ?>', this);"
        <?php echo ($arq[$i]["inPublicar"] ? 'publicar="true"' : 'publicar="false"'); ?>
                             />
                    </td>
                    <td width="10%" align="center"><img src="<?php echo $miniatura; ?>" border="0"/></td>
                    <td width="5%" align="center"><?php echo $arq[$i]["idArquivo"]; ?></td>
                    <td width="10%" align="left">
                        <a href="<?php echo $arquivo; ?>" target="_blank" id="nmTituloArquivo_<?php echo $arq[$i]["idArquivo"]; ?>"><?php echo $arq[$i]["nmTituloArquivo"]; ?></a>
                        <input type="hidden" name="<?php echo $arq[$i]["idArquivo"]; ?>" id="<?php echo $arq[$i]["idArquivo"]; ?>" value="<?php echo $arq[$i]["idArquivo"]; ?>"/>
                    </td>
                    <td width="21%" align="left" id="nmDescricaoArquivo_<?php echo $arq[$i]["idArquivo"]; ?>"><?php echo $arq[$i]["nmDescricaoArquivo"]; ?></td>
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
                        <?php /* ?> <td id="audio_<?php echo $arq[$i]["idArquivo"]; ?>">
                          <?php

                          $a = $db->query('SELECT * FROM tb_arquivo WHERE idArquivo = '.$arq[$i]["nmLinkExterno"]);
                          echo $a[0]['nmTituloArquivo'];

                          ?>
                          </td><?php */ ?>
                </tr>
                    <?php $coluna++;
                } ?>
        </table>      
    </fieldset>

    </form>
    <br/>            
    <div align="center">
    <?php
    //NAVEGA�AO DO PAGINADOR
    //Chama a fun�ao que monta a exibi�ao do paginador
    navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
    ?>
    </div>

    <?php } ?>

<br/>
<script type="text/javascript">

            function ativarArquivo(id, o) {
                var s = 0;
                if ($(o).attr('publicar') == 'true') {
                    s = 0;
                    $(o).attr('publicar', 'false');
                    $(o).attr('src', '<?php echo $url_raiz; ?>admin/img/bola_cinza.png');
                } else {
                    s = 1;
                    $(o).attr('publicar', 'true');
                    $(o).attr('src', '<?php echo $url_raiz; ?>admin/img/bola_verde.png');
                }

                $.ajax({
                    type: 'POST',
                    url: '<?php echo $url_raiz; ?>admin/controller/act-arquivos',
                    data: {status: s, acao: 'AtivarDesativar', idArquivo: id}
                });
            }

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
                ajaxRequest.sendJson('<?php echo $url_raiz; ?>admin/controller/act-arquivos?acao=excluirTag&idArquivo=' + idArquivo + '&idCategoria=' + idTag, retornoExcluir);
            }

            function removerArquivo(id) {
                enableCache = false;
                displayMessage('<?php echo $url_raiz; ?>admin/excluir-arquivo?ajax=1&idArquivo=' + id, 188, 300);
            }

            function editarArquivo(id) {
                enableCache = false;
                displayMessage('<?php echo $url_raiz; ?>admin/editar-arquivo?ajax=1&idArquivo=' + id, 500, 400);
            }

            function adcionarLinkExterno(id, idNome, tipo, idArquivo) {
                enableCache = false;
                displayMessage('<?php echo $url_raiz; ?>admin/popup-adicionar-link-externo?ajax=1&id=' + id + '&idNome=' + idNome + '&tipo=' + tipo + '&idArquivo=' + idArquivo, 188, 400);
            }

            function finalizar() {
                enableCache = false;
                displayMessage('popupMsgaguarde.php?ajax=1', 188, 300);
                $('#DHTMLSuite_modalBox_contentDiv').css('opacity', 0);
                $('#DHTMLSuite_modalBox_contentDiv').animate({opacity: 1}, 'fast', function() {
                    ajaxRequest.sendJson('<?php echo $url_raiz; ?>controller/act-arquivos?acao=validarFinalizar', function(d) {
                        if (!d.status) {
                            $('#msgResposta').html('Não foi possível finalizar, verifique se os arquivos foram todos associados a pelo menos uma tag.');
                            $('#msgResposta').css('color', '#C83C12');
                        } else {
                            ajaxRequest.sendJson('<?php echo $url_raiz; ?>controller/act-arquivos?acao=finalizar', function(d) {
                                if (d.status) {
                                    $('#msgResposta').html('Itens finalizados com sucesso.');
                                    setTimeout(function() {
                                        window.location = 'admin';
                                    }, 1000);
                                } else {
                                    $('#msgResposta').html('Não foi possível finalizar, favor tente novamente');
                                    $('#msgResposta').css('color', '#C83C12');
                                }
                            });
                        }
                    });
                });
            }
</script>     