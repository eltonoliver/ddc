<?php $qryEnquete = $db->query("SELECT * FROM tb_enquete WHERE idEnquete = ".$db->clean($_REQUEST["idEnquete"]));?>
            <form name="formGeral" id="formGeral" action="controller/act-enquete" method="post" enctype="multipart/form-data">
            
            <h1>Cadastro de Enquetes</h1>
            <br/>
            
			<?php include('sisMensagem.php'); ?>
                    
              <h2 class="separadador">Etapa <span class="destaqueVermelho">1</span> de 2: Dados da Pergunta</h2>
            
            <p class="destaque_italico">
                &raquo; Cadastre abaixo os dados da pergunta desta enquete e, na tela seguinte, você poderá incluir as respostas desejadas;<br/>
            </p>
            
            <div align="left">
                <?php if(!$qryEnquete){ ?>
              <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                <?php } else {?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir" id="btExcluir" onClick="excluirItem('<?php echo $qryEnquete[0]["idEnquete"]; ?>','controller/act-enquete','Excluir','idEnquete');">Excluir</button>
                    <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-enquete';">Cadastrar Novo</button>
                <?php } ?>
                <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href='menu-enquete';">Voltar</button>
            </div>
            <br/>
            
            <table width="100%" border="0" align="center" class="tabelaForm">
                <tr>
                    <td width="15%">Pergunta:</td>
                    <td width="85%"><input name="nmPergunta" id="nmPergunta" size="60" maxlength="100" style="top:auto;" value="<?php echo $qryEnquete[0]["nmPergunta"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Descrição:</td>
                    <td width="85%">
                    	<input name="nmDescricaoEnquete" id="nmDescricaoEnquete" size="60" maxlength="100" style="top:auto;" value="<?php echo $qryEnquete[0]["nmDescricaoEnquete"]; ?>"/>
                        <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" height="16" class="imgHover" id="helpEnqueteDescricao"/>
                    </td>
                </tr>
                <tr>
                    <td width="15%">Data Início:</td>
                    <td>
                        <?php if(!$qryEnquete){ ?>
                      <input type="text" name="dtDataInicio" id="dtDataInicio" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y');?>"/>
                        <?php } else { ?>
                      <input type="text" name="dtDataInicio" id="dtDataInicio" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y', strtotime($qryEnquete[0]["dtDataInicio"]));?>"/>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td width="15%">Data Fim:</td>
                    <td>
                        <?php if(!$qryEnquete){ ?>
                      <input type="text" name="dtDataFim" id="dtDataFim" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y');?>"/>
                        <?php } else { ?>
                      <input type="text" name="dtDataFim" id="dtDataFim" size="10" class="jdpicker" readonly="readonly" value="<?php echo date('d/m/Y', strtotime($qryEnquete[0]["dtDataFim"]));?>"/>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td width="15%" valign="top">Destaque?</td>
                    <td valign="top">
                        <input type="radio" id="inDestaque" name="inDestaque" value="1" <?php if($qryEnquete && $qryEnquete[0]["inDestaque"] == 1){ echo 'checked="checked"'; } else if(!$qryEnquete){ echo 'checked="checked"'; } ?>/> Sim
                        <input type="radio" id="inDestaque" name="inDestaque" value="0" <?php if($qryEnquete && $qryEnquete[0]["inDestaque"] == 0){ echo 'checked="checked"'; } ?>/> Nao
                        <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" height="16" class="imgHover" id="helpEnqueteDestaque"/>
                    </td>
                </tr>
                <tr>
                    <td width="15%" valign="top">Limitar votação por IP?</td>
                    <td valign="top">
                        <input type="radio" id="inLimitarIp" name="inLimitarIp" value="1" <?php if($qryEnquete && $qryEnquete[0]["inLimitarIp"] == 1){ echo 'checked="checked"'; } ?>/> Sim
                        <input type="radio" id="inLimitarIp" name="inLimitarIp" value="0" <?php if($qryEnquete && $qryEnquete[0]["inLimitarIp"] == 0){ echo 'checked="checked"'; } else if(!$qryEnquete){ echo 'checked="checked"'; } ?>/> Nao
                      <img src="<?php echo $url_raiz; ?>admin/img/help_icon.gif" width="16" height="16" class="imgHover" id="helpeEnqueteLimitarIP"/>
                  </td>
                </tr>                
                <tr>
                    <td width="15%">Tipo de Enquete</td>
                    <td width="85%">
                        <select name="inTipoEnquete" id="inTipoEnquete" style="top:auto;" >
                            <option value="">[Selecione]</option>
                            <option value="1" <?php if($qryEnquete && $qryEnquete[0]["inTipoEnquete"] == 1){ echo 'selected="selected"'; } ?>>Escolha Única</option>
                            <option value="2" <?php if($qryEnquete && $qryEnquete[0]["inTipoEnquete"] == 2){ echo 'selected="selected"'; } ?>>Múltipla escolha</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="15%">Ativo?</td>
                    <td width="85%">
                        <select name="inAtivo" id="inAtivo" style="top:auto;" >
                            <option value="1" <?php if($qryEnquete && $qryEnquete[0]["inAtivo"] == 1){ echo 'selected="selected"'; } else if(!$qryEnquete){ echo 'selected="selected"'; }  ?>>Sim</option>
                            <option value="0" <?php if($qryEnquete && $qryEnquete[0]["inAtivo"] == 0){ echo 'selected="selected"'; } ?>>Não</option>
                        </select>
                    </td>
                </tr>
            </table>
            
            
            
            <br/>
            <div align="left">
                <?php if(!$qryEnquete){ ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                    <input type="hidden" id="acao" name="acao" value="Cadastrar" />
                <?php } else {?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirItem('<?php echo $qryEnquete[0]["idEnquete"]; ?>','controller/act-enquete','Excluir','idEnquete');">Excluir</button>
                    <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href='cad-enquete';">Cadastrar Novo</button>
                    <input type="hidden" id="idEnquete" name="idEnquete" value="<?php echo $qryEnquete[0]["idEnquete"]; ?>" />
                    <input type="hidden" id="acao" name="acao" value="Atualizar" />
                <?php } ?>
                <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href='menu-enquete';">Voltar</button>
            </div>
            <br/>
        </form>
        <?php 
			if($qryEnquete){ 
			$qryRespostas = $db->query("SELECT * FROM tb_resposta_enquete WHERE idEnquete = ".$qryEnquete[0]["idEnquete"]." ORDER BY idRespostaEnquete");
			
			$total = 0;
			if($qryRespostas){
				$total = $total + count($qryRespostas);	
			} 
			$total++;
		?>
        <form name="formGeral" id="formGeral" action="controller/act-enquete" method="post" enctype="multipart/form-data">
            <br/>
            <h2 class="separadador"><a name="secaoResposta"></a>Etapa <span class="destaqueVermelho">2</span> de 2: Respostas para <span class="destaqueVermelho"><?php echo '"'.$qryEnquete[0]["nmPergunta"].'"'; ?></span></h2>
          <p class="destaque_italico">
                &raquo; Cadastre abaixo cada uma das respostas para a pergunta desta enquete;<br/>
            </p>
            <table width="100%" border="0" align="center" class="tabelaForm">
                <tr>
                    <td width="15%">Resposta <span class="destaqueVermelho">#<?php echo $total; ?></span></td>
                    <td width="85%"><input name="nmResposta" id="nmResposta" size="60" maxlength="200" style="top:auto;"/></td>
                </tr>
            </table>
            <div align="left">
            <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Cadastrar Resposta</button>
            <input type="hidden" id="acao" name="acao" value="CadastrarResposta" />
            <input type="hidden" id="idEnquete" name="idEnquete" value="<?php echo $qryEnquete[0]["idEnquete"]; ?> " />
            </div>
        </form>
        	<?php if($qryRespostas){ ?>
            <div style="margin-left:20px;">
			<h2 class="separadador"><a name="resultados"></a>Respostas cadastradas</h2>
        	<table width="100%" border="0" align="center" class="tbLista">
            	<tr class="tbTituloCinza">
                	<td align="center" width="3%">Op&ccedil;&otilde;es</td>
                	<td width="34%" align="left">Resposta</td>
                	<td align="left">Votos</td>
                	<td align="left">Percentual</td>
               	</tr>
                <?php 
					$qryTotalGeral = $db->query("SELECT count(idEnqueteVoto) as total FROM tb_enquete_voto WHERE idEnquete = ".$qryEnquete[0]["idEnquete"]);
					$totalGeral = $qryTotalGeral[0]["total"];
					$coluna = 1;
					for($i=0; $i<count($qryRespostas); $i++){
					
					if($coluna % 2 == 0){
						$classe = 'tbNormal';
					} else {
						$classe = 'tbNormalAlt';
					}
				?>
            	<tr class="<?php echo $classe; ?>">
                	<td align="center" width="3%">
                        <img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                                 onClick="excluirItem('<?php echo $qryRespostas[$i]["idRespostaEnquete"]; ?>','controller/act-enquete','ExcluirResposta','idRespostaEnquete');"
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Excluir" 
                                 alt="Excluir" class="imgHover"/>
                    </td>
                	<td align="left"><?php echo $qryRespostas[$i]["nmResposta"]; ?></td>
                	<td align="left">
                    	<?php 
							if($totalGeral > 0){
								$qryTotalVotos = $db->query("SELECT count(idEnqueteVoto) as total FROM tb_enquete_voto WHERE idRespostaEnquete = ".$qryRespostas[$i]["idRespostaEnquete"]);
								$totalVotos = $qryTotalVotos[0]["total"];
								echo $totalVotos;
							} else { echo '-'; }
						?>
                    </td>
                	<td align="left">
                    	<?php 
							if($totalGeral > 0){
								$totalPercent = ($totalVotos*100)/$totalGeral;
								echo number_format($totalPercent,2, ',', ' ').'%';
							} else { echo '-'; }
						?>
                    </td>
               	</tr>
                <?php $coluna++; } ?>
            </table>
            </fieldset>
            <br/>
            </div>
            <?php } ?>
        <?php } ?>
        
        
<script type="text/javascript">
	ugaAlert('helpEnqueteDescricao', 	
'&raquo; Esta descrição é apenas um lembrete, e não será exibida para os visitantes;'
	);
	
	ugaAlert('helpeEnqueteLimitarIP', 	
'&raquo; Sinalizando esta opção, o visitante não poderá votar mais de uma vez no mesmo computador, durante todo o tempo de duração da enquete;<br/>'+
'&raquo; Por padrão, o único bloqueio feito é que o visitante não possa votar mais de uma vez durante uma sessão (ou seja: uma visita ao site);'
	);
	

	ugaAlert('helpEnqueteDestaque', 	
'&raquo;Sinalizar uma enquete como destaque é ação que faz com que ela seja exibida na página inicial do site;<br/>'+
'&raquo; Somente uma enquete pode ser sinalizada como destaque;<br/>'+
'&raquo; Ao sinalizar o destaque para esta enquete, todos as outras ser&atilde;o automaticamente sinalizados como não destacadas;<br/>'
	);
</script>
