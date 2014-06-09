<?php 
	$limite = 20;
	$pagina = $_GET['pag']?$_GET['pag']:1;
	$inicio = ($pagina * $limite) - $limite;
	$nmGrupo = $_REQUEST['nmGrupo'];
	$filtro = strlen($nmGrupo)?(' AND nmGrupo LIKE '.$db->clean('%'.$nmGrupo.'%')):'';
	$qrTotal = "SELECT COUNT(*) as total_registros FROM tb_news_grupo A WHERE 1=1".$filtro;
	$total_registros = $db->query($qrTotal);
	$total_registros = $total_registros[0]["total_registros"]?$total_registros[0]["total_registros"]:0;
	$qryGrupos = $db->select(array(
								'nmGrupo' 	=> 'contains:'.$nmGrupo,
								'orderby' 	=> 'nmGrupo',
								'limit' 	=> $limite,
								'offset' 	=> $inicio), 'tb_news_grupo');
?> 	    
<form name="formGeral" id="formGeral" action="menu-news-grupo" method="post">
<h1>Manutenção de Grupos</h1>
<br/>
<h2 class="separadador">Últimas Cadastradas (<?php echo $total_registros; ?>)</h2>
<div align="left">
	<table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
	<tr>
		<td width="7%">Filtrar por Nome</td>
		<td width="93%">
		<input name="nmGrupo" size="30" maxlength="200" style="top:auto" value="<?php echo $nmGrupo; ?>"/>
        </td>
	</tr>
	</table>
    <button type="submit">Consultar</button>
    <button type="button" onClick="location.href='cad-news-grupo';">Cadastrar Novo</button>
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
	$filtro2 = strlen($nmGrupo)?('nmGrupo='.$nmGrupo):'';;
	$stringPagina = $url_raiz.'admin/menu-news-grupo?'.$filtro2;
	navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
?>
</div>
<fieldset>
<table width="100%" border="0" align="center" class="tbLista">
    <tr class="tbTitulo">
        <td colspan="2" align="center">Op&ccedil;&otilde;es</td>
        <td width="45%" align="left">Nome</td>
    </tr>
    <?php for($i=0;$i<count($qryGrupos); $i++){?>
    <tr class="<?php echo cycle('tbNormal','tbNormalAlt'); ?>">
        <td align="center" width="3%">
			<img src="<?php echo $url_raiz; ?>admin/img/del.png" 
				onClick="excluirItem(<?php echo $qryGrupos[$i]["idGrupo"]; ?>,'controller/act-news','excluirGrupo','id')"
				style="cursor:pointer" title="Excluir" alt="Excluir"/>
        </td>
        <td align="center" width="3%">
			<img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
				onclick="location.href='cad-news-grupo?id=<?php echo $qryGrupos[$i]["idGrupo"]; ?>';" 
				style="cursor:pointer" title="Editar" alt="Editar"/> 
		</td>
        <td width="91%" align="left"><?php echo $qryGrupos[$i]["nmGrupo"]; ?></td>
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