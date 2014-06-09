<?php 

	$qryRedes = $db->query('SELECT * FROM tb_rede_social WHERE inVisibilidade = 1'); 
	$total_registros = count($qryRedes);
	$msg = $_SESSION['msg']; unset($_SESSION['msg']);
	$msgErro = $_SESSION['msgErro']; unset($_SESSION['msgErro']);
?>

            <h1>Redes Socais &raquo; Integração</h1>
            <br/>
            
			<?php if($msg){ ?>
                <div class="msgBox"><p><img src="<?php echo $url_raiz; ?>admin/img/alertaOK.gif" border="0"align="absbottom"/><?php echo $msg ?></p></div>
            <?php } elseif($msgErro){?>
                <div class="msgBox"><p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/><?php echo $msgErro; ?></p></div>
            <?php } ?>
            
            <h2 class="separadador">Redes Disponíveis (<?php echo $total_registros; ?>)</h2>
            <p class="destaque_italico">
                &raquo; Informe abaixo os dados necessários para acesso/integração das redes sociais abaixo listadas, para este site;<br/>
                &raquo; Para desabilitar totalmente a integra&ccedil;&atilde;o de uma Rede Social, utilize a op&ccedil;&atilde;o &quot;Ativar / Desativar&quot;;<br/>
            </p>
            <?php if($total_registros == 0){ //Se a consulta voltou sem nenhum resultado.?>
                <div class="msgBox">
                    <p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/> Nenhum resultado para a consulta realizada.</p>
                </div>
                
            <?php } else { //Se foram encontrados resultados. ?>
            
            <fieldset>
        	<table width="100%" border="0" align="center" class="tbLista">
            	<tr class="tbTitulo">
                	<td colspan="3" align="center" width="9%">Op&ccedil;&otilde;es</td>
                	<td width="11%" align="center">Rede</td>
                	<td width="16%" align="left">Nome de usu&aacute;rio</td>
                	<td width="16%" align="left">ID de usu&aacute;rio</td>
                	<td width="47%" align="left">URL completa</td>
               	</tr>
                <?php 
					$coluna = 1;
					for($i=0; $i<count($qryRedes); $i++){
					
					if($coluna % 2 == 0){
						$classe = 'tbNormal';
					} else {
						$classe = 'tbNormalAlt';
					}
				?>
                 <form name="formRedes<?php echo $coluna; ?>" id="formRedes<?php echo $coluna; ?>" action="controller/act-redes" method="post">
            	<tr class="<?php echo $classe; ?>">
                	<td align="center" width="3%">
                    	<?php /*?><img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                        	 onclick="excluirItem('<?php echo $qryRedes[$i]["idRedeSocial"]; ?>','controller/act-redes','Excluir','idRedeSocial');" 
                             onmouseover="javascript: this.style.cursor='pointer';"
                             title="Excluir"
                             alt="Excluir"/><?php */?>
                             -
                    </td>
                	<td align="center" width="3%">
                    	<img src="<?php echo $url_raiz; ?>admin/img/iconeAtualizar.png" 
                        	 onClick="validaFormularioVazioElementos(formRedes<?php echo $coluna; ?>);" 
                             title="Atualizar"
                             alt="Atualizar" 
                             class="hover"/>
                    </td>
                	<td align="center" width="3%">
                    
                    
						<?php if($qryRedes[$i]["inAtivo"] == 1){ ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                 onclick="javascript: location.href='controller/act-redes?acao=Destivar&idRedeSocial=<?php echo $qryRedes[$i]["idRedeSocial"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Ativo. Clique para destivar"
                                 alt="Ativo. Clique para destivar"/>
                                 
                        <?php } else { ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" 
                                 onclick="javascript: location.href='controller/act-redes?acao=Ativar&idRedeSocial=<?php echo $qryRedes[$i]["idRedeSocial"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor='pointer';"
                                 title="Desativado. Clique para ativar."
                                 alt="Desativado. Clique para ativar"/>
                                 
                        <?php } ?>
                        <input type="hidden" name="inAtivo" id="inAtivo" value="<?php echo $qryRedes[$i]["inAtivo"]; ?>"/>
                    </td>
                	<td align="center">
						<?php echo $qryRedes[$i]["nmRedeSocial"]; ?>
                        <input type="hidden" name="idRedeSocial" id="idRedeSocial" value="<?php echo $qryRedes[$i]["idRedeSocial"]; ?>"/>
                        <input type="hidden" name="acao" id="acao" value="atualizar"/>
                    </td>
                	<td align="left"><input name="nmUsuario" id="nmUsuario" size="30" maxlength="100" style="top:auto" value="<?php echo $qryRedes[$i]["nmUsuario"]; ?>"/></td>
                	<td align="left"><input name="idUsuarioRede" id="idUsuarioRede" size="30" maxlength="50" style="top:auto" value="<?php echo $qryRedes[$i]["idUsuarioRede"]; ?>"/></td>
                	<td align="left"><input name="nmURLCompleta" id="nmURLCompleta" size="70" maxlength="200" style="top:auto" value="<?php echo $qryRedes[$i]["nmURLCompleta"]; ?>"/></td>
               	</tr>
                </form>
                <?php $coluna++; } ?>
            </table>
            </fieldset>
            <?php } ?>
            
            <br/>