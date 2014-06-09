<?php
$qryCliente = $db->query("SELECT * FROM tb_cliente WHERE idCliente = " . $_REQUEST["idCliente"]);
?>
<form name="formGeral" id="formGeral" action="controller/act-cliente" method="post" enctype="multipart/form-data">

    <h1>Cadastro de Clientes</h1>
    <br/>
    <?php if (isset($_GET["atualizado"])) { ?>
        <div class="msgBox">
            <p>Dados atualizados com sucesso!</p>
        </div>
    <?php } else if (isset($_GET["erro"])) { ?>
        <div class="msgBox">
            <p><img src="<?php echo $url_raiz; ?>/admin/img/alerta.gif" border="0"align="absbottom"/> Ocorreu um erro! Tente novamente ou contate o suporte.</p>
        </div>
    <?php } else if (isset($_GET["pedido"])) { ?>
        <div class="msgBox">
            <p><img src="<?php echo $url_raiz; ?>/admin/img/alerta.gif" border="0"align="absbottom"/> Informe os dados do cliente antes de cadastrar o Orçamento.</p>
        </div>
    <?php } ?>

    <h2 class="separadador">Dados do Cliente</h2>
    <div align="left">
        <?php if (!isset($_REQUEST["idCliente"])) { ?>
            <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
        <?php } else { ?>
            <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
            <button type="button" name="btExcluir" id="btExcluir"  onclick="excluirItem('<?php echo $qryPaginada[0]["idCliente"]; ?>', 'controller/act-cliente?', 'Excluir', 'idCliente');">Excluir</button>
            <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href = 'cadCliente.php';">Cadastrar Novo</button>
        <?php } ?>
        <?php if (isset($_GET["pedido"])) { ?>
            <input type="hidden" name="pedido" id="pedido" value="pedido">
        <?php } ?>
        <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href = 'menu-clientes';">Voltar</button>
    </div>
    <br/>

    <table width="100%" border="0" align="center" class="tabelaForm">
        <tr>
            <td width="15%">Nome:</td>
            <td width="85%">
                <input type="text" name="nmCliente" id="nmCliente" size="60" maxlength="200" style="top:auto" value="<?php echo $qryCliente[0]["nmCliente"]; ?>"/>
            </td>
        </tr>
        <tr>
            <td width="15%">Logradouro:</td>
            <td width="85%"><input name="nmEnderecoCliente" id="nmEnderecoCliente" size="60" maxlength="200" style="top:auto" value="<?php echo $qryCliente[0]["nmEnderecoCliente"]; ?>"/></td>
        </tr>
        <tr>
            <td width="15%">Número:</td>
            <td width="85%"><input name="nrNumeroCliente" id="nrNumeroCliente" size="10" maxlength="10" style="top:auto" value="<?php echo $qryCliente[0]["nrNumeroCliente"]; ?>"/></td>
        </tr>
        <tr>
            <td width="15%">Complemento:</td>
            <td width="85%"><input name="nmComplementoCliente" id="nmComplementoCliente" size="60" maxlength="200" value="<?php echo $qryCliente[0]["nmComplementoCliente"]; ?>"/></td>
        </tr>
        <tr> 
            <td width="15%">Bairro:</td>
            <td width="85%"><input name="nmBairroCliente" id="nmBairroCliente" size="60" maxlength="200" style="top:auto" value="<?php echo $qryCliente[0]["nmBairroCliente"]; ?>"/></td>
        </tr>
        <tr>
            <td width="15%">CEP:</td>
            <td width="85%">
                <input type="text" name="nrCepCliente" id="nrCepCliente" onKeyPress="MascaraCep(this);" maxlength="10" size="10" style="top:auto" value="<?php echo $qryCliente[0]["nrCepCliente"]; ?>"/>
            </td>
        </tr>
        <tr>
            <td width="15%">Cidade:</td>
            <td width="85%"><input name="nmCidadeCliente" id="nmCidadeCliente" size="60" maxlength="200" style="top:auto" value="<?php echo $qryCliente[0]["nmCidadeCliente"]; ?>"/></td>
        </tr>
        <tr>
            <td width="15%">Estado:</td>
            <td width="85%">
                <select name="nmUfCliente" id="nmUfCliente" style="top:auto">
                    <option value="">Selecione</option>
                    <?php for ($i = 0; $i < count($estados); $i++) { ?>
                        <option value="<?php echo $estados[$i][0]; ?>" <?php
                        if ($qryCliente[0]["nmUfCliente"] == $estados[$i][0]) {
                            echo 'selected';
                        }
                        ?>><?php echo $estados[$i][1]; ?></option>
                            <?php } ?>
                </select>                
            </td>
        </tr>
        <tr>
            <td width="15%">E-mail:</td>
            <td width="85%"><input name="nmEmailCliente" id="nmEmailCliente" size="60" maxlength="200" style="top:auto" value="<?php echo $qryCliente[0]["nmEmailCliente"]; ?>"/></td>
        </tr>
        <tr>
            <td width="15%">Telefone:</td>
            <td width="85%">
                <input name="nmTelefoneCliente" type="text" id="nmTelefoneCliente" style="top:auto" onKeyPress="MascaraTelefone(this);" size="21" maxlength="14" value="<?php echo $qryCliente[0]["nmTelefoneCliente"]; ?>"/>                    
            </td>
        </tr>
    </table>

    <br/>
    <div align="left">
        <?php if (!isset($_REQUEST["idCliente"])) { ?>
            <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
            <input type="hidden" id="acao" name="acao" value="Cadastrar" />
        <?php } else { ?>
            <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
            <button type="button" name="btExcluir2" id="btExcluir2"  onclick="excluirItem('<?php echo $qryPaginada[0]["idCliente"]; ?>', 'controller/act-cliente?', 'Excluir', 'idCliente');">Excluir</button>
            <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href = 'cadCliente.php';">Cadastrar Novo</button>
            <input type="hidden" id="idCliente" name="idCliente" value="<?php echo $qryCliente[0]["idCliente"]; ?>" /> 
            <input type="hidden" id="acao" name="acao" value="Atualizar" />
        <?php } ?>
        <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href = 'menu-clientes';">Voltar</button>
    </div>
    <br/>
</form>