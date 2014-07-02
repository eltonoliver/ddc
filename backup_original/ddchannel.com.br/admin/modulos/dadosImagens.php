<form name="formGeral" id="formGeral" action="controller/act-imagens" method="post" enctype="multipart/form-data">
    <h1>Configurações de Imagens</h1>
    <br/>
    <?php include('sisMensagem.php'); ?>

    <h2 class="separadador">Tamanhos de imagens</h2>
    <div align="left">
        <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" class="adicionarFormato">Adicionar Formato</button>
    </div>
    <br/>
    <table border="0" align="left" class="tabelaForm">
        <?
        $qryImagens = $db->query("SELECT * FROM  tb_dados_imagens");
        $i = 0;
        foreach ($qryImagens as $imagem) {
            ?>
            <tr>
                <td><img src="<?php echo $url_raiz; ?>admin/img/del.png" class="excluirFormato" title="Excluir" alt="Excluir"/></td>
                <td>Nome:</td>
                <td><input name="nome[]" size="20" maxlength="20" style="top:auto" value="<?php echo $imagem["nome"]; ?>"/></td>
                <td>Largura:</td>
                <td><input name="largura[]" size="10" maxlength="4" style="top:auto" value="<?php echo $imagem["largura"]; ?>"/></td>
                <td>Altura:</td>
                <td><input name="altura[]" size="10" maxlength="4" style="top:auto" value="<?php echo $imagem["altura"]; ?>"/></td>
                <td>Recortar:</td>
                <td>
                    <input type="radio" name="crop<?php echo $i ?>" style="top:auto" value="1" <?php if ($imagem["crop"] == 1): ?>checked="checked"<?php endif; ?>/>Sim
                    <input type="radio" name="crop<?php echo $i ?>" style="top:auto" value="0" <?php if ($imagem["crop"] == 0): ?>checked="checked"<?php endif; ?>/>Não
                </td>
            </tr>
            <tr>
                <td colspan="9"><br/></td>
            </tr>
            <?
            $i++;
        }
        ?>
    </table>
    <div class="clearBoth"></div>
    <div align="left">        
        <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
        <button type="button" class="adicionarFormato">Adicionar Formato</button>
    </div>
    <br/>
</form>
<table style="display: none;">
    <tbody id="dadosImagem">
        <tr>
            <td></td>
            <td>Nome:</td>
            <td><input name="nome[]" size="20" maxlength="20" style="top:auto" value=""/></td>
            <td>Largura:</td>
            <td><input name="largura[]" size="10" maxlength="4" style="top:auto" value=""/></td>
            <td>Altura:</td>
            <td><input name="altura[]" size="10" maxlength="4" style="top:auto" value=""/></td>
            <td>Recortar:</td>
            <td>
                <input type="radio" name="crop" style="top:auto" value="1"/>Sim
                <input type="radio" name="crop" style="top:auto" value="0"/>Não
            </td>
        </tr>
        <tr>
            <td colspan="9"><br/></td>
        </tr>
    </tbody>
</table>
<script>
            $(".adicionarFormato").click(function() {
                $("#dadosImagem input[type=radio]").attr('name', 'crop' + $(".tabelaForm tr").length / 2);
                $(".tabelaForm").append($("#dadosImagem").html());
            });
            $(".excluirFormato").mouseover(function() {
                this.style.cursor = 'pointer';
            });
            $(".excluirFormato").click(function() {
                $(this).parents("tr").eq(0).remove();
            });
</script>