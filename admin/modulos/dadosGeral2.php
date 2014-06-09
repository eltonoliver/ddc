<form name="formGeral" id="formGeral" action="controller/act-geral" method="post" enctype="multipart/form-data">
<h1>Configurações Gerais</h1>
<br/>
<?php include('sisMensagem.php'); ?>

<h2 class="separadador">Informações Gerais do Website</h2>
<div align="left">
    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
</div>
<br/>
<table width="100%" border="0" align="center" class="tabelaForm">
    <tr>
        <td width="15%">Nome da Empresa:</td>
        <td width="85%"><input name="nmEmpresa" id="nmEmpresa" size="60" maxlength="200" style="top:auto" value="<?php echo $geralConfig[0]["nmEmpresa"]; ?>"/></td>
    </tr>
    <tr>
        <td width="15%">Nome do Site:</td>
        <td width="85%">
            <input name="nmTituloSite" id="nmTituloSite" size="60" maxlength="200" style="top:auto" value="<?php echo $geralConfig[0]["nmTituloSite"]; ?>"/>
        </td>
    </tr>
    <tr>
        <td width="15%">Data de Lançamento:</td>
        <td width="85%">
            <?php if(!$geralConfig){ ?>
                <input type="text" name="dtLancamento" id="dtLancamento" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y');?>"/>
            <?php } else { ?>
                <input type="text" name="dtLancamento" id="dtLancamento" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y', strtotime($geralConfig[0]["dtLancamento"]));?>"/>
            <?php } ?>
        </td>
    </tr> 
    
    <!-- Marca Site -->
    <tr>
        <td width="15%" rowspan="2">Marca do Site<br/>(Principal):</td>
        <td width="85%">
            <br/>
            <img src="<?php echo $url_raiz.'img/'.$geralConfig[0]["nmLinkMarcaSite"]; ?>">
        </td>
    </tr>
    <tr>
      <td>Enviar  imagem?
        <input name="nmLinkMarcaSite" id="nmLinkMarcaSite" size="60" type="file"/>
      <span class="destaque">(180px X 52px)</span></td>
    </tr>
    
    <!-- Marca Menor -->
    <tr>
        <td width="15%" rowspan="2">Texto ou Marca menor<br/>(Principal / Governo):</td>
        <td width="85%">
            <br/>
            <img src="<?php echo $url_raiz.'img/'.$geralConfig[0]["nmLinkMarcaMenor"]; ?>">
        </td>
    </tr>
    <tr>
      <td>Enviar  imagem?
        <input name="nmLinkMarcaMenor" id="nmLinkMarcaMenor" size="60" type="file"/>
      <span class="destaque">(208px X 82px)</span></td>
    </tr>

    <!-- Marca Maior -->
    <tr>
        <td width="15%" rowspan="2">Texto ou Marca maior<br/>(Principal / Governo):</td>
        <td width="85%">
            <br/>
            <img src="<?php echo $url_raiz.'img/'.$geralConfig[0]["nmLinkMarcaMaior"]; ?>">
        </td>
    </tr>
    <tr>
      <td>Enviar  imagem?
        <input name="nmLinkMarcaMaior" id="nmLinkMarcaMaior" size="60" type="file"/>
      <span class="destaque">(220px X 80px)</span></td>
    </tr>
    
    <!-- Marca Admin -->
    <tr>
        <td width="15%" rowspan="2">Marca do Site<br/>(Admin):</td>
        <td width="85%">
            <br/>
            <img src="<?php echo $url_raiz.'img/'.$geralConfig[0]["nmLinkLogoTopo"]; ?>">
        </td>
    </tr>
    <tr>
      <td>Enviar  imagem?
        <input name="nmLinkLogoTopo" id="nmLinkLogoTopo" size="60" type="file"/>
      <span class="destaque">(316px X 195px)</span></td>
    </tr>
</table>
<br/>   
<h2 class="separadador">Dados de Manutenção</h2>
<table width="100%" border="0" align="center" class="tabelaForm">
    <tr>
        <td width="15%">E-mail Suporte:</td>
        <td width="85%"><input name="nmEmailSuporte" id="nmEmailSuporte" size="60" maxlength="200" style="top:auto" value="<?php echo $geralConfig[0]["nmEmailSuporte"]; ?>"/></td>
    </tr>
    <tr>
        <td width="15%">Habilitar HTTPS?</td>
        <td width="85%">
        <input type="radio" name="inHttps" id="inHttps" style="top:auto" value="1" <?php if($geralConfig[0]["inHttps"] == 1):?>checked="checked"<?php endif;?>/>Sim
        <input type="radio" name="inHttps" id="inHttps" style="top:auto" value="0" <?php if($geralConfig[0]["inHttps"] == 0):?>checked="checked"<?php endif;?>/>Não
        </td>
    </tr>           
</table>

<br/>            
<div align="left">
    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
    <input type="hidden" id="retorno" name="retorno" value="<?php echo $linkEsteArquivo; ?>" />
</div>
<br/>
</form>