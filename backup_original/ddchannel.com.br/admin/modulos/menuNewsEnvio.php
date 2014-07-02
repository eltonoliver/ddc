<?php 
	$limite = 20;
	$pagina = $_GET['pag']?$_GET['pag']:1;
	$inicio = ($pagina * $limite) - $limite;
	
	$nmNome = $_REQUEST['nmNome'];
	$filtro .= strlen($nmNome)?(' AND D.nmTituloConteudo LIKE '.$db->clean('%'.$nmNome.'%')):'';
	
	$qrTotal = "SELECT COUNT(*) as total_registros FROM tb_news_envio A ".
				"LEFT JOIN tb_news_envio_enviando B ON A.idEnvio = B.idEnvio ".
				"LEFT JOIN tb_news_envio_conteudo C ON C.idEnvio = A.idEnvio ".
				"LEFT JOIN vwconteudoarquivo D ON C.idConteudo = D.idConteudo WHERE 1=1 ".
				$filtro.'  GROUP BY A.idEnvio ORDER BY A.dtDataCadastro DESC, D.nmTituloConteudo ASC';
	
	$total_registros = $db->query($qrTotal);
	$total_registros = $total_registros[0]["total_registros"]?$total_registros[0]["total_registros"]:0;
	$qryEnvios = $db->query(str_replace('COUNT(*) as total_registros', 'A.*, D.nmTituloConteudo, B.idEnviando', $qrTotal));
	$tiposConteudosEnvio = array(
		'11' => 'Boletim',
		'-1' => 'Clipping',
		'10' => 'Informativo'
	);
	
?>
<form name="formGeral" id="formGeral" action="menu-news-envio" method="post">
<h1>Manutenção de Envios</h1>
<br/>
<h2 class="separadador">Últimas Cadastradas (<?php echo $total_registros; ?>)</h2>
<div align="left">
	<table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
	<tr>
		<td width="7%">Filtrar por Nome</td>
		<td width="93%">
		<input name="nmNome" size="30" maxlength="200" style="top:auto" value="<?php echo $nmNome; ?>"/>
        </td>
	</tr>
	</table>
    <button type="submit">Consultar</button>
    <button type="button" onClick="location.href='cad-news-envio';">Cadastrar Novo</button>
</div>
<br/>
</form>
<?php if($total_registros == 0){ //Se a consulta voltou sem nenhum resultado.?>
    <div class="msgBox">
        <p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/> Nenhum resultado para a consulta realizada.</p>
    </div>
<?php } else { //Se foram encontrados resultados. ?>
<div align="center">
<?php
	$total_paginas = ceil($total_registros/$limite);
	$filtro2 = strlen($nmGrupo)?('nmNome='.$nmNome):'';;
	$stringPagina = $url_raiz.'admin/menu-news-envio?'.$filtro2;
	navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
?>
</div>
<fieldset>
<table width="100%" border="0" align="center" class="tbLista">
    <tr class="tbTitulo">
        <td colspan="4" align="center">Op&ccedil;&otilde;es</td>
        <td width="10%" align="left">Data de cadastro</td>
        <td width="10%" align="center">Tipos de News</td>
        <td align="left">Título</td>
    </tr>
    <?php for($i=0;$i<count($qryEnvios); $i++){?>
    <tr class="<?php echo cycle('tbNormal','tbNormalAlt'); ?>">
        <td align="center" width="3%">
        	<?php if(!$qryEnvios[$i]['inEnviado']): ?>
			<img src="<?php echo $url_raiz; ?>admin/img/del.png" 
				onClick="excluirItem(<?php echo $qryEnvios[$i]["idEnvio"]; ?>,'controller/act-news','excluirEnvio','id')"
				style="cursor:pointer" title="Excluir" alt="Excluir"/>
            <?php else: ?>
            -
            <?php endif; ?>
        </td>
        <td align="center" width="3%">
        	<?php if(!$qryEnvios[$i]['inEnviado']): ?>
			<img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
				onclick="location.href='cad-news-envio?id=<?php echo $qryEnvios[$i]["idEnvio"]; ?>';" 
				style="cursor:pointer" title="Editar" alt="Editar"/> 
        	<?php else: ?>
            -
            <?php endif; ?>
		</td>
        <td align="center" width="3%">
        	<?php if(!$qryEnvios[$i]['inEnviado'] && !$qryEnvios[$i]['idEnviando']): ?>
            <img src="<?php echo $url_raiz; ?>admin/img/iconesAprovar.png" 
				onClick="iniciarEnvio('<?php echo $qryEnvios[$i]["idEnvio"]; ?>');"
				style="cursor:pointer" title="Iniciar Envio" alt="Iniciar Envio"/>
             <?php elseif($qryEnvios[$i]['idEnviando']): ?>
             <img src="<?php echo $url_raiz; ?>admin/img/iconeAtualizar.png" 
				onclick="iniciarEnvio('<?php echo $qryEnvios[$i]["idEnvio"]; ?>');" 
				style="cursor:pointer" title="Continuar Envio" alt="Continuar Envio"/>
             <?php else:?>
             -
             <?php endif; ?>
        </td>
        <td>
        <?php $urlInfor = $url_raiz.'controller/boletim-controller?acao=visualizar&idEnvio='.$qryEnvios[$i]["idEnvio"];?>
        <img src="<?php echo $url_raiz; ?>admin/img/iconAmpliar.png" 
				onClick="popupFrame('<?php echo $urlInfor; ?>',600,700);"
				style="cursor:pointer" title="Visualizar Envio" alt="Visualizar Envio"/>
        </td>
        <td align="center" width="10%"><?php echo date('d/m/Y', strtotime($qryEnvios[$i]["dtDataCadastro"])); ?></td>
        <td align="center" width="10%">
		<?php echo $tiposConteudosEnvio[$qryEnvios[$i]['idTipoConteudo']] ?></td>
        <td align="left" width="71%">
		<?php $a = date('Y', strtotime($qryEnvios[$i]['dtDataCadastro']));?>
		<?php printf('%04d', $qryEnvios[$i]['numeroEnvio']);?>/<?php echo $a; ?> - <?php echo $qryEnvios[$i]["nmTituloConteudo"]; ?></td>
    </tr>
    <?php } ?>
</table>
</fieldset>
<br/>            
<div align="center">
    <?php navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);?>
</div>
<?php } ?>
<br/>

<script type="text/javascript">
	function iniciarEnvio(idEnvio){
		var w = 400;
		var h = 500;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open('<?php echo $url_raiz;?>admin/controller/act-news?acao=iniciarEnvio&idEnvio='+idEnvio, 'Iniciar Envio', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width='+w+',height='+h+', top='+top+', left='+left);
	}
</script>