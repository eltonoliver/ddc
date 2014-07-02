<?php
	$qryEndereco = $db->query("SELECT * FROM tb_endereco_site WHERE idEnderecoSite = ".$db->clean($_REQUEST["idEnderecoSite"]));
?>
            <form name="formGeral" id="formGeral" action="controller/act-endereco" method="post" enctype="multipart/form-data">
            
            <h1>Cadastro de Endereços</h1>
            <br/>
            
			<?php include('sisMensagem.php'); ?>
                    
            <h2 class="separadador">Dados do Endere&ccedil;o</h2>
            
            <p class="destaque_italico">
                &raquo; Os dados cadastrados nesta se&ccedil;&atilde;o ser&atilde;o utilizados nas p&aacute;ginas de localiza&ccedil;&atilde;o e no rodap&eacute; do site, quando necess&aacute;rio;<br/>
            </p>
            
            <div align="left">
                <?php if(!$qryEndereco){ ?>
              <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                <?php } else {?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir" id="btExcluir" onClick="excluirItem('<?php echo $_GET["idEnderecoSite"]; ?>','controller/act-endereco','Excluir','idEnderecoSite');">Excluir</button>
                    <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-endereco';">Cadastrar Novo</button>
                <?php } ?>
                <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href='menu-endereco';">Voltar</button>
            </div>
            <br/>
            
            <table width="100%" border="0" align="center" class="tabelaForm">
                <tr>
                    <td width="15%">Título<span class="destaque">*</span></td>
                    <td width="85%"><input name="nmTituloEndereco" id="nmTituloEndereco" size="60" maxlength="100" style="top:auto;" value="<?php echo $qryEndereco[0]["nmTituloEndereco"]; ?>"/></td>
                </tr>                
                <tr>
                    <td width="15%">Tipo Logradouro<span class="destaque">*</span></td>
                    <td width="85%">
                    	<?php $qryLogradouros = $db->query("SELECT * FROM tb_tipo_logradouro ORDER BY nmTipoLogradouro ASC"); ?>
                        <select name="idTipoLogradouro" id="idTipoLogradouro" style="top:auto;">
                            <option value="">[Selecione]</option>
                            <?php foreach($qryLogradouros as $logradouro){ ?>
                            	<option value="<?php echo $logradouro["idTipoLogradouro"]; ?>" <?php if($qryEndereco[0]["idTipoLogradouro"] == $logradouro["idTipoLogradouro"]){ echo 'selected'; } ?>><?php echo $logradouro["nmTipoLogradouro"]; ?></option>
                            <?php } ?>
                        </select>
                  </td>
                </tr>
                <tr>
                    <td width="15%">Logradouro<span class="destaque">*</span></td>
                    <td width="85%"><input name="nmLogradouro" id="nmLogradouro" size="60" maxlength="200" style="top:auto;" value="<?php echo $qryEndereco[0]["nmLogradouro"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Número<span class="destaque">*</span></td>
                    <td width="85%"><input name="nrNumero" id="nrNumero" size="10" maxlength="10" style="top:auto;" value="<?php echo $qryEndereco[0]["nrNumero"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Complemento</td>
                    <td width="85%"><input name="nmComplemento" id="nmComplemento" size="60" maxlength="200" value="<?php echo $qryEndereco[0]["nmComplemento"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Ponto de Referencia</td>
                    <td width="85%"><input name="nmPontoReferencia" id="nmPontoReferencia" size="60" maxlength="200" value="<?php echo $qryEndereco[0]["nmPontoReferencia"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Bairro<span class="destaque">*</span></td>
                    <td width="85%"><input name="nmBairro" id="nmBairro" size="60" maxlength="50" style="top:auto;" value="<?php echo $qryEndereco[0]["nmBairro"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">CEP<span class="destaque">*</span></td>
                    <td width="85%"><input name="nmCEP" id="nmCEP" size="10" maxlength="9" style="top:auto;" value="<?php echo $qryEndereco[0]["nmCEP"]; ?>"/></td>
                </tr>
              <tr>
                    <td width="15%">Estado<span class="destaque">*</span></td>
                    <td width="85%">
                      <select name="nmEstado" id="nmEstado" style="top:auto;">
                            <option value="">Selecione</option>
                            <?php for($i=0; $i<count($estados); $i++){ ?>
                            <option value="<?php echo $estados[$i][1]; ?>" <?php if($qryEndereco[0]["nmEstado"] == $estados[$i][1]){ echo 'selected'; } ?>><?php echo $estados[$i][1]; ?></option>
                            <?php } ?>
                      </select>
                    </td>
              </tr>
                <tr>
                    <td width="15%">Cidade<span class="destaque">*</span></td>
                    <td width="85%"><input name="nmCidade" id="nmCidade" size="60" maxlength="100" style="top:auto;" value="<?php echo $qryEndereco[0]["nmCidade"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%" valign="top">Endereço Principal?</td>
                    <td valign="top">
                        <input type="radio" id="inPrincipal" name="inPrincipal" value="1" <?php if($qryEndereco && $qryEndereco[0]["inPrincipal"] == 1){ echo 'checked="checked"'; } ?>/> Sim
                        <input type="radio" id="inPrincipal" name="inPrincipal" value="0" <?php if($qryEndereco && $qryEndereco[0]["inPrincipal"] == 0){ echo 'checked="checked"'; } else if(!$qryEndereco){ echo 'checked="checked"'; }?>/> Nao
                    </td>
                </tr>              <tr>
                    <td width="15%" valign="top">Exibir no Site?</td>
                    <td valign="top">
                        <input type="radio" id="inExibir" name="inExibir" value="1" <?php if($qryEndereco && $qryEndereco[0]["inExibir"] == 1){ echo 'checked="checked"'; } else if(!$qryEndereco){ echo 'checked="checked"'; } ?>/> Sim
                        <input type="radio" id="inExibir" name="inExibir" value="0" <?php if($qryEndereco && $qryEndereco[0]["inExibir"] == 0){ echo 'checked="checked"'; } ?>/> Nao
                    </td>
                </tr>                
                <tr>
                    <td width="15%">Ativo?</td>
                    <td width="85%">
                        <select name="inAtivo" id="inAtivo" style="top:auto;" >
                            <option value="1" <?php if($qryEndereco && $qryEndereco[0]["inAtivo"] == 1){ echo 'selected="selected"'; } else if(!$qryEndereco){ echo 'selected="selected"'; }  ?>>Sim</option>
                            <option value="0" <?php if($qryEndereco && $qryEndereco[0]["inAtivo"] == 0){ echo 'selected="selected"'; } ?>>Nao</option>
                        </select>
                    </td>
                </tr>
            </table>
            <br/>
            
            <h2 class="separadador">Contatos deste Endereço</h2>
            <table width="100%" border="0" align="center" class="tabelaForm">
                <tr>
                    <td width="15%">Telefone Comercial<span class="destaque">*</span></td>
                    <td width="85%"><input name="nmTelefoneComercial" id="nmTelefoneComercial" size="20" maxlength="20" style="top:auto;" value="<?php echo $qryEndereco[0]["nmTelefoneComercial"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Telefone Celular</td>
                    <td width="85%"><input name="nmTelefoneCelular" id="nmTelefoneCelular" size="20" maxlength="20" value="<?php echo $qryEndereco[0]["nmTelefoneCelular"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Telefone Convencional</td>
                    <td width="85%"><input name="nmTelefoneConvencional" id="nmTelefoneConvencional" size="20" maxlength="20" value="<?php echo $qryEndereco[0]["nmTelefoneConvencional"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Telefone (Outros)</td>
                    <td width="85%"><input name="nmTelefoneOutros" id="nmTelefoneOutros" size="20" maxlength="20" value="<?php echo $qryEndereco[0]["nmTelefoneConvencional"]; ?>"/></td>
                </tr>
            </table>
            
            <script type="text/javascript">
				$('#nmCEP').mask('99999-999');
				$('#nmTelefoneComercial').mask('(99) 9999-9999');
				$('#nmTelefoneCelular').mask('(99) 9999-9999');
				$('#nmTelefoneConvencional').mask('(99) 9999-9999');
				$('#nmTelefoneOutros').mask('(99) 9999-9999');
			</script>
            
            <br/>
            
            <h2 class="separadador">Dados de Localização</h2>
            <table width="100%" border="0" align="center" class="tabelaForm">
                <tr>
                    <td width="15%" rowspan="2">Mapa:<br/>(Imagem fixa)</td>
                    <td width="85%">
                        <img src="<?php echo $url_raiz.'img/'.$qryEndereco[0]["nmLinkImagemMapa"]; ?>" width="300" height="250">
                    </td>
                </tr>
                <tr>
                  <td>Enviar  imagem?
                    <input name="nmLinkImagemMapa" id="nmLinkImagemMapa" size="60" type="file"/>
                  <span class="destaque">(300px X 250px)</span></td>
                </tr>
                <tr>
                    <td width="15%">Mapa Dinâmico:<br/>(Google Maps)</td>
                    <td>
                        <textarea name="nmCodigoMaps" cols="60" rows="10" id="nmCodigoMaps" wrap="virtual"><?php echo $qryEndereco[0]["nmCodigoMaps"]; ?></textarea>
                    </td>
                </tr>   
            </table>
            
            <br/>
            <div align="left">
                <?php if(!$qryEndereco){ ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                    <input type="hidden" id="acao" name="acao" value="Cadastrar" />
                <?php } else {?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirItem('<?php echo $_GET["idEnderecoSite"]; ?>','controller/act-endereco','Excluir','idEnderecoSite');">Excluir</button>
                    <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href='cad-endereco';">Cadastrar Novo</button>
                    <input type="hidden" id="idEnderecoSite" name="idEnderecoSite" value="<?php echo $_GET["idEnderecoSite"]; ?>" />
                    <input type="hidden" id="acao" name="acao" value="Atualizar" />
                <?php } ?>
                <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href='menu-endereco';">Voltar</button>
            </div>
            <br/>
        </form>
        
        
        
<script type="text/javascript">
	ugaAlert('helpDescricaoContato', 	
'&raquo; Se a op�ao "Exibir no Formul�rio de Contato" for sinalizada, esta descri�ao ser� a "etiqueta" exibida no seletor do Formul�rio.'
	);

	ugaAlert('helpContatoPrincipal', 	
'&raquo; O contato que for sinalizado como "Contato Principal" receber&aacute; todas as notifica&ccedil;&otilde;es de e-mail autom&aacute;ticas, como modera&ccedil;&atilde;o de coment&aacute;rios, pore exemplo;<br/>'+
'&raquo; Somente um contato poder&aacute; ser sinalizado como principal;<br/>'+
'&raquo; Ao sinalizar um contato como principal, todos os outros ser&atilde;o automaticamente sinalizados como contatos simples;<br/>'
	);
</script>
