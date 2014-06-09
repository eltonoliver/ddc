<?php 
	$qryTags = $db->query("SELECT * FROM tb_categoria WHERE inTipo = 2 ORDER BY nmCategoria");
?>
<table bgcolor="#ffffff" width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr class="FonteTituloPOP">
    <td width="96%" style="color:#000;">Adicionar Tag</td>
    <td width="2%"><img src="<?php echo $url_raiz; ?>admin/js/modalMessage/botFechar.png" onClick="closeMessage();" title="Fechar" onMouseOver="javascript: this.style.cursor = 'pointer';"></td>
  </tr>
</table>
    <table width="100%" border="0" cellspacing="5" cellpadding="0" id="tabForm">
      <tr>
        <td align="left" class="FonteGeral">
        <p id="msgErroCadTagP" style="display:none;text-align:center;">
    <img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/>
    <span id="msgErroCadTag">Ocorreu um erro interno do sistema.</span></p>
                      <form id="formEnviarTag" name="formEnviarTag" method="post" action="<?php echo $destino; ?>" onsubmit="return false;">
                        <table width="100%" border="0" cellpadding="1" cellspacing="0">
                          <tr>
                            <td colspan="2" align="left" class="formLabel"><br/><span class="destaque">* </span>Nome</td>
                          </tr>
                          <tr>
                            <td width="54%" align="left">
                                  <select name="idCategoriaSelect" id="idCategoriaSelect" style="top:auto; width:100%;">
                                        <option value="">[Selecione]</option>
                                        <?php for($i=0; $i<count($qryTags); $i++){ ?>
                                            <option value="<?php echo $qryTags[$i]["idCategoria"]; ?>"><?php echo utf8_encode($qryTags[$i]["nmCategoria"]); ?></option>
                                        <?php } ?>
                                  </select>
                            </td>
                            <td width="46%" align="center">
                            <input type="hidden" name="idConteudo" id="idConteudoIdTag" value="<?php echo $_GET["idItem"]; ?>">
                            <input type="hidden" name="acao" id="acao" value="adicionarTagAux">
                            <button name="submit" id="submit" onclick="adicionarNovaTagConteudo();">Adicionar</button>
                        	</td>
                          </tr>

                          <tr>
                            <td colspan="2" align="left" class="formLabel"><br/><span class="destaque">* </span>Nova Tag</td>
                          </tr>
                          <tr>
                            <td width="54%" align="left">
                                  <input type="text" style="width:95%;" name="novaTag" id="novaTag">
                            </td>
                            <td width="46%" align="center">
                            <button name="submit" id="submit" onclick="criarNovaTagConteudo();">Adicionar</button>
                        	</td>
                          </tr>
							
                        </table>
                        <br/>
                        
                      </form>
            
        </td>
      </tr>
</table>
<div id="loading" style="display:none;"><p align="center" style="margin-top:50px;"><img src='img/ajax-loader.gif'></p></div>
<!--- Inicio Tabela Esquerda--->    
<script type="text/javascript">	
	var id = 'idConteudoIdTag';	
	function getIdTag(){
		return $('#' + id).val() + '_' + $('#idCategoriaSelect').val();
	}

	function adicionarNovaTagConteudo(){
		if($('#idCategoriaSelect').val() != ''){
			closeMessage();
			var idEl = getIdTag();
			if(!document.getElementById(idEl)){
				$('#tagsAdd_'+$('#' + id).val()).append(
				'<div style="float:left; padding:5px;" id="'+idEl+'">'+
				'<img style="cursor:pointer" src="img/erro.gif" onclick="excluirTag(\''+idEl+'\')">&nbsp;'+
				$('#idCategoriaSelect option:selected').text()+
				'<input type="hidden" name="tags[]" value="'+$('#idCategoriaSelect').val()+'"></div>');
				$('#' + idEl).css('opacity', 0).css('display', 'inline').animate({opacity:1}, 'slow');
			}
		}
	}
	
	function criarNovaTagConteudo(){
		if(!limparJSX($('#novaTag').val())){
			$('#novaTag').empty();
			$('#msgErroCadTagP').show();
			setTimeout(function(){$('#msgErroCadTagP').hide();}, 3000);
			return 0;
		}
		
		$('#tabForm').css('display', 'none');
		$('#loading').css('display', 'inline');
		
		var d = {
					'nmCategoria':$('#novaTag').val(),
					'idCategoriaPai':0,
					'inTipo':2,
					'inDestaque':0,
					'acao':'cadastrar-json'
				};
		
		$.ajax({
			'type':'POST',
			'url':'<?php echo $url_raiz; ?>admin/controller/act-categoria',
			'dataTypeString':'json',
			'data':d,
			'success':function(data){
				var r = $.parseJSON(data);
				if(r.id){
					closeMessage();
					var idEl = $('#' + id).val() + '_' + r.id;
					if(!document.getElementById(idEl)){
						$('#tagsAdd_'+$('#' + id).val()).append(
						'<div style="float:left; padding:5px;" id="'+idEl+'">'+
						'<img style="cursor:pointer" src="img/erro.gif" onclick="excluirTag(\''+idEl+'\')">&nbsp;'+
						$('#novaTag').val()+
						'<input type="hidden" name="tags[]" value="'+r.id+'"></div>');
						$('#' + idEl).css('opacity', 0).css('display', 'inline').animate({opacity:1}, 'slow');
					}
					
				}else{
					$('#msgErroCadTagP').css('display', 'inline');
					if(r.status == 1){
						$('#msgErroCadTag').html('Tag digitada j� est� cadastrada.');
					}else{
						$('#msgErroCadTag').html('Ocorreu um erro interno do sistema.');
					}
					$('#tabForm').css('display', 'inline');
					$('#loading').css('display', 'none');
				}
			}
		});
	}
	
	function limparJSX(description){
		description.replace(/["\'][\s]*javascript:(.*)["\']/gi, "\"\""); 
		description = description.replace(/<script(.*)/gi, ""); 
		description = description.replace(/eval\((.*)\)/gi, "");
		return description;
	}
	
</script>