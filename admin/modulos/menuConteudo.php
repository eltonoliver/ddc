<?php
$qryTipoConteudo = $db->query("SELECT * FROM tb_tipo_conteudo WHERE idTipoPagina IN(1,3)  AND idTipoConteudo <> 13 ORDER BY nmTipoConteudo");
//$qryCategorias = $db->query("SELECT * FROM tb_categoria WHERE inTipo = 1 ORDER BY nmCategoria");
//$qryTags = $db->query("SELECT * FROM tb_categoria WHERE inTipo = 2 ORDER BY nmCategoria");
//new dBug($qryTags);
//PREPARA�AO DO PAGINADOR
//Define o total de registros por p�gina
$limite = 10;

//Pega o n�mero da p�gina que vem pela URL
$pagina = $_GET['pag'];

//Seta a p�gina inicial
if (!$pagina) {
    $pagina = 1;
}

//Seta um filtro vazio
$filtro = '';

//Se veio o tipo no FORM ou URL, adiciona ao filtro
if (isset($_REQUEST["idTipoConteudo"]) && strlen($_REQUEST["idTipoConteudo"]) > 0) {
    $idTipoConteudo = $_REQUEST["idTipoConteudo"];
    $filtro .= " AND A.idTipoConteudo = " . $db->clean($idTipoConteudo);
}

if (isset($_REQUEST["nmTituloConteudo"]) && strlen($_REQUEST["nmTituloConteudo"]) > 0) {
    $nmTituloConteudo = $_REQUEST["nmTituloConteudo"];
    $filtro .= ' AND A.nmTituloConteudo like ' . $db->clean('%' . $nmTituloConteudo . '%');
}

//		if (isset($_REQUEST["idCategoria"]) && strlen($_REQUEST["idCategoria"]) > 0){
//			$idCategoria = $_REQUEST["idCategoria"];
//			$filtro .= " AND A.idCategoria = ".$db->clean($idCategoria);	
//		} 

if (isset($_REQUEST["idConteudo"]) && strlen($_REQUEST["idConteudo"]) > 0) {
    $idConteudo = $_REQUEST["idConteudo"];
    $filtro .= " AND A.idConteudo = " . $db->clean($idConteudo);
}


/* 		if (isset($_REQUEST["idTag"]) && strlen($_REQUEST["idTag"]) > 0){
  $idTag = $_REQUEST["idTag"];

  $strTags = "SELECT DISTINCT(idConteudo) FROM tb_conteudo_tag WHERE idCategoria = ".$db->clean($idTag);
  $qryTagsFiltro = $db->query($strTags);
  $listaCategoria = campoMatrizParaLista('',$qryTagsFiltro,'idConteudo');

  if(strlen($listaCategoria) > 0){
  $filtro .= " AND A.idConteudo in (".$listaCategoria.")";
  }
  }
 */


//Calcula os registros inicial e final as serem pesquisados no banco de dados
$inicio = ($pagina * $limite) - $limite;

//Busca o total de registros da consulta nao paginada
$qrTotal = "SELECT 		COUNT(A.idConteudo) as total_registros
		 
					FROM 		tb_conteudo A 
					LEFT JOIN	tb_tipo_conteudo B ON (A.idTipoConteudo =  B.idTipoConteudo)
					
					WHERE	 	B.idTipoPagina IN(1,3)
								" . $filtro;

$total_registros = $db->query($qrTotal);
$total_registros = $total_registros[0]["total_registros"];
?>

<?php
//QUERY PAGINADA
//Exemplo: "SELECT * FROM nome_da_tabela LIMIT $inicio,$limite"

$qryCont = "
		
			SELECT 		B.nmTipoConteudo,A.* 
			
			FROM 		tb_conteudo A 
			LEFT JOIN	tb_tipo_conteudo B ON (A.idTipoConteudo =  B.idTipoConteudo)
			
			WHERE 		A.idTipoConteudo NOT IN ('" . $lista . "') 
			AND			B.idTipoPagina IN(1,3)
			" . $filtro . " 
			ORDER BY 	A.idConteudo DESC
			LIMIT 	" . $inicio . "," . $limite . "		
		";

$qryConteudo = $db->query($qryCont);
?> 	

<form name="formGeral" id="formGeral" action="menu-conteudo" method="post">
    <h1>Manutenção de Conteúdos</h1>
    <br/>
    <?php include('sisMensagem.php'); ?>
    <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
    <div align="left">
        <table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td width="7%">Filtrar por ID</td>
                <td width="93%"><input name="idConteudo" id="idConteudo" size="10" maxlength="20" style="top:auto" value="<?php echo $idConteudo; ?>"/></td>
            </tr>
            <tr>
                <td width="7%">Filtrar por Nome</td>
                <td width="93%"><input name="nmTituloConteudo" id="nmTituloConteudo" size="30" maxlength="200" style="top:auto" value="<?php echo $nmTituloConteudo; ?>"/></td>
            </tr>
            <tr>
                <td width="7%">Filtrar por Tipo</td>
                <td>
                    <select name="idTipoConteudo" id="idTipoConteudo">
                        <option value="">Todos</option>
                        <?php for ($i = 0; $i < count($qryTipoConteudo); $i++) { ?>
                            <option value="<?php echo $qryTipoConteudo[$i]["idTipoConteudo"]; ?>" <?php
                            if ($qryTipoConteudo[$i]["idTipoConteudo"] == $idTipoConteudo) {
                                echo 'selected';
                            }
                            ?>><?php echo $qryTipoConteudo[$i]["nmTipoConteudo"]; ?></option>
                                <?php } ?>
                    </select>
                </td>
            </tr>
            <?php /* ?>        

              <tr>
              <td width="7%">Filtrar por Categoria</td>
              <td width="93%">
              <select name="idCategoria" id="idCategoria"  style="top:auto">
              <option value="">Todas</option>
              <?php for($i=0; $i<count($qryCategorias); $i++){ ?>
              <option value="<?php echo $qryCategorias[$i]["idCategoria"]; ?>" <?php if($qryCategorias[$i]["idCategoria"] == $idCategoria){ echo 'selected'; } ?>><?php echo $qryCategorias[$i]["nmCategoria"]; ?></option>
              <?php } ?>
              </select>
              </td>
              </tr>

              <?php */ ?>
            <?php /* ?>        
              <tr>
              <td width="7%">Filtrar por Tag</td>
              <td width="93%">
              <select name="idTag" id="idTag"  style="top:auto">
              <option value="">Todas</option>
              <?php for($j=0; $j<count($qryTags); $j++){ ?>
              <option value="<?php echo $qryTags[$j]["idCategoria"]; ?>" <?php if($qryTags[$j]["idCategoria"] == $idTag){ echo 'selected'; } ?>><?php echo $qryTags[$j]["nmCategoria"]; ?></option>
              <?php } ?>
              </select>
              </td>
              </tr>

              <?php */ ?>        
        </table>
        <button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
        <button type="button" name="btEnviar" id="btEnviar" onClick="javascript: location.href = 'menu-conteudo';">Limpar filtros</button>
        <button type="button" name="btEnviar" id="btEnviar" onClick="javascript: location.href = 'cad-conteudo?idTipoConteudo=<?php echo $idTipoConteudo; ?>';">Cadastrar Novo</button>
    </div>
    <br/>
</form>

<?php if ($total_registros == 0) { //Se a consulta voltou sem nenhum resultado.  ?>
    <div class="msgBox">
        <p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/> Nenhum resultado para a consulta realizada.</p>
    </div>

<?php } else { //Se foram encontrados resultados.  ?>
    <div align="center">
        <?php
        //NAVEGA�AO DO PAGINADOR
        //Calcula o total de p�ginas
        $total_paginas = ceil($total_registros / $limite);

        //Define a p�gina de direcionamento dos links
        //Seta um filtro vazio
        $filtro2 = '';

        //Se est� definido o tipo, adiciona consulta do ano ao filtro
        if (strlen($idTipoConteudo) > 0) {
            $filtro2 = $filtro2 . '' . $filtro2 = '&idTipoConteudo=' . $idTipoConteudo;
        }

        //Se est� definido o tipo, adiciona consulta do ano ao filtro
        if (strlen($nmTituloConteudo) > 0) {
            $filtro2 = $filtro2 . '' . $filtro2 = '&nmTituloConteudo=' . $nmTituloConteudo;
        }

        if (strlen($idCategoria) > 0) {
            $filtro2 = $filtro2 . '' . $filtro2 = '&idCategoria=' . $idCategoria;
        }


        if (strlen($idConteudo) > 0) {
            $filtro2 = $filtro2 . '' . $filtro2 = '&idConteudo=' . $idConteudo;
        }

        if (strlen($idTag) > 0) {
            $filtro2 = $filtro2 . '' . $filtro2 = '&idTag=' . $idTag;
        }


        //Nome da p�gina 
        $stringPagina = '?pesquisa' . $filtro2;

        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
        ?>
    </div>
    <fieldset>
        <table width="100%" border="0" align="center" class="tbLista">
            <tr class="tbTitulo">
                <td width="9%" colspan="4" align="center">Op&ccedil;&otilde;es</td>
                <td width="8%" align="center">Tipo<br/>(Categoria)</td>
                <td width="8%" align="center">Publicado?</td>
                <td width="10%" align="center">Data</td>
                <td width="15%" align="left">T&iacute;tulo  (ID)</td>
                <td width="50%" align="left">Resumo</td>
            </tr>
            <?php
            $coluna = 1;
            for ($i = 0; $i < count($qryConteudo); $i++) {

                if ($coluna % 2 == 0) {
                    $classe = 'tbNormal';
                } else {
                    $classe = 'tbNormalAlt';
                }
                ?>
                <tr class="<?php echo $classe; ?>">
                    <td align="center" width="3%">
                        <img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                             onclick="excluirConteudo('<?php echo $qryConteudo[$i]["idConteudo"]; ?>');" 
                             onmouseover="javascript: this.style.cursor = 'pointer';"
                             title="Excluir"
                             alt="Excluir"/>
                    </td>
                    <td align="center" width="3%">
                        <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                             onclick="javascript: location.href = 'cad-conteudo?idConteudo=<?php echo $qryConteudo[$i]["idConteudo"]; ?>&idTipoConteudo=<?php echo $qryConteudo[$i]["idTipoConteudo"]; ?>';" 
                             onmouseover="javascript: this.style.cursor = 'pointer';"
                             title="Editar"
                             alt="Editar"/>
                    </td>
                    <td align="center" width="3%">

                        <?php if ($qryConteudo[$i]["inPublicar"] == 1) { ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png" 
                                 onclick="javascript: location.href = 'controller/act-conteudo?acao=Ocultar&idConteudo=<?php echo $qryConteudo[$i]["idConteudo"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor = 'pointer';"
                                 title="Publicado. Clique para ocultar"
                                 alt="Publicado. Clique para ocultar"/>

                        <?php } else { ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png" 
                                 onclick="javascript: location.href = 'controller/act-conteudo?acao=Publicar&idConteudo=<?php echo $qryConteudo[$i]["idConteudo"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor = 'pointer';"
                                 title="Oculto. Clique para publicar."
                                 alt="Oculto. Clique para publicar"/>

                        <?php } ?>
                    </td>
                    <?php if ($qryConteudo[$i]["idTipoConteudo"] == 21) { ?>
                        <td align="center" width="3%">
                            <img src="<?php echo $url_raiz; ?>admin/img/iconAmpliar.png" 
                                 onclick="javascript: location.href = 'inscritos-promocao?idPromocao=<?php echo $qryConteudo[$i]["idConteudo"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor = 'pointer';"
                                 title="Inscritos"
                                 alt="Inscritos"/>
                        </td>
                    <?php } else { ?>
                        <td align="center" width="3%">
                            <?php
                            if (is_file($raiz . 'admin/img/' . $qryConteudo[$i]["nmLang"] . '.png')) {
                                ?>
                                <img src="<?php echo $url_raiz; ?>admin/img/<?php echo $qryConteudo[$i]["nmLang"]; ?>.png" title="Idioma" alt="Idioma"/>
                                <?php
                            }
                            ?>
                        </td>
                    <?php } ?>
                    <td width="8%" align="center">
                        <?php
                        $qryNomeCat = $db->query('SELECT nmCategoria FROM tb_categoria WHERE idCategoria = ' . $qryConteudo[$i]["idCategoria"]);
                        echo $qryConteudo[$i]["nmTipoConteudo"];
                        echo $nomeCat = ($qryNomeCat) ? ("<br/>(" . $qryNomeCat[0]["nmCategoria"] . ")") : ("<br/>(Nenhuma)");
                        ?>
                    </td>
                    <td width="8%" align="center">
                        <?php if ($qryConteudo[$i]["inPublicar"] == 1) { ?>
                            Sim
                        <?php } else { ?>
                            Nao
                        <?php } ?>
                    </td>
                    <td width="10%" align="center"><?php echo date('d/m/Y', strtotime($qryConteudo[$i]["dtDataConteudo"])); ?></td>
                    <td width="15%" align="left"><?php echo $qryConteudo[$i]["nmTituloConteudo"]; ?><br/>(<?php echo $qryConteudo[$i]["idConteudo"]; ?>)</td>
                    <td width="50%" align="left"><?php echo $qryConteudo[$i]["nmResumo"]; ?></td>
                </tr>
                <?php
                $coluna++;
            }
            ?>
        </table>
    </fieldset>
    <br/>            
    <div align="center">
        <?php
        //NAVEGA�AO DO PAGINADOR
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
        ?>
    </div>
    <br/>
    <?php
} //Fim - if?>