<?php 
	$qryRedes = $db->query('SELECT idRedeSocial, nmRedeSocial FROM tb_rede_social WHERE inVisibilidade = 1');  
	$qryAlterar = $db->query("SELECT * FROM tb_dados_api WHERE idDadosApi = ".$db->clean($_REQUEST["idDadosApi"])); 
?>
    <form name="formGeral" id="formGeral" action="controller/act-a-p-i" method="post" enctype="multipart/form-data">
            
            <h1>Cadastro de Dados de API</h1>
            <br/>
            
			<?php include('sisMensagem.php');  ?>        
            <h2 class="separadador">Dados da Conta ou Aplicativo</h2>
            <p class="destaque_italico">
                &raquo; Informe abaixo os dados necess&aacute;rios para acesso avan&ccedil;ado &agrave;s APIs das redes sociais abaixo listadas, para este site;<br/>
                &raquo; Estes s&atilde;o dados t&eacute;cnicos obtivos nas p&aacute;ginas de desenvolvedores de cada rede social;<br/>
                &raquo; Voc&ecirc; pode cadastrar v&aacute;rios dados de apis para uma mesma rede social, mas somente uma delas poder&aacute; estar ativa (a &uacute;ltima cadastrada);<br/>
                &raquo; N&atilde;o modifique ou exclua estas informa&ccedil;&otilde;es, a menos que tenha absoluta certeza do que est&aacute; fazendo;<br/>
            </p>
  <div align="left">
				<?php if(!$qryAlterar){ ?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
                <?php } else {?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir" id="btExcluir" onClick="excluirItem('<?php echo $qryAlterar[0]["idDadosApi"]; ?>','controller/act-api','Excluir','idDadosApi');">Excluir</button>
                    <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-apis';">Cadastrar Novo</button>
                <?php } ?>
                <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href='menu-apis';">Voltar</button>
            </div>
            <div align="right"> <span class="destaqueVermelho">*</span>Campos obrigatórios.</div>
            <table width="100%" border="0" align="center" class="tabelaForm">
                <tr>
                    <td width="7%">Rede Social</td>
                    <td width="93%">
                          <select name="idRedeSocial" id="idRedeSocial"  style="top:auto">
                                <option value="">[Selecione]</option>
                                <?php for($i=0; $i<count($qryRedes); $i++){ ?>
                                <option value="<?php echo $qryRedes[$i]["idRedeSocial"]; ?>" <?php if($qryRedes[$i]["idRedeSocial"] == $qryAlterar[0]["idRedeSocial"]){ echo 'selected'; } ?>><?php echo $qryRedes[$i]["nmRedeSocial"]; ?></option>
                                <?php } ?>
                          </select>
                    </td>
                </tr>
                <tr>
                    <td width="15%">Nome do Aplicativo: <span class="destaqueVermelho">*</span></td>
                    <td width="85%"><input name="nmNomeApp" id="nmNomeApp" size="65" maxlength="100" style="top:auto" value="<?php echo $qryAlterar[0]["nmNomeApp"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">ID do Aplicativo:</td>
                    <td width="85%"><input name="nmAppId" id="nmAppId" size="65" maxlength="100" value="<?php echo $qryAlterar[0]["nmAppId"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">URL do Aplicativo:</td>
                    <td width="85%"><input name="nmURLApp" id="nmURLApp" size="65" maxlength="100" value="<?php echo $qryAlterar[0]["nmURLApp"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Callback URL: <span class="destaqueVermelho">*</span></td>
                    <td width="85%"><input name="nmCallbackURL" id="nmCallbackURL" size="65" maxlength="500" style="top:auto" value="<?php echo $qryAlterar[0]["nmCallbackURL"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Consumer Key:</td>
                    <td width="85%"><input name="nmConsumerKey" id="nmConsumerKey" size="65" maxlength="500" value="<?php echo $qryAlterar[0]["nmConsumerKey"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Consumer Secret:</td>
                    <td width="85%"><input name="nmConsumerSecret" id="nmConsumerSecret" size="65" maxlength="500" value="<?php echo $qryAlterar[0]["nmConsumerSecret"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Acess Token: <span class="destaqueVermelho">*</span></td>
                    <td width="85%"><input name="nmAcessToken" id="nmAcessToken" size="65" maxlength="500" style="top:auto" value="<?php echo $qryAlterar[0]["nmAcessToken"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Acess Token Secret:</td>
                    <td width="85%"><input name="nmAcessTokenSecret" id="nmAcessTokenSecret" size="65" maxlength="500" value="<?php echo $qryAlterar[0]["nmAcessTokenSecret"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Lista de Permissoes:</td>
                    <td width="85%">
                        <span class="destaque_italico"><span class="destaqueVermelho">Exemplo:</span> &raquo; user_info,email,friends</span>
                        <br/>
                        <input name="nmPermissionsList" id="nmPermissionsList" size="65" maxlength="500" value="<?php echo $qryAlterar[0]["nmPermissionsList"]; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td width="15%">Ativo?</td>
                    <td width="85%">
                        <select name="inAtivo" id="inAtivo"  style="top:auto">
                            <option value="1" <?php if(isset($qryAlterar[0]["inAtivo"]) && $qryAlterar[0]["inAtivo"] == 1){ echo 'selected'; } ?>>Sim</option>
                            <option value="0" <?php if(isset($qryAlterar[0]["inAtivo"]) && $qryAlterar[0]["inAtivo"] == 0){ echo 'selected'; } ?>>Nao</option>
                        </select>                    
                    </td>
                </tr>            
            </table>
            <br/>
            <div align="left">
                <?php if(!$qryAlterar){ ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
                    <input type="hidden" id="acao" name="acao" value="Cadastrar" />
                <?php } else {?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirItem('<?php echo $qryAlterar[0]["idDadosApi"]; ?>','controller/act-api','Excluir','idDadosApi');">Excluir</button>
                    <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href='cad-apis';">Cadastrar Novo</button>
                    <input type="hidden" id="idDadosApi" name="idDadosApi" value="<?php echo $qryAlterar[0]["idDadosApi"]; ?>" />
                    <input type="hidden" id="acao" name="acao" value="Atualizar" />
                <?php } ?>
                <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href='menu-apis';">Voltar</button>
            </div>
            <br/>
        </form>