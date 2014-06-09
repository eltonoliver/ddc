<?php
$qryMenu = $db->query("SELECT * FROM tb_menu WHERE idMenu = " . $db->clean($_REQUEST["idMenu"]));
//Lista de Categorias dispon�veis
$qryCategorias = $db->query("SELECT * FROM tb_categoria WHERE inTipo = 3 ORDER BY nmCategoria ASC");

if ($qryMenu) {
    $idCategoria = $qryMenu[0]["idCategoria"];
} else if ($_REQUEST["idCategoria"]) {
    $idCategoria = $_REQUEST["idCategoria"];
} else {
    $idCategoria = '';
}

if ($idCategoria > 0) {

    $qryMenusPai = $db->query("SELECT * FROM tb_menu WHERE  idTipoMenu = 2 AND idCategoria = " . $db->clean($idCategoria) . " ORDER BY idMenu, nmMenu, ordemMenu");
    //$qryMenusPai = $db->query("SELECT * FROM tb_menu WHERE  idTipoMenu = 1 ORDER BY idMenu, nmMenu, ordemMenu");
    $qryConteudoCategoria = $db->query("SELECT nmListaTipoConteudo FROM tb_categoria WHERE idCategoria = " . $db->clean($idCategoria) . " ORDER BY nmCategoria ASC");
    $restricaoConteudo = '';
    if (strlen($qryConteudoCategoria[0]["nmListaTipoConteudo"]) > 0) {
        $restricaoConteudo = 'AND idTipoConteudo IN (' . $qryConteudoCategoria[0]["nmListaTipoConteudo"] . ')';
    }


    $qryConteudo = $db->query("SELECT * FROM vwconteudo WHERE inPublicar = 1 " . $restricaoConteudo . " ORDER BY nmTipoConteudo, nmTituloConteudo ASC");

    //$teste = geraSelectMenus($qryMenusPai);
    //echo '<select>';
    //echo $teste;
    //echo '</select>';
}
?>

<script type="text/javascript">
    function enviar(campo) {
        location.href = 'cad-menu-externo?idMenuPai=' + campo.value<?php echo $filtro; ?>;
    }

    function mudaOrdem(campo) {
        document.getElementById('ordemMenu').value = campo.value;
        document.getElementById('labelOrdem').innerHTML = campo.value;
    }
</script>
<form name="formGeral" id="formGeral" action="<?php echo $url_raiz; ?>admin/controller/act-menu-externo" method="post" enctype="multipart/form-data">

    <h1>Cadastro de Menus</h1>
    <br/>

    <?php include('sisMensagem.php'); ?>            
<!--<span class="destaqueForte">Enviar todos</span></span>-->
    <div id="cadMenuExterno1" style="display:inline;">
        <h2 class="separadador">Para qual seção do Site?</h2>
        <span class="destaque_italico">
            &raquo; Informe em qual seção do site este menu será exibido;
        </span>
        <br/>
        <table width="100%" border="0" align="center" class="tabelaForm">
            <tr>
                <td width="15%" valign="middle">Seção:</td>
                <td width="85%" valign="top">
                    <select name="idCategoria" id="idCategoria" onChange="proximoPassoMenu();">
                        <option value="">[Selecione]</option>
                        <?php for ($i = 0; $i < count($qryCategorias); $i++) { ?>
                            <option value="<?php echo $qryCategorias[$i]["idCategoria"]; ?>" <?php
                            if ($qryCategorias[$i]["idCategoria"] == $idCategoria) {
                                echo 'selected';
                            }
                            ?>><?php echo $qryCategorias[$i]["nmCategoria"]; ?></option>
                                <?php } ?>
                    </select> 

                    <?php if ($idCategoria == '') { ?>
                        <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: <?php $url_raiz; ?>menu - menus - externos - externos';">Voltar</button>
                    <?php } ?>
                </td>
            </tr> 
        </table> 
        <script type="text/javascript">
    function proximoPassoMenu() {
        var idCategoria = document.getElementById('idCategoria').value;
        location.href = '<?php $url_raiz; ?>cad-menu-externo?idCategoria=' + idCategoria;
    }
        </script>

    </div>

    <?php if ($idCategoria > 0) { ?>
        <br/>
        <div>

            <h2 class="separadador">Informações do Menu</h2>
            <div align="left">
                <?php if (!$qryMenu) { ?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                <?php } else { ?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir" id="btExcluir" onClick="excluirMenu('<?php echo $qryMenu["idMenu"]; ?>');">Excluir</button>
                    <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href = 'cad-menu-externo';">Cadastrar Novo</button>
                <?php } ?>
                <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href = 'menu-menus-externos';">Voltar</button>
            </div>
            <br/>


            <script type="text/javascript">
        function buscaOrdem(idMenu) {

            var idSecao = document.getElementById("idCategoria").value;

            jQuery.getJSON(
                    "<?php echo $url_raiz; ?>admin/controller/act-menu-externo?acao=buscaOrdem&idMenu=" + idMenu + '&idSecao=' + idSecao,
                    function(data) {
                        ordemMenu = data;

                        proximaOrdem = parseInt(ordemMenu.ordem) + 1;

                        $("#ordemMenu option").each(function(index, element) {

                            if ($(element).val() == proximaOrdem) {

                                $(element).attr('selected', true);

                            }

                        });


                    }
            );

        }
            </script>

            <table width="100%" border="0" align="center" class="tabelaForm">


                <tr>
                    <td width="15%" valign="top">Menu Pai:</td>
                    <td colspan="2" valign="top">
                        <select name="idMenuPai" id="idMenuPai"  style="top:auto;" onchange="buscaOrdem(this.value);">
                            <option value="">Selecione</option>
                            <option value="0" <?php
                            if ($qryMenu[0]["idMenuPai"] == 0) {
                                echo 'selected';
                            }
                            ?>>Sem pai</option>
                            <option value="">-----------------</option>

                            <?php
                            if ($qryMenusPai) {
                                for ($i = 0; $i < count($qryMenusPai); $i++) {
                                    ?>
                                    <option value="<?php echo $qryMenusPai[$i]["idMenu"]; ?>" <?php
                                    if ($qryMenusPai[$i]["idMenu"] == $qryMenu[0]["idMenuPai"]) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $qryMenusPai[$i]["descricaoMenu"]; ?></option>
                                            <?php
                                        }//Fim-for
                                    }//Fim-if
                                    ?>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <td width="15%">Nome:</td>
                    <td colspan="2"><input name="nmMenu" id="nmMenu" size="60" maxlength="100" style="top:auto;" value="<?php echo $qryMenu[0]["nmMenu"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Descrição do Menu:</td>
                    <td colspan="2"><input name="descricaoMenu" id="descricaoMenu" size="60" maxlength="100" style="top:auto;" value="<?php echo $qryMenu[0]["descricaoMenu"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%" rowspan="2" valign="top">Tipo de Link</td>
                    <td colspan="2" valign="top">
                        <input type="radio" id="inTipoLink" name="inTipoLink" onclick="mudaLabel(this.value)" value="1" <?php
                        if ($qryMenu && $qryMenu[0]["inTipoLink"] == 1) {
                            echo 'checked="checked"';
                        } else if (!$qryMenu) {
                            echo 'checked="checked"';
                        }
                        ?>/> 
                        Conte&uacute;do 
                        <input type="radio" id="inTipoLink" name="inTipoLink" onclick="mudaLabel(this.value)" value="0" <?php
                        if ($qryMenu && $qryMenu[0]["inTipoLink"] == 0) {
                            echo 'checked="checked"';
                        }
                        ?>/>Link Exerno
                        <input type="radio" id="inTipoLink" name="inTipoLink" onclick="mudaLabel(this.value)" value="2" <?php
                        if ($qryMenu && $qryMenu[0]["inTipoLink"] == 2) {
                            echo 'checked="checked"';
                        }
                        ?>/>P&aacute;gina est&aacute;tica</td>
                </tr>
                <tr>
                    <td width="4%" valign="middle" align="center">Link</td>
                    <td width="81%" valign="top">
                        <script type="text/javascript">
        function mudaLabel(tipo) {
            if (tipo == 2) {
                $("#labelPagina").html('um nome página válida do sistema');
            } else {
                $("#labelPagina").html('uma URL válida');
            }


            if (tipo == 1) {
                $("#linkMenu").css('top', 'inherit');
                $("#idConteudo").css('top', 'auto');
                $("#divLinkMenu").fadeOut('fast', function() {
                    $("#divIdConteudo").fadeIn('fast');
                });
            } else {
                $("#idConteudo").css('top', 'inherit');
                $("#linkMenu").css('top', 'auto');
                $("#divIdConteudo").fadeOut('fast', function() {
                    $("#divLinkMenu").fadeIn('fast');
                });
            }


        }
                        </script>

                        <div id="divIdConteudo" <?php if ($qryMenu[0]["inTipoLink"] != 1): ?>style="display:none;"<?php endif; ?>>
                            <span class="destaque_italico">&raquo; Selecione um conteúdo cadastrado para ser associado como link do menu.</span>
                            <br/>
                            <select name="idConteudo" id="idConteudo"  style="top:auto;">
                                <option value="">[Selecione]</option>
                                <?php for ($i = 0; $i < count($qryConteudo); $i++) { ?>
                                    <option value="<?php echo $qryConteudo[$i]["idConteudo"]; ?>" <?php
                                    if ($qryConteudo[$i]["idConteudo"] == $qryMenu[0]["idConteudo"]) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo '[' . $qryConteudo[$i]["nmTipoConteudo"] . '] ' . $qryConteudo[$i]["nmTituloConteudo"]; ?></option>
                                        <?php } ?>

                            </select>
                        </div>
                        <div id="divLinkMenu" <?php if ($qryMenu[0]["inTipoLink"] == 1): ?>style="display:none;"<?php endif; ?>>
                            <span class="destaque_italico">&raquo; Digite 
                                <span id="labelPagina">
                                    <?php if ($qryMenu[0]["inTipoLink"] == 2): ?>
                                        um nome página válida do sistema
                                    <?php else: ?>
                                        uma URL válida
                                    <?php endif; ?>
                                </span>
                                para ser associado link externo do menu.</span>
                            <br/>
                            <input name="linkMenu" id="linkMenu" size="60" maxlength="200" style="top:0;" value="<?php echo $qryMenu[0]['linkMenu']; ?>"/>
                        </div>
                    </td>
                </tr>                
                <tr>
                    <td width="15%">Ordem</td>
                    <td colspan="2">
                        <select name="ordemMenu" id="ordemMenu"  style="top:auto;">
                            <option value="" selected>--</option>
                            <?php for ($i = 1; $i <= 50; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php if ($qryMenu[0]["ordemMenu"] == $i) echo 'selected="selected"' ?> ><?php echo $i; ?></option>
                            <?php } ?>
                        </select>                    
                    </td>
                </tr>
                <tr>
                    <td width="15%" valign="top">Publicar?</td>
                    <td colspan="2" valign="top">
                        <input type="radio" id="inExibir" name="inExibir" value="1" <?php
                        if ($qryMenu && $qryMenu[0]["inExibir"] == 1) {
                            echo 'checked="checked"';
                        } else if (!$qryMenu) {
                            echo 'checked="checked"';
                        }
                        ?>/> Sim
                        <input type="radio" id="inExibir" name="inExibir" value="0" <?php
                        if ($qryMenu && $qryMenu[0]["inExibir"] == 0) {
                            echo 'checked="checked"';
                        }
                        ?>/> Não
                    </td>
                </tr>
            </table>

            <br/>
            <div align="left">
                <?php if (!$qryMenu) { ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                    <input type="hidden" id="acao" name="acao" value="Cadastrar" />
                <?php } else { ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirMenu('<?php echo $qryMenu[0]["idMenu"]; ?>');">Excluir</button>
                    <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href = 'cad-menu-externo';">Cadastrar Novo</button>
                    <input type="hidden" id="idMenu" name="idMenu" value="<?php echo $qryMenu[0]["idMenu"]; ?>" />
                    <input type="hidden" id="acao" name="acao" value="Atualizar" />
                <?php } ?>
                <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href = 'menu-menus-externos';">Voltar</button>
                <input type="hidden" id="idTipoMenu" name="idTipoMenu" value="2" /><!-- EXTERNO -->
            </div>
            <br/>
        </div>
    <?php } ?>

</form>