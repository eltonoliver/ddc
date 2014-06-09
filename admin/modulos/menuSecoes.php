<?php 
	$qryCategoriasPai = $db->query("SELECT * FROM tb_categoria WHERE inTipo = 3 ORDER BY nmCategoria DESC");

	//PREPARA�AO DO PAGINADOR
		
		//Define o total de registros por p�gina
		$limite = 20;
		
		//Pega o n�mero da p�gina que vem pela URL
		$pagina = $_GET['pag'];
		
		//Seta a p�gina inicial
		if(!$pagina){
			$pagina = 1;
		}
	
		//Calcula os registros inicial e final as serem pesquisados no banco de dados
		$inicio = ($pagina * $limite) - $limite;
		
	//QUERY PAGINADA
		//Exemplo: "SELECT * FROM nome_da_tabela LIMIT $inicio,$limite"
		
		//Seta um filtro vazio
		$filtro = '';
		
		//Se veio o tipo no FORM ou URL, adiciona ao filtro
		if (isset($_REQUEST["idCategoriaPai"])){
			$idCategoriaPai = $_REQUEST["idCategoriaPai"];
		} 
		
		if(strlen($idCategoriaPai) > 0){
			$filtro = $filtro .' '. $filtro = 'AND A.idCategoriaPai = '.$idCategoriaPai;	
		}
		
		if (isset($_REQUEST["nmCategoria"])){
			$nmCategoria = $_REQUEST["nmCategoria"];
		} 
		
		if(strlen($nmCategoria) > 0){
			$filtro .= " AND A.nmCategoria like ".$db->clean("%".$nmCategoria."%");	
		}
		
		if (isset($_REQUEST["inTipo"])){
			$inTipo = $_REQUEST["inTipo"];
		} 
		
		if(strlen($inTipo) > 0){
			$filtro .= ' AND A.inTipo = '.$db->clean($inTipo);
		}
		
		//Busca o total de registros da consulta nao paginada
		$qrTotal = "SELECT COUNT(A.idCategoria) as total_registros FROM tb_categoria A WHERE A.inTipo = 3".$filtro;
		$total_registros = $db->query($qrTotal);
		$total_registros = $total_registros[0]["total_registros"];
		
		if (!$total_registros){
			$total_registros = 0;
		}
		
		$qryCont = "
			SELECT 		B.nmCategoria AS nmCategoriaPai,
						A.*
					
			FROM 		tb_categoria A
			
			LEFT JOIN	tb_categoria B ON (A.idCategoriaPai = B.idCategoria)
			WHERE 		A.inTipo = 3 
			".$filtro." 
			ORDER BY 	A.nrOrdem ASC, A.idCategoria DESC
			LIMIT 		".$inicio.",".$limite."		
		";
		
		$qryCategorias = $db->query($qryCont);
//			new dBug($qryCont);
//			new dBug($qryCategorias);
//			exit;
		
?> 	
            
<form name="formGeral" id="formGeral" action="menu-secoes" method="post">
<h1>Manutenção de Tags e Categorias</h1>
<br/>
<?php include('sisMensagem.php');  ?>

<h2 class="separadador">Últimas Cadastradas (<?php echo $total_registros; ?>)</h2>
<div align="left">
    <table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
            <td width="7%">Filtrar por Nome</td>
            <td width="93%"><input name="nmCategoria" id="nmCategoria" size="30" maxlength="200" style="top:auto" value="<?php echo $nmCategoria; ?>"/></td>
        </tr>
        
<?php /*?>        
        <tr>
            <td width="7%">Filtrar por Pai</td>
            <td>
              <select name="idCategoriaPai" id="idCategoriaPai"  style="top:auto">
                    <option value="">Todas</option>
                    <?php for($i=0; $i<count($qryCategoriasPai); $i++){ ?>
                    <option value="<?php echo $qryCategoriasPai[$i]["idCategoria"]; ?>" <?php if($qryCategoriasPai[$i]["idCategoria"] == $idCategoriaPai){ echo 'selected'; } ?>><?php echo $qryCategoriasPai[$i]["nmCategoria"]; ?></option>
                    <?php } ?>
              </select>
            </td>
        </tr>
        
<?php */?>        
    </table>
    
    <button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
    <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href='cad-secoes';">Cadastrar Nova</button>
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
        //NAVEGA�AO DO PAGINADOR
        
            //Calcula o total de p�ginas
            $total_paginas = ceil($total_registros/$limite);
            
            //Define a p�gina de direcionamento dos links
                //Seta um filtro vazio
                $filtro2 = '';
                
                //Se est� definido o tipo, adiciona consulta do ano ao filtro
                if (strlen($idCategoriaPai) > 0){
                    $filtro2 = $filtro2 .''. $filtro2 = '&idCategoriaPai='.$idCategoriaPai;
                } 
                if (strlen($inTipo) > 0){
                    $filtro2 = $filtro2 .''. $filtro2 = '&inTipo='.$inTipo;
                } 
                if (strlen($nmCategoria) > 0){
                    $filtro2 = $filtro2 .''. $filtro2 = '&nmCategoria='.$nmCategoria;
                } 
                
               
                //Nome da p�gina 
                $stringPagina = '?busca'.$filtro2;
            
            //Chama a fun�ao que monta a exibi�ao do paginador
            navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
        
    ?>
</div>
<fieldset>
<table width="100%" border="0" align="center" class="tbLista">
    <tr class="tbTitulo">
        <td colspan="2" width="6%" align="center">Op&ccedil;&otilde;es</td>
        <td width="6%" align="center">ID</td>
        <td width="14%" align="center">Nome</td>
        <td width="26%" align="center">Tipos de Conte&uacute;do</td>
        <td width="45%" align="left">Classifica&ccedil;&atilde;o</td>
    </tr>
    <?php 
        $coluna = 1;
        for($i=0; $i<count($qryCategorias); $i++){
        
        if($coluna % 2 == 0){
            $classe = 'tbNormal';
        } else {
            $classe = 'tbNormalAlt';
        }
    ?>
    <tr class="<?php echo $classe; ?>">
        <td align="center" width="3%">
                <img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                     onclick="excluirItem('<?php echo $qryCategorias[$i]["idCategoria"]; ?>','controller/act-secoes','Excluir','idCategoria');"
                     onmouseover="javascript: this.style.cursor='pointer';"
                     title="Excluir"
                     alt="Excluir"/>
        </td>
        <td align="center" width="3%">
                <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                     onclick="javascript: location.href='cad-secoes?idCategoria=<?php echo $qryCategorias[$i]["idCategoria"]; ?>&idCategoriaPai=<?php echo $qryCategorias[$i]["idCategoriaPai"]; ?>';" 
                     onmouseover="javascript: this.style.cursor='pointer';"
                     title="Editar"
                     alt="Editar"/>
        </td>
        <td width="6%" align="center"><?php echo $qryCategorias[$i]["idCategoria"]; ?></td>
      <td width="14%" align="center"><?php echo $qryCategorias[$i]["nmCategoria"]; ?></td>
        <td align="center"><?php echo $qryCategorias[$i]["nmListaTipoConteudo"]; ?></td>
        <td width="45%" align="left">
			<?php 
                    if($qryCategorias[$i]["inTipo"] == 1){
                        echo 'Categoria';
                        
                    } else if($qryCategorias[$i]["inTipo"] == 2){
                        echo 'Tag';
                        
                    } else if($qryCategorias[$i]["inTipo"] == 3){
                        echo 'Se��o de Menu';
                        
                    } else {
                        echo '-';
                    }
                
            ?>
        </td>
    </tr>
    <?php $coluna++; } ?>
</table>
</fieldset>
<br/>            
<div align="center">
    <?php  
        //NAVEGA�AO DO PAGINADOR
        
            //Calcula o total de p�ginas
            $total_paginas = ceil($total_registros/$limite);
            
            //Chama a fun�ao que monta a exibi�ao do paginador
            navegacaoPaginadorSimples($total_registros,$total_paginas,$limite,$stringPagina,$pagina);
        
    ?>
</div>

<?php } ?>

<br/>