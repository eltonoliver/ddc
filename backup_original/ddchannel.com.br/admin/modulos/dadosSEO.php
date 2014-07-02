<form name="formGeral" id="formGeral" action="controller/act-s-e-o" method="post" enctype="multipart/form-data">

    <h1>Configurações de Busca</h1>
    <br/>

    <?php include('sisMensagem.php'); ?>

    <h2 class="separadador">Informa&ccedil;&otilde;es para Otimiza&ccedil;&atilde;o de Mecanismos de Busca (SEO)</h2>
    <table width="100%" border="0" align="center" class="tabelaForm">
        <tr>
            <td width="15%">Meta "keywords"</td>
            <td width="85%">
                <textarea name="nmMetaKeywords"cols="60" rows="5" id="nmMetaKeywords" wrap="virtual"><?php echo $geralConfig[0]["nmMetaKeywords"]; ?></textarea>                    
            </td>
        </tr>
        <tr>
            <td width="15%">Meta "description"</td>
            <td width="85%">
                <textarea name="nmMetaDescricao"cols="60" rows="5" id="nmMetaDescricao" wrap="virtual"><?php echo $geralConfig[0]["nmMetaDescricao"]; ?></textarea>                    
            </td>
        </tr>
        <tr>
            <td width="15%">Código do Google Analytics</td>
            <td width="85%">
                <textarea name="nmGoogleAnalytics"cols="60" rows="5" id="nmGoogleAnalytics" onKeyUp="limitadorCampo(this,1000,'contador4','msg_nmGoogleAnalytics');"  wrap="virtual"><?php echo $geralConfig[0]["nmGoogleAnalytics"]; ?></textarea>
            </td>
        </tr>
    </table>


    <br/>            
    <div align="left">
        <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
    </div>
    <br/>

</form>