<?php
//PREPARA��O DO PAGINADOR
//Define o total de registros por p�gina
$limite = 5;

//Pega o n�mero da p�gina que v�m pela URL
$pagina = $_GET['pag'];

//Seta a p�gina inicial
if (!$pagina) {
    $pagina = 1;
}

//Calcula os registros inicial e final as serem pesquisados no banco de dados
$inicio = ($pagina * $limite) - $limite;

//Seta um filtro vazio
$filtro = '';

//Se veio o tipo no FORM ou URL, adiciona ao filtro
if (isset($_REQUEST["nmCliente"])) {
    $nmCliente = $_REQUEST["nmCliente"];
}

if (isset($_REQUEST["idCliente"])) {
    $idCliente = $_REQUEST["idCliente"];
}

if (strlen($idCliente) > 0) {
    $filtro = $filtro . ' ' . $filtro = 'AND idCliente = ' . $idCliente;
}
if (strlen($nmCliente) > 0) {
    $filtro = $filtro . ' ' . $filtro = 'AND nmCliente like "%' . $nmCliente . '%"';
}

//Busca o total de registros da consulta n�o paginada
$qrTotal = "SELECT COUNT(idCliente) as total_registros FROM tb_cliente WHERE idCliente > 0" . $filtro;
$total_registros = $db->query($qrTotal);
$total_registros = $total_registros[0]["total_registros"];
?>

<?php
//QUERY PAGINADA
//Exemplo: "SELECT * FROM nome_da_tabela LIMIT $inicio,$limite"

$qryCont = "
			SELECT 		*
			FROM 		tb_cliente
			WHERE 		idCliente > 0
			" . $filtro . " 
			ORDER BY 	nmCliente
			LIMIT 		" . $inicio . "," . $limite . "		
		";

$qryPaginada = $db->query($qryCont);
?>
<form name="formGeral" id="formGeral" action="menu-clientes" method="post">
    <h1>Manutenção de Clientes</h1>
    <br/>
    <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
    <div align="left">
        <table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td width="7%">Filtrar por Nome</td>
                <td width="93%"><input name="nmCliente" id="nmCliente" size="30" maxlength="200" style="top:auto" value="<?php echo $nmCliente; ?>"/></td>
            </tr>
            <tr>
                <td width="7%">Filtrar por Código</td>
                <td><input name="idCliente" id="idCliente" size="10" maxlength="10" style="top:auto" value="<?php echo $idCliente; ?>"/></td>
            </tr>
        </table>
        <button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
        <button type="button" name="btEnviar" id="btEnviar" onClick="javascript: location.href = 'menu-clientes';">Limpar filtros</button>
        <button type="button" name="btNovo" id="btNovo" onClick="javascript: location.href = 'cad-cliente';">Cadastrar Novo</button>
    </div>
    <br/>
</form>

<?php if ($total_registros == 0) { //Se a consulta voltou sem nenhum resultado. ?>
    <div class="msgBox">
        <p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/> Nenhum resultado para a consulta realizada.</p>
    </div>

<?php } else { //Se foram encontrados resultados.  ?>
    <div align="center">
        <?php
        //NAVEGA��O DO PAGINADOR
        //Calcula o total de p�ginas
        $total_paginas = ceil($total_registros / $limite);

        //Define a p�gina de direcionamento dos links
        //Seta um filtro vazio
        $filtro2 = '';

        //Se est� definido o tipo, adiciona consulta do ano ao filtro
//                            if (strlen($idCategoriaPai) > 0){
//                                $filtro2 = $filtro2 .''. $filtro2 = 'idCategoriaPai='.$idCategoriaPai;
//                            } 
        //Nome da p�gina 
        $stringPagina = '?' . $filtro2;

        //Chama a fun��o que monta a exibi��o do paginador
        navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
        ?>
    </div>
    <fieldset>
        <table width="100%" border="0" align="center" class="tbLista">
            <tr class="tbTitulo">
                <td width="9%" colspan="3" align="center">Op&ccedil;&otilde;es</td>
                <td width="8%" align="ceter">C&oacute;digo</td>
                <td width="43%" align="left">Nome</td>
                <td width="40%" align="left">E-mail</td>
            </tr>
            <?php
            $coluna = 1;
            for ($i = 0; $i < count($qryPaginada); $i++) {

                if ($coluna % 2 == 0) {
                    $classe = 'tbNormal';
                } else {
                    $classe = 'tbNormalAlt';
                }
                ?>
                <tr class="<?php echo $classe; ?>">
                    <td align="center" width="3%">
                        <img src="<?php echo $url_raiz; ?>/admin/img/del.png" 
                             onclick="excluirItem('<?php echo $qryPaginada[$i]["idCliente"]; ?>', 'controller/act-cliente', 'Excluir', 'idCliente');"
                             onmouseover="javascript: this.style.cursor = 'pointer';"
                             title="Excluir"
                             alt="Excluir"/>
                    </td>
                    <td align="center" width="3%">
                        <img src="<?php echo $url_raiz; ?>/admin/img/editar2.png" 
                             onclick="javascript: location.href = 'cad-cliente?idCliente=<?php echo $qryPaginada[$i]["idCliente"]; ?>';" 
                             onmouseover="javascript: this.style.cursor = 'pointer';"
                             title="Editar"
                             alt="Editar"/>
                    </td>
                    <td align="center" width="3%">

                        <img src="<?php echo $url_raiz; ?>/admin/img/iconeOrcamento.png" 
                             onclick="javascript: location.href = 'cad-cliente?idCliente=<?php echo $qryPaginada[$i]["idCliente"]; ?>&pedido';" 
                             onmouseover="javascript: this.style.cursor = 'pointer';"
                             title="Adicionar Orçamento"
                             alt="Adicionar Orçamento"/>

                    </td>
                    <td width="8%" align="center"><?php echo $qryPaginada[$i]["idCliente"]; //str_pad($qryPaginada[$i]["idCliente"], 10, "0", STR_PAD_LEFT);                   ?></td>
                    <td width="43%" align="left"><?php echo $qryPaginada[$i]["nmCliente"]; ?></td>
                    <td width="40%" align="left"><?php echo $qryPaginada[$i]["nmEmailCliente"]; ?></td>
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
        //NAVEGA��O DO PAGINADOR
        //Calcula o total de p�ginas
        $total_paginas = ceil($total_registros / $limite);

        //Define a p�gina de direcionamento dos links
        //Seta um filtro vazio
        $filtro2 = '';

        //Se est� definido o tipo, adiciona consulta do ano ao filtro
//                            if (strlen($idCategoriaPai) > 0){
//                                $filtro2 = $filtro2 .''. $filtro2 = 'idCategoriaPai='.$idCategoriaPai;
//                            } 
        //Nome da p�gina 
        $stringPagina = '?' . $filtro2;

        //Chama a fun��o que monta a exibi��o do paginador
        navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
        ?>
    </div>

<?php } ?>