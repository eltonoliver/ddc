<?php
	$qryPerfil = $db->query("SELECT * FROM tb_perfil WHERE idPerfil = ".$db->clean($_REQUEST["idPerfil"]));
	
	$arrayMenusPerfil = array();
	if($qryPerfil){
		$str = "SELECT * FROM vwmenuperfil WHERE idTipoMenu = '1' AND inExibir = '1' AND idPerfil = '".$qryPerfil[0]["idPerfil"]."'";
		$qryMenusPerfil = $db->query($str);
		$arrayMenusPerfil = campoMatrizParaArray($valorInicial,$qryMenusPerfil,'idMenu');
	}
	
	//new dBug($arrayMenusPerfil);
	$qryMenusPai = $db->query("SELECT * FROM tb_menu WHERE  idTipoMenu = 1 ORDER BY idMenu, nmMenu, ordemMenu"); //idMenuPai = 0 AND
	$checksMenus = geraChecksMenus($qryMenusPai,$arrayMenusPerfil);
	
?>
            <form name="formGeral" id="formGeral" action="controller/act-perfil" method="post" enctype="multipart/form-data">
            
            <h1>Cadastro de Perfis</h1>
            <br/>
            
			<?php include('sisMensagem.php'); ?>
                    
            <h2 class="separadador">Dados do Perfil</h2>
            <p class="destaque_italico">
                &raquo; OBS.: Por quest&otilde;es de seguran&ccedil;a, n&atilde;o &eacute; permitido nenhum tipo de altera&ccedil;&atilde;o no perfil "Administrador";<br/>
            </p>
            <div align="left">
                <?php if(!isset($_REQUEST["idPerfil"])){ ?>
              <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
                <?php } else if($qryPerfil[0]["idPerfil"] != 1 ) {?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir" id="btExcluir" onClick="excluirItem('<?php echo $_GET["idPerfil"]; ?>','controller/act-perfil','Excluir','idPerfil');">Excluir</button>
                    <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-perfil';">Cadastrar Novo</button>
                <?php } ?>
                <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href='menu-perfis';">Voltar</button>
            </div>
            <br/>
            
            <table width="100%" border="0" align="center" class="tabelaForm">
                <tr>
                    <td width="15%">Nome do Perfil:</td>
                    <td width="85%"><input name="nmPerfil" id="nmPerfil" size="60" maxlength="100" style="top:auto" value="<?php echo $qryPerfil[0]["nmPerfil"]; ?>"/></td>
                </tr>
                <tr>
                  <td width="15%" valign="top">Menus:</td>
                    <td width="85%"><?php echo $checksMenus; ?></td>
                </tr>
            </table>
            
            <br/>
            <div align="left">
                <?php if(!isset($_REQUEST["idPerfil"])){ ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
                    <input type="hidden" id="acao" name="acao" value="Cadastrar" />
                <?php } else if($qryPerfil[0]["idPerfil"] != 1 ) {?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirItem('<?php echo $_GET["idPerfil"]; ?>','controller/act-perfil','Excluir','idPerfil');">Excluir</button>
                    <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href='cad-perfil';">Cadastrar Novo</button>
                    <input type="hidden" id="idPerfil" name="idPerfil" value="<?php echo $_GET["idPerfil"]; ?>" />
                    <input type="hidden" id="acao" name="acao" value="Atualizar" />
                <?php } ?>
                <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href='menu-perfis';">Voltar</button>
            </div>
            <br/>
        </form>