<?php
$qryUsuario = $db->query("SELECT * FROM tb_usuario WHERE idUsuario = " . $db->clean($_REQUEST["idUsuario"]));
$qryPerfis = $db->query("SELECT * FROM tb_perfil WHERE inAtivo = 1 ORDER BY idPerfil DESC");
?>
<script type="text/javascript">
    function mudaTipo() {
        var tipo = document.getElementById("idTipoConteudo").value;
        location.href = 'cad-usuari o ? idTipoConteudo=' + tipo;
    }
</script>

<form name="formGeral" id="formGeral" action="controller/act-usuario" method="post" enctype="multipart/form-data">

    <h1>Cadastro de Usu&aacute;rios</h1>
    <br/>

    <?php if (isset($_GET["atualizado"])) { ?>
        <div class="msgBox">
            <p>Dados atualizados com sucesso!</p>
        </div>
    <?php } else if (isset($_GET["erro"])) { ?>
        <div class="msgBox">
            <p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/> Ocorreu um erro! Tente novamente ou contate o suporte.</p>
        </div>
    <?php } ?>

    <h2 class="separadador">Dados do Usu&aacute;rio</h2>
    <div align="left">
        <?php if (!isset($_REQUEST["idUsuario"])) { ?>
            <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
        <?php } else { ?>
            <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
            <?php
            session_start();
            if (($_SESSION["PERFIL"] == 1) && ( isset($qryUsuario[0]["idPerfil"]) && $qryUsuario[0]["idPerfil"] != 1)) {
                ?>
                <button type="button" name="btExcluir" id="btExcluir" onClick="excluirItem('<?php echo $qryUsuario[0]["idUsuario"]; ?>', 'controller/act-usuario', 'Excluir', 'idUsuario');">Excluir</button>
            <?php } ?>
            <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href = 'c ad- usu ar io';">Cadastrar Novo</button>
        <?php } ?>
        <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href = 'menu-usuarios';">Voltar</button>
    </div>
    <br/>

    <table width="100%" border="0" align="center" class="tabelaForm">
        <tr>
            <td width="15%">Nome:</td>
            <td width="85%">
                <?php if (isset($qryUsuario[0]["idPerfil"]) && $qryUsuario[0]["idPerfil"] == 1) { ?>
                    <span class="destaque_italico"><?php echo $qryUsuario[0]["nmUsuario"]; ?></span>
                    <br/>
                    <input type="hidden" name="nmUsuario" id="nmUsuario" size="60" maxlength="100" style="top:auto" value="<?php echo $qryUsuario[0]["nmUsuario"]; ?>"/>
                <?php } else { ?>
                    <input name="nmUsuario" id="nmUsuario" size="60" maxlength="100" style="top:auto" value="<?php echo $qryUsuario[0]["nmUsuario"]; ?>"/>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="15%">Perfil:</td>
            <td width="85%">
                <?php if (isset($qryUsuario[0]["idPerfil"]) && $qryUsuario[0]["idPerfil"] == 1) { ?>
                    <span class="destaque_italico">&raquo; Por questoes de segurança, o perfil do usuário administrador não pode ser modificado.</span>
                    <br/>
                    <input type="hidden" id="idPerfil" name="idPerfil" value="<?php echo $qryUsuario[0]["idPerfil"]; ?>" style="top:auto"/>

                <?php } else if ($_SESSION["perfil"] == 1) { ?>
                    <select name="idPerfil" id="idPerfil" style="top:auto">
                        <option value="">[Selecione]</option>
                        <?php for ($i = 0; $i < count($qryPerfis); $i++) { ?>
                            <option value="<?php echo $qryPerfis[$i]["idPerfil"]; ?>" <?php
                            if ($qryPerfis[$i]["idPerfil"] == $qryUsuario[0]["idPerfil"]) {
                                echo 'selected';
                            }
                            ?>><?php echo $qryPerfis[$i]["nmPerfil"]; ?></option>
                                <?php } ?>
                    </select>

                <?php } else { ?>
                    <select name="idPerfil" id="idPerfil" style="top:auto">
                        <option value="">[Selecione]</option>
                        <?php for ($i = 0; $i < count($qryPerfis); $i++) { ?>
                            <?php if ($qryPerfis[$i]["idPerfil"] != 1) { ?>
                                <option value="<?php echo $qryPerfis[$i]["idPerfil"]; ?>" <?php
                                if ($qryPerfis[$i]["idPerfil"] == $qryUsuario[0]["idPerfil"]) {
                                    echo 'selected';
                                }
                                ?>><?php echo $qryPerfis[$i]["nmPerfil"]; ?></option>
                                    <?php } ?>
                                <?php } ?>
                    </select>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="15%">Login:</td>
            <td width="85%">
                <input name="nmLogin" id="nmLogin" size="20" maxlength="50" style="top:auto" value="<?php echo $qryUsuario[0]["nmLogin"]; ?>"/>
            </td>
        </tr>
        <tr>
            <td width="15%"><?php if (!isset($_REQUEST["idUsuario"])) { ?>Senha:<?php } else { ?>Nova Senha?<?php } ?></td>
            <td width="85%">
                <?php if (!isset($_REQUEST["idUsuario"])) { ?>
                    <input type="password" name="nmSenha" id="nmSenha" size="20" maxlength="50" style="top:auto"/>
                    <div id="progressbar"><div id="progress"></div><div id="retornoSenha"></div></div>
                <?php } else { ?>
                    <span class="destaque_italico">&raquo; Preencha este campo com sua nova senha, SOMENTE se desejar modificar sua senha atual.</span>
                    <br/>
                    <span class="destaque_italico">&raquo; Mínimo de 8 caracteres contendo letras, números e caracteres especiais.</span>
                    <br/>
                    <input type="password" name="nmNovaSenha" id="nmNovaSenha" size="20" maxlength="25"/>
                    <div id="progressbar"><div id="progress"></div><div id="retornoSenha"></div></div>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="15%">Confirmar <?php if (!isset($_REQUEST["idUsuario"])) { ?>Senha:<?php } else { ?>Nova Senha?<?php } ?></td>
            <td width="85%">
                <?php if (!isset($_REQUEST["idUsuario"])) { ?>
                    <input type="password" name="confirmacaoSenha" id="confirmacaoSenha" size="20" maxlength="25" style="top:auto"/>
                    <span id="retornoConfirmacaoSenha"></span>
                <?php } else { ?>
                    <span class="destaque_italico">&raquo; Preencha este campo com sua nova senha, SOMENTE se desejar modificar sua senha atual.</span>
                    <br/>
                    <input type="password" name="confirmacaoNovaSenha" id="confirmacaoNovaSenha" size="20" maxlength="25"/>
                    <span id="retornoConfirmacaoSenha"></span>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="15%">E-mail:</td>
            <td width="85%"><input name="nmEmailUsuario" id="nmEmailUsuario" size="60" maxlength="200" style="top:auto" value="<?php echo $qryUsuario[0]["nmEmailUsuario"]; ?>"/></td>
        </tr>
        <tr>
            <td width="15%">Ativo?</td>
            <td width="85%">
                <?php if (isset($qryUsuario[0]["idPerfil"]) && $qryUsuario[0]["idPerfil"] == 1) { ?>
                    <span class="destaque_italico">&raquo; Por questoes de segurança, o usuário "Administrador" não pode ser excluído nem desativado.</span>
                    <br/>
                    <input type="hidden" name="inAtivo" id="inAtivo" style="top:auto" value="<?php echo $qryUsuario[0]["inAtivo"]; ?>"/>
                <?php } else { ?>
                    <select name="inAtivo" id="inAtivo" style="top:auto" >
                        <option value="" selected>--</option>
                        <option value="1" <?php
                        if (isset($qryUsuario[0]["inAtivo"]) && $qryUsuario[0]["inAtivo"] == 1) {
                            echo 'selected';
                        }
                        ?>>Sim</option>
                        <option value="0" <?php
                        if (isset($qryUsuario[0]["inAtivo"]) && $qryUsuario[0]["inAtivo"] == 0) {
                            echo 'selected';
                        }
                        ?>>Nao</option>
                    </select>
                <?php } ?>
            </td>
        </tr>
    </table>

    <br/>
    <div align="left">
        <?php if (!isset($_REQUEST["idUsuario"])) { ?>
            <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
            <input type="hidden" id="acao" name="acao" value="Cadastrar" />
        <?php } else { ?>
            <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>

            <?php
            session_start();
            if (($qryUsuario && $qryUsuario[0]["idPerfil"] != 1) &&
                    ($qryUsuario && $qryUsuario[0]["idUsuario"] != $_SESSION["ID"])
            ) {
                ?>
                <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirItem('<?php echo $qryUsuario[0]["idUsuario"]; ?>', 'controller/act-usuario', 'Excluir', 'idUsuario');">Excluir</button>
            <?php } ?>

            <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href = 'cad-usuario';">Cadastrar Novo</button>
            <input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo $qryUsuario[0]["idUsuario"]; ?>" />
            <input type="hidden" id="acao" name="acao" value="Atualizar" />
        <?php } ?>
        <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href = 'menu -usuarios';">Voltar</button>
    </div>
    <br/>
</form>
<script type="text/javascript">
    $(function() {
        var submitForm = true;
        $("#nmSenha,#nmNovaSenha").complexify({}, function(valid, complexity) {
            if (!valid) {
                $('#progress').css({'width': complexity + '%'}).removeClass('progressbarValid').addClass('progressbarInvalid');
                var tamanhoSenha = $(this).val().length;
                if (tamanhoSenha) {
                    if (tamanhoSenha < 8)
                        $("#retornoSenha").html("Senha inválida");
                    else
                        $("#retornoSenha").html("Senha fraca");
                }
                $("#btEnviar,#btEnviar2").attr('disabled', 'disabled');
                submitForm = false;
            } else {
                $('#progress').css({'width': complexity + '%'}).removeClass('progressbarInvalid').addClass('progressbarValid');
                $("#retornoSenha").html("Senha válida");
                $("#btEnviar,#btEnviar2").removeAttr('disabled');
                submitForm = true;
            }
        });
        $("#confirmacaoSenha,#confirmacaoNovaSenha,#nmSenha,#nmNovaSenha").on("keyup", function() {
            if ($("#confirmacaoSenha").val() != $("#nmSenha").val() || $("#confirmacaoNovaSenha").val() != $("#nmNovaSenha").val()) {
                $("#retornoConfirmacaoSenha").html("Confirmação incorreta");
                $("#btEnviar,#btEnviar2").attr('disabled', 'disabled');
            }
            else {
                $("#retornoConfirmacaoSenha").empty();
                if (submitForm)
                    $("#btEnviar,#btEnviar2").removeAttr('disabled');
            }
        });
        if ($("#nmUsuario").val().length > 0)
            $("#btEnviar,#btEnviar2").removeAttr('disabled');
    });
</script>