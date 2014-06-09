<?php 
	$limite = 20;
	$pagina = $_GET['pag']?$_GET['pag']:1;
	$inicio = ($pagina * $limite) - $limite;
	
	$nmNome = $_REQUEST['nmNome'];
	$nmEmail = $_REQUEST['nmEmail'];
	$idGrupo = $_REQUEST['idGrupo'];
	
	$filtro .= strlen($nmNome)?(' AND A.nmNome LIKE '.$db->clean('%'.$nmNome.'%')):'';
	$filtro .= strlen($nmEmail)?(' AND A.nmEmail LIKE '.$db->clean('%'.$nmEmail.'%')):'';
	$filtro .= strlen($idGrupo)?(' AND B.idGrupo = '.$db->clean($idGrupo)):'';
	
	$qrTotal = "SELECT COUNT(*) as total_registros FROM tb_news_emails A ".
				"LEFT JOIN tb_news_grupo_emails B ON A.idEmail = B.idEmail WHERE 1=1".$filtro.' GROUP BY A.idEmail ORDER BY A.nmNome, nmEmail';
	$total_registros = $db->query($qrTotal);
	$total_registros = $total_registros[0]["total_registros"]?$total_registros[0]["total_registros"]:0;
	
	$qryEmails = $db->query(str_replace('COUNT(*) as total_registros', 'A.*', $qrTotal));
	$qryGrupos = $db->select(array('orderby' => 'nmGrupo'), 'tb_news_grupo');
?> 	    
<form name="formGeral" id="formGeral" action="menu-news-emails" method="post">
<h1>Manutenção de E-mails</h1>
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
    <tr>
		<td width="7%">Filtrar por E-mail</td>
		<td width="93%">
		<input name="nmEmail" size="30" maxlength="200" style="top:auto" value="<?php echo $nmEmail; ?>"/>
        </td>
	</tr>
    <tr>
    	<td>Filtrar por Grupo</td>
        <td>
        	<select name="idGrupo">
            	<option value="">Todos</option>
            	<?php foreach($qryGrupos as $g): ?>
                <option <?php echo ($g['idGrupo']==$idGrupo)?'selected="selected"':''; ?> value="<?php echo $g['idGrupo'];?>"><?php echo $g['nmGrupo']; ?></option>
                <?php endforeach;?>
            </select>
        </td>
    </tr>
	</table>
    <button type="submit">Consultar</button>
    <button type="button" onClick="location.href='cad-news-emails';">Cadastrar Novo</button>
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
	$stringPagina = $url_raiz.'admin/menu-news-emails?'.$filtro2;
	navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
?>
</div>
<fieldset>
<table width="100%" border="0" align="center" class="tbLista">
    <tr class="tbTitulo">
        <td colspan="2" align="center">Op&ccedil;&otilde;es</td>
        <td width="30%" align="left">Nome</td>
        <td width="51%" align="left">E-mail</td>
    </tr>
    <?php for($i=0;$i<count($qryEmails); $i++){?>
    <tr class="<?php echo cycle('tbNormal','tbNormalAlt'); ?>">
        <td align="center" width="3%">
			<img src="<?php echo $url_raiz; ?>admin/img/del.png" 
				onClick="excluirItem(<?php echo $qryEmails[$i]["idEmail"]; ?>,'controller/act-news','excluirEmail','id')"
				style="cursor:pointer" title="Excluir" alt="Excluir"/>
        </td>
        <td align="center" width="3%">
			<img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
				onclick="location.href='cad-news-emails?id=<?php echo $qryEmails[$i]["idEmail"]; ?>';" 
				style="cursor:pointer" title="Editar" alt="Editar"/> 
		</td>
        <td align="left"><?php echo $qryEmails[$i]["nmNome"]; ?></td>
        <td align="left"><?php echo $qryEmails[$i]["nmEmail"]; ?></td>
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