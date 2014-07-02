<table>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td height="43" class="rodape" align="center" colspan="2">
    
    	<p>
		<?php 
			
			if($geralConfig[0]["anoLancamento"] == date("Y")){
				echo date("Y");
				
			} else {
				echo $geralConfig[0]["anoLancamento"]. ' - ' .date("Y");
				
			}
		
		echo '  '. $geralConfig[0]["nmTituloSite"]; ?> &reg; - Todos os direitos reservados <?php if($geralConfig[0]["inDensevolve"] == 1){ ?>| Suporte: <a href="mailto:<?php echo $geralConfig[0]["nmEmailSuporte"]; ?>" title="Entre em contato com o Suporte" target="_blank"><?php echo $geralConfig[0]["nmEmailSuporte"]; ?></a><?php } ?>
        <br/>
        	<?php if($geralConfig[0]["inDensevolve"] == 1){ ?>
        	Desenvolvido por: <a href="http://www.ugagogo.com.br" title="Conhe&ccedil;a a Ugagogo!" target="_blank">Ugagogo Invencionices Tecnol&oacute;gicas</a> | <a href="http://www.ugagogo.com.br/softwares/ugadmin" title="Saiba Mais" target="_blank">Ugadmin vers&atilde;o 2.1.1.3</a>
            <?php } else { ?>
                Sistema <a href="http://www.ugagogo.com.br/softwares/ugadmin" title="CMS Ugadmin" target="_blank">Ugadmin vers&atilde;o 2.1.1.3</a> | Suporte: <a href="mailto:<?php echo $geralConfig[0]["nmEmailSuporte"]; ?>" title="Entre em contato com o Suporte" target="_blank"><?php echo $geralConfig[0]["nmEmailSuporte"]; ?></a>
            <?php } ?>
        </p>
        
    </td>
</tr>