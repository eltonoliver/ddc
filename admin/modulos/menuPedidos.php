<?php
//PREPARAÇÃO DO PAGINADOR
//Define o total de registros por página
$limite = 5;

//Pega o número da página que vêm pela URL
$pagina = $_GET['pag'];

//Seta a página inicial
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

if (isset($_REQUEST["idPedido"])) {
    $idPedido = $_REQUEST["idPedido"];
}
if (isset($_REQUEST["nrPedido"])) {
    $nrPedido = $_REQUEST["nrPedido"];
}
if (isset($_REQUEST["nrAnoPedido"])) {
    $nrAnoPedido = $_REQUEST["nrAnoPedido"];
}

if (strlen($idPedido) > 0) {
    $filtro .= ' AND A.idPedido = ' . $db->clean($idPedido);
}

if (strlen($nrPedido) > 0) {
    $filtro .= ' AND A.nrPedido = ' . $db->clean($nrPedido);
}

if (strlen($nrAnoPedido) > 0) {
    $filtro .= ' AND year(A.dtDataPedido) = ' . $db->clean($nrAnoPedido);
}

if (strlen($nmCliente) > 0) {
    $filtro .= ' AND B.nmCliente like ' . $db->clean('%' . $nmCliente . '%');
}

//Busca o total de registros da consulta não paginada
//$qrTotal = "SELECT COUNT(idConteudo) as total_registros FROM tb_conteudo WHERE idTipoConteudo NOT IN (".$lista.")".$filtro;
$qrTotal = "
			SELECT 		COUNT(A.idPedido) as total_registros
					
			FROM 		tb_pedido A
			LEFT JOIN	tb_cliente B ON (A.idCliente = B.idCliente)
			
			WHERE 		A.idTipoPedido in (2,3)	
			" . $filtro . " 
		";

$total_registros = $db->query($qrTotal);
$total_registros = $total_registros[0]["total_registros"];
?>

<?php
//QUERY PAGINADA
//Exemplo: "SELECT * FROM nome_da_tabela LIMIT $inicio,$limite"

$qryCont = "
			SELECT 		A.*,
						B.*,
						year(A.dtDataPedido) as nrAnoPedido
					
			FROM 		tb_pedido A
			LEFT JOIN	tb_cliente B ON (A.idCliente = B.idCliente)
			
			WHERE 		A.idTipoPedido > 1	
			" . $filtro . " 
			ORDER BY 	A.nrPedido DESC, year(A.dtDataPedido) DESC
			LIMIT 		" . $inicio . "," . $limite . "		
		";

$qryPaginada = $db->query($qryCont);
?>
<form name="formGeral" id="formGeral" action="menu-pedidos" method="post">
    <?php if ($geralConfig[0]["inOrcamento"] == 1 && $geralConfig[0]["inPagSeguro"] == 0) { ?>
        <h1>Manutenção de Orçamentos</h1>
    <?php } else if ($geralConfig[0]["inOrcamento"] == 1 && $geralConfig[0]["inPagSeguro"] == 1) { ?>
        <h1>Manutenção de Pedidos de Compra</h1>
    <?php } ?>
    <br/>
    <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
    <div align="left">
        <table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td width="10%">Filtrar por Nome do Cliente</td>
                <td width="90%"><input name="nmCliente" id="nmCliente" size="30" maxlength="200" style="top:auto" value="<?php echo $nmCliente; ?>"/></td>
            </tr>
            <tr>
                <td width="10%">Filtrar por Código do Pedido</td>
                <td>
                    <input name="nrPedido" id="nrPedido" size="10" maxlength="10" style="top:auto" value="<?php echo $nrPedido; ?>"/>/
                    <input name="nrAnoPedido" id="nrAnoPedido" size="4" maxlength="4" style="top:auto" value="<?php echo $nrAnoPedido; ?>"/>-
                    <input name="idPedido" id="idPedido" size="10" maxlength="10" style="top:auto" value="<?php echo $idPedido; ?>"/>
                </td>
            </tr>
        </table>
        <button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
        <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href = 'cad-cliente?pedido';">Cadastrar Novo</button>
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
        //NAVEGAÇÃO DO PAGINADOR
        //Calcula o total de páginas
        $total_paginas = ceil($total_registros / $limite);

        //Define a página de direcionamento dos links
        //Seta um filtro vazio
        $filtro2 = '';

        //Se está definido o tipo, adiciona consulta do ano ao filtro
//                            if (strlen($idCategoriaPai) > 0){
//                                $filtro2 = $filtro2 .''. $filtro2 = 'idCategoriaPai='.$idCategoriaPai;
//                            } 
        //Nome da página 
        $stringPagina = '?' . $filtro2;

        //Chama a função que monta a exibição do paginador
        navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
        ?>
    </div>
    <fieldset>
        <table width="100%" border="0" align="center" class="tbLista">
            <tr class="tbTitulo">
                <td width="6%" colspan="2" align="center">Op&ccedil;&otilde;es</td>
                <td width="8%" align="ceter">C&oacute;digo</td>
                <td width="16%" align="left">Tipo / Situação</td>
                <td width="10%" align="left">Data</td>
                <td width="30%" align="left">Nome</td>
                <td width="20%" align="left">E-mail</td>
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
                        -
                    </td>
                    <td align="center" width="3%">
                        <img src="<?php echo $url_raiz; ?>admin/img/editar2.png" 
                             onclick="javascript: location.href = 'cad-pedido?idPedido=<?php echo $qryPaginada[$i]["idPedido"]; ?>';" 
                             onmouseover="javascript: this.style.cursor = 'pointer';"
                             title="Editar"
                             alt="Editar"/>
                    </td>
                    <td width="8%" align="center">
                        <?php echo $pedido = $qryPaginada[$i]["nrPedido"] . '/' . $qryPaginada[$i]["nrAnoPedido"] . '-' . $qryPaginada[$i]["idPedido"]; ?> 
                    </td>
                    <td width="16%" align="center">
                        <?php
                        switch ($qryPaginada[$i]["idTipoPedido"]) {
                            case '1':
                                echo 'Orçamento Aberto';
                                break;
                            case '2':
                                echo 'Orçamento Solicitado';
                                break;
                            case '3':
                                echo 'Compra PagSeguro';
                                break;
                            case '4':
                                echo 'Pedido Finalizado';
                                break;
                            case '5':
                                echo 'Pedido Cancelado';
                                break;
                        }
                        ?>
                    </td>
                    <td width="10%" align="left"><?php echo date('d/m/Y - H:i:s', strtotime($qryPaginada[$i]["dtDataPedido"])); ?></td>
                    <td width="30%" align="left"><?php echo $qryPaginada[$i]["nmCliente"]; ?></td>
                    <td width="20%" align="left"><?php echo $qryPaginada[$i]["nmEmailCliente"]; ?></td>
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
        //NAVEGAÇÃO DO PAGINADOR
        //Calcula o total de páginas
        $total_paginas = ceil($total_registros / $limite);

        //Define a página de direcionamento dos links
        //Seta um filtro vazio
        $filtro2 = '';

        //Se está definido o tipo, adiciona consulta do ano ao filtro
//                            if (strlen($idCategoriaPai) > 0){
//                                $filtro2 = $filtro2 .''. $filtro2 = 'idCategoriaPai='.$idCategoriaPai;
//                            } 
        //Nome da página 
        $stringPagina = '?' . $filtro2;

        //Chama a função que monta a exibição do paginador
        navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
        ?>
    </div>

<?php } ?>