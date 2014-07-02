<?php
	$qryContato = $db->query("SELECT * FROM tb_contato_site WHERE idContatoSite = ".$db->clean($_REQUEST["idContatoSite"]));
?>
            <form name="formGeral" id="formGeral" action="controller/act-contato-site" method="post" enctype="multipart/form-data">
            
            <h1>Cadastro de Contatos</h1>
            <br/>
            
			<?php include('sisMensagem.php'); ?>
                    
            <h2 class="separadador">Dados do Contato</h2>
            
            <p class="destaque_italico">
                &raquo; Os dados cadastrados nesta se&ccedil;&atilde;o ser&atilde;o utilizados para o formul&aacute;rio de contato da p&aacute;gina "Fale Conosco";<br/>
            </p>
            
            <div align="left">
                <?php if(!$qryContato){ ?>
              <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                <?php } else {?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir" id="btExcluir" onClick="excluirItem('<?php echo $_GET["idContatoSite"]; ?>','controller/act-contato-site','Excluir','idContatoSite');">Excluir</button>
                    <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-contato-site';">Cadastrar Novo</button>
                <?php } ?>
                <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href='menu-contato-site';">Voltar</button>
            </div>
            <br/>
            
            <table width="100%" border="0" align="center" class="tabelaForm">
                <tr>
                    <td width="15%">Nome:</td>
                    <td width="85%"><input name="nmContato" id="nmContato" size="60" maxlength="100" style="top:auto;" value="<?php echo $qryContato[0]["nmContato"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Descrição:</td>
                    <td width="85%">
                    	<input name="nmDescricaoContato" id="nmDescricaoContato" size="60" maxlength="100" style="top:auto;" value="<?php echo $qryContato[0]["nmDescricaoContato"]; ?>"/>
                        <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" height="16" class="imgHover" id="helpDescricaoContato"/>
                    </td>
                </tr>
                <tr>
                    <td width="15%">E-mail:</td>
                    <td width="85%"><input name="nmEmailContato" id="nmEmailContato" size="60" maxlength="100" style="top:auto;" value="<?php echo $qryContato[0]["nmEmailContato"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%" valign="top">Contato Principal do Site?</td>
                    <td valign="top">
                        <input type="radio" id="inContatoPrincipal" name="inContatoPrincipal" value="1" <?php if($qryContato && $qryContato[0]["inContatoPrincipal"] == 1){ echo 'checked="checked"'; } ?>/> Sim
                        <input type="radio" id="inContatoPrincipal" name="inContatoPrincipal" value="0" <?php if($qryContato && $qryContato[0]["inContatoPrincipal"] == 0){ echo 'checked="checked"'; } else if(!$qryContato){ echo 'checked="checked"'; } ?>/> Não
                        <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" height="16" class="imgHover" id="helpContatoPrincipal"/>
                    </td>
                </tr>
                <tr>
                    <td width="15%" valign="top">Exibir no Formulário de Contatos?</td>
                    <td valign="top">
                        <input type="radio" id="inExibir" name="inExibir" value="1" <?php if($qryContato && $qryContato[0]["inExibir"] == 1){ echo 'checked="checked"'; } else if(!$qryContato){ echo 'checked="checked"'; } ?>/> Sim
                        <input type="radio" id="inExibir" name="inExibir" value="0" <?php if($qryContato && $qryContato[0]["inExibir"] == 0){ echo 'checked="checked"'; } ?>/> Não
                    </td>
                </tr>                <tr>
                    <td width="15%">Ativo?</td>
                    <td width="85%">
                        <select name="inAtivo" id="inAtivo" style="top:auto;" >
                            <option value="1" <?php if($qryContato && $qryContato[0]["inAtivo"] == 1){ echo 'selected="selected"'; } else if(!$qryContato){ echo 'selected="selected"'; }  ?>>Sim</option>
                            <option value="0" <?php if($qryContato && $qryContato[0]["inAtivo"] == 0){ echo 'selected="selected"'; } ?>>Não</option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <br/>
            <div align="left">
                <?php if(!$qryContato){ ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                    <input type="hidden" id="acao" name="acao" value="Cadastrar" />
                <?php } else {?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirItem('<?php echo $_GET["idContatoSite"]; ?>','controller/act-contato-site','Excluir','idContatoSite');">Excluir</button>
                    <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href='cad-contato-site';">Cadastrar Novo</button>
                    <input type="hidden" id="idContatoSite" name="idContatoSite" value="<?php echo $_GET["idContatoSite"]; ?>" />
                    <input type="hidden" id="acao" name="acao" value="Atualizar" />
                <?php } ?>
                <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href='menu-contato-site';">Voltar</button>
            </div>
            <br/>
        </form>
        
        
        
<script type="text/javascript">
	ugaAlert('helpDescricaoContato', 	
'&raquo; Se a opção "Exibir no Formulário de Contato" for sinalizada, esta descrição será a "etiqueta" exibida no seletor do Formulário.'
	);

	ugaAlert('helpContatoPrincipal', 	
'&raquo; O contato que for sinalizado como "Contato Principal" receber&aacute; todas as notifica&ccedil;&otilde;es de e-mail autom&aacute;ticas, como modera&ccedil;&atilde;o de coment&aacute;rios, pore exemplo;<br/>'+
'&raquo; Somente um contato poder&aacute; ser sinalizado como principal;<br/>'+
'&raquo; Ao sinalizar um contato como principal, todos os outros ser&atilde;o automaticamente sinalizados como contatos simples;<br/>'
	);
</script>
