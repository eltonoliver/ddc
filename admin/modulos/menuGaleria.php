<?php 
	$qryTipoConteudo = $db->query("SELECT * FROM tb_tipo_conteudo ORDER BY nmTipoConteudo");
		$limite = 10;
		$pagina = $_GET['pag']?$_GET['pag']:1;
		$filtro = '';
		$filtro2 = '';
		
		if(strlen($_REQUEST['nmGaleria'])){
			$filtro .= ' AND nmGaleria LIKE '.$db->clean('%'.$_REQUEST['nmGaleria'].'%');
			$filtro2 .= '&nmGaleria='.$_REQUEST['nmGaleria'];
			$nmGaleria = $_REQUEST['nmGaleria'];
		}
		
		//Calcula os registros inicial e final as serem pesquisados no banco de dados
		$inicio = ($pagina * $limite) - $limite;
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT 		COUNT(*) as total_registros
					FROM 		tb_galeria A 
					WHERE	 	idGaleria > 0
								".$filtro."
					ORDER BY dtDataGaleria DESC";
		
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
		$qryGaleria = $db->query($s = str_replace("COUNT(*) as total_registros", "*", $qrTotal).' LIMIT '.$inicio.",".$limite);
?> 	
            
<form name="formGeral" id="formGeral" action="<?php echo $url_raiz; ?>admin/menu-galeria" method="post">
<h1>Manutenção de Galeria</h1>
<br/>
<?php include('sisMensagem.php');  ?>
<h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
<div align="left">
    <table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
            <td width="100px;">Filtrar por Nome</td>
            <td><input name="nmGaleria" size="30" maxlength="200" value="<?php echo $nmGaleria; ?>"/></td>
        </tr>
    </table>
    <button type="submit">Consultar</button>
    <button type="button" onClick="location.href='<?php echo $url_raiz; ?>admin/menu-galeria';">Limpar filtros</button>
    <button type="button" onClick="location.href='<?php echo $url_raiz; ?>admin/cad-galeria';">Cadastrar Novo</button>
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
		$stringPagina = $url_raiz.'admin/menu-galeria?pesquisa'.$filtro2;
		navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
    ?>
</div>
<fieldset>
<table width="100%" border="0" align="center" class="tbLista">
    <tr class="tbTitulo">
        <td colspan="3" align="center">Op&ccedil;&otilde;es</td>
        <td width="60px" align="center">Ativo?</td>
        <td width="80px" align="left">Data</td>
        <td align="left">T&iacute;tulo</td>
    </tr>
    <?php foreach($qryGaleria as $g){?>
    <tr class="<?php echo cycle('tbNormalAlt', 'tbNormal'); ?>">
        <td align="center" width="20px">
        <a style="cursor:pointer;" onclick="popupHtml('<?php echo $url_raiz; ?>admin/excluir-galeria?ajax=1&id=<?php echo $g['idGaleria']; ?>', '500', '500', true);">
            <img src="<?php echo $url_raiz; ?>admin/img/del.png" title="Excluir" alt="Excluir"/></a>
        </td>
        <td align="center" width="20px">
        <a href="<?php echo $url_raiz; ?>admin/cad-galeria?id=<?php echo $g['idGaleria']; ?>">
            <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" title="Editar" alt="Editar"/></a>
        </td>
        <td align="center" width="20px">
            <?php if($g["inAtivo"] == 1){ ?>
            <a href="<?php echo $url_raiz; ?>admin/controller/act-galeria?acao=desativar&id=<?php echo $g['idGaleria']; ?>">
                <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" title="Publicado. Clique para ocultar" alt="Publicado. Clique para ocultar"/></a>
            <?php } else { ?>
            <a href="<?php echo $url_raiz; ?>admin/controller/act-galeria?acao=ativar&id=<?php echo $g['idGaleria']; ?>">
                <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" title="Oculto. Clique para publicar." alt="Oculto. Clique para publicar"/></a>
            <?php } ?>
        </td>
        <td align="center"><?php echo $g["inAtivo"]?'Sim':'Não'; ?></td>
        <td align="left"><?php echo date('d/m/Y', strtotime($g['dtDataGaleria'])); ?></td>
        <td align="left"><?php echo $g['nmGaleria']; ?></td>
    </tr>
    <?php } ?>
</table>
</fieldset>
<br/>            
<div align="center">
    <?php  navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);?>
</div>
<br/>
<?php } //Fim - if?>