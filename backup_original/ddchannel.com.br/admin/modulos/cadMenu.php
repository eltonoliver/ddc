<?php
	$qryMenu = $db->query("SELECT * FROM tb_menu WHERE idTipoMenu = 1 AND idMenu = ".$db->clean($_REQUEST["idMenu"]));
	$qryMenusPai = $db->query("SELECT * FROM tb_menu WHERE  idTipoMenu = 1 ORDER BY idMenu, nmMenu, ordemMenu"); //idMenuPai = 0 AND
	
	//$teste = geraSelectMenus($qryMenusPai);
	//echo '<select>';
	//echo $teste;
	//echo '</select>';
	
	$qryTipoMenu = $db->query("SELECT * FROM tb_tipo_menu ORDER BY nmTipoMenu DESC");
	$qryPerfis = $db->query("SELECT * FROM tb_perfil WHERE idPerfil > 1 ORDER BY idPerfil ASC");
	
	if($qryMenu){
		$linkMenu = $qryMenu[0]["linkMenu"];	
	} //else {
//		$linkMenu = 'conteudo.php';	
//	}
	
	$idMenuPai = $qryMenu[0]["idMenuPai"];
	
	$filtro = "";
	if($_REQUEST["idMenu"]){
		$filtro = "+'&idMenu=".$_REQUEST["idMenu"]."'";
	}
	
	if(isset($_REQUEST["idMenuPai"])){
		$idMenuPai = $_REQUEST["idMenuPai"];
		
		if(strlen($_REQUEST["idMenuPai"]) == 0){
			$qryUltimaOrdem = $db->query("SELECT MAX(ordemMenu) AS ordemMenu FROM tb_menu WHERE idMenuPai = 0");
			
		} else {
			$qryUltimaOrdem = $db->query("SELECT MAX(ordemMenu) AS ordemMenu FROM tb_menu WHERE idMenuPai = ".$db->clean($idMenuPai));
		}
		
		$ordemAtual = 1 + $qryUltimaOrdem[0]["ordemMenu"];
	} 
?>

<script type="text/javascript">
	function enviar(campo){
		location.href = 'cad-menu?idMenuPai='+campo.value<?php echo $filtro; ?>;
	}
	
	function mudaOrdem(campo){
		document.getElementById('ordemMenu').value = campo.value;
		document.getElementById('labelOrdem').innerHTML = campo.value; 
	}
</script>
            <form name="formGeral" id="formGeral" action="controller/act-menu" method="post" enctype="multipart/form-data">
            
            <h1>Cadastro de Menus</h1>
            <br/>
            
			<?php if(isset($_GET["atualizado"])){ ?>
                <div class="msgBox">
                    <p>Dados atualizados com sucesso!</p>
                </div>
            <?php } else if (isset($_GET["erro"])){?>
                <div class="msgBox">
                    <p><img src="<?php echo $url_raiz; ?>/img/alerta.gif" border="0"align="absbottom"/> Ocorreu um erro! Tente novamente ou contate o suporte.</p>
                </div>
            <?php } ?>
        
            <h2 class="separadador">Dados do Menu</h2>
            <div align="left">
                <?php if(!$qryMenu){ ?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                <?php } else {?>
                    <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir" id="btExcluir" onClick="excluirMenu('<?php echo $_GET["idMenu"]; ?>');">Excluir</button>
                    <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-menu';">Cadastrar Novo</button>
                <?php } ?>
                <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href='menu-menus';">Voltar</button>
            </div>
            <br/>
            
            <table width="100%" border="0" align="center" class="tabelaForm">
                <tr>
                    <td width="15%" valign="top">Menu Pai:</td>
                    <td width="85%" valign="top">
                      <select name="idMenuPai" id="idMenuPai"  style="top:auto" onChange="enviar(this);">
                            <option value="">Selecione</option>
                            <option value="0" <?php if(isset($_GET["idMenuPai"]) && $idMenuPai == 0){ echo 'selected';} ?>>Este é um menu pai</option>
                            <option value="">-----------------</option>
                            
                            <?php 
                                if($qryMenusPai){
                                    for($i=0; $i<count($qryMenusPai); $i++){ ?>
                                    <option value="<?php echo $qryMenusPai[$i]["idMenu"]; ?>" <?php if($qryMenusPai[$i]["idMenu"] == $idMenuPai){ echo 'selected';} ?>><?php echo $qryMenusPai[$i]["descricaoMenu"]; ?></option>
                                    <?php 
                                    }//Fim-for
                                }//Fim-if
                            ?>
                      </select>
                      <input type="hidden" id="idTipoMenu" name="idTipoMenu" value="1" /><!-- Para este projeto, fixado em 1 -->
                    </td>
                </tr> 
				<?php /* PARA ESTE PROJETO, N�O SER� UTILIZADO?>                
                <tr>
                    <td width="15%" valign="top">Tipo de Menu:</td>
                    <td width="85%" valign="top">
                      <select name="idTipoMenu" id="idTipoMenu"  style="top:auto">
                            <option value="">Selecione</option>
							<?php for($i=0; $i<count($qryTipoMenu); $i++){ ?>
                                <option value="<?php echo $qryTipoMenu[$i]["idTipoMenu"]; ?>" <?php if($qryTipoMenu[$i]["idTipoMenu"] == $qryMenu[0]["idTipoMenu"]){ echo 'selected';} ?>><?php echo $qryTipoMenu[$i]["nmTipoMenu"]; ?></option>
                            <?php } ?>
                      </select>
                    </td>
                </tr> 
				<?php */?>                
				<tr>
                    <td width="15%">Nome:</td>
                    <td width="85%"><input name="nmMenu" id="nmMenu" size="60" maxlength="100" style="top:auto" value="<?php echo $qryMenu[0]["nmMenu"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Descrição do Menu:</td>
                    <td width="85%"><input name="descricaoMenu" id="descricaoMenu" size="60" maxlength="100" style="top:auto" value="<?php echo $qryMenu[0]["descricaoMenu"]; ?>"/></td>
                </tr>
                <?php if($_SESSION["PERFIL"] == 1){ ?>
                <tr>
                    <td width="15%">Link / URL:</td>
                    <td width="85%"><input name="linkMenu" id="linkMenu" size="60" maxlength="200" style="top:auto" value="<?php echo $linkMenu; ?>"/></td>
                </tr>
                <?php } else { ?>
               	 	<input type="hidden" name="linkMenu" id="linkMenu" size="60" maxlength="200" style="top:auto" value="<?php echo $linkMenu; ?>"/>
                <?php } ?>
                <?php if(!$qryMenu){ ?>
                    <tr>
                        <td width="15%">Ordem</td>
                        <td width="85%">
                            <select name="ordemMenu" id="ordemMenu"  style="top:auto">
                            <option value="" selected>--</option>
                                <?php for($i=1; $i<=50; $i++){ ?>
                                    <option value="<?php echo $i; ?>"
                                    
                                        <?php   //Se o nr for o mesmo, seleciona.
                                        if($i == $ordemAtual){ echo 'selected';} ?>
                                    ><?php echo $i;?></option>
                                    
                                <?php } ?>
                          </select>                    
                        </td>
                    </tr>
                <?php } else { ?>
                <tr>
                    <td width="15%">Ordem:</td>
                    <td width="85%">
                    	<span id="labelOrdem"><?php echo $qryMenu[0]["ordemMenu"]; ?></span>
                    	<input type="hidden" name="ordemMenu" id="ordemMenu" size="10" maxlength="10" style="top:auto" value="<?php echo $qryMenu[0]["ordemMenu"]; ?>"/></td>
                </tr>
                <tr>
                    <td width="15%">Muda Ordem?</td>
                    <td width="85%">
                        <select name="novaOrdem" id="novaOrdem"  style="top:auto" onChange="mudaOrdem(this);">
                        <option value="" selected>--</option>
                            <?php for($i=1; $i<=50; $i++){ ?>
                                <option value="<?php echo $i; ?>"
                                
									<?php   //Se o nr for o mesmo, seleciona.
									if($i == $ordemAtual){ echo 'selected';} ?>
                                ><?php echo $i;?></option>
                                
                            <?php } ?>
                      </select>                    
                    
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td width="10%" valign="top">Publicar?</td>
                    <td valign="top">
                        <select name="inExibir" id="inExibir"  style="top:auto">
                            <option value="1" <?php if($qryMenu[0]["inExibir"] == 1){ echo 'selected'; } ?>>Sim</option>
                            <option value="0" <?php if($qryMenu[0]["inExibir"] == 0){ echo 'selected'; } ?>>Nao</option>
                        </select>
                    </td>
                </tr>  
                <tr>
                    <td width="10%" valign="top">Perfil</td>
                    <td valign="top">
                        <span class="destaque_italico">&raquo; Por questões de segurança, todo menu gerencial criado, é automaticamente adicionado ao perdil "Administrador".</span>
                        <br/>
                        <input type="hidden" id="idPerfil_0" name="idPerfil_0" value="1"/>&nbsp;<img src="<?php $url_admin;?>img/fakeCheck.png" border="0"/>&nbsp; Administrador<br/>	                          	      

                    	<?php 
							if($qryMenu){
								$query = "SELECT DISTINCT(idPerfil) as idPerfil FROM tb_perfil_menu WHERE idMenu = ".$qryMenu[0]["idMenu"];
								$qryPefisMenu = $db->query($query); 
								
								$arrayPerfis = campoMatrizParaArray('',$qryPefisMenu,'idPerfil');
								
								//new dBUg($arrayPerfis);

							}
							$linha = 1;
							for($i=0; $i<count($qryPerfis); $i++){ ?>
									
                            <input type="checkbox" 	id="idPerfil_<?php echo $linha; ?>" 
                            						name="idPerfil_<?php echo $linha; ?>" 
                                                    value="<?php echo $qryPerfis[$i]["idPerfil"]; ?>"
                                                    <?php if(in_array($qryPerfis[$i]["idPerfil"],$arrayPerfis)){ echo 'checked="checked"'; }?>
                            /> <?php echo $qryPerfis[$i]["nmPerfil"]; ?><br/>	                          	      
                                
                        <?php $linha ++; } ?>
                        <input type="hidden" id="totalPerfis" name="totalPerfis" value="<?php echo count($qryPerfis); ?>"/> 
                    </td>
                </tr>                          
            </table>
            
            <br/>
            <div align="left">
                <?php if(!$qryMenu){ ?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Cadastrar</button>
                    <input type="hidden" id="acao" name="acao" value="Cadastrar" />
                <?php } else {?>
                    <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioSeguro(this.form);">Atualizar</button>
                    <button type="button" name="btExcluir2" id="btExcluir2" onClick="excluirMenu('<?php echo $_GET["idMenu"]; ?>');">Excluir</button>
                    <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href='cad-menu';">Cadastrar Novo</button>
                    <input type="hidden" id="idMenu" name="idMenu" value="<?php echo $_GET["idMenu"]; ?>" />
                    <input type="hidden" id="acao" name="acao" value="Atualizar" />
                <?php } ?>
                <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href='menu-menus';">Voltar</button>
            </div>
            <br/>
        </form>