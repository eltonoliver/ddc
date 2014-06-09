<?php
$qry = "SELECT *, year(dtDataPedido) as nrAnoPedido FROM tb_pedido WHERE idPedido = " . $_REQUEST["idPedido"];
$qryPedido = $db->query($qry);
$qry = "
			SELECT 		A.*,B.nmTituloConteudo AS nmProduto					
			FROM 		tb_pedido_produto A
			LEFT JOIN	tb_conteudo B ON (A.idProduto = B.idConteudo)
			WHERE 		A.idPedido = '" . $qryPedido[0]["idPedido"] . "'	
	";
$qryProdutos = $db->query($qry);

if ($qryPedido[0]["idTipoPedido"] == 2) {
    $qryListaProdutos = $db->query("SELECT idConteudo, nmTituloConteudo FROM tb_conteudo WHERE idTipoConteudo=31 AND inPublicar = 1 ORDER BY nmTituloConteudo DESC");
}
?>
<form name="formGeral" id="formGeral" action="<?php echo $url_raiz; ?>controller/orcamento-controller" method="post" enctype="multipart/form-data">

    <?php if ($geralConfig[0]["inOrcamento"] == 1 && $geralConfig[0]["inPagSeguro"] == 0) { ?>
        <h1>Cadastro de <?php echo $nomeOrcamento; ?></h1>
    <?php } else if ($geralConfig[0]["inOrcamento"] == 1 && $geralConfig[0]["inPagSeguro"] == 1) { ?>
        <h1>Cadastro de <?php echo $nomeOrcamento; ?></h1>
    <?php } ?>
    <br/>

    <?php if (isset($_GET["atualizado"])) { ?>
        <div class="msgBox">
            <p>Dados atualizados com sucesso!</p>
        </div>
    <?php } else if (isset($_GET["erro"])) { ?>
        <div class="msgBox">
            <p><img src="<?php echo $url_raiz; ?>/admin/img/alerta.gif" border="0"align="absbottom"/> Ocorreu um erro! Tente novamente ou contate o suporte.</p>
        </div>
    <?php } ?>

    <h2 class="separadador">Dados do Pedido "<?php echo $pedido = $qryPedido[0]["nrPedido"] . '/' . $qryPedido[0]["nrAnoPedido"] . '-' . $qryPedido[0]["idPedido"]; ?>"</h2>
    <div align="left">
        <?php if (!isset($_REQUEST["idPedido"])) { ?>
            <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
        <?php } else { ?>
            <?php if ($qryPedido[0]["idTipoPedido"] == 2 && $qryPedido) { ?>
                <button type="submit" name="btEnviar" id="btEnviar" onClick="return validaFormularioVazio(this.form);">Finalizar</button>
            <?php } ?>
        <?php } ?>
        <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href = 'menu-pedidos';">Voltar</button>
        <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href = 'cad-cliente?pedido';">Cadastrar Novo</button>
    </div>
    <br/>

    <table width="100%" border="0" align="center" class="tabelaForm">
        <tr>
            <td width="15%">C&oacute;digo do Pedido:</td>
            <td width="85%"><?php echo $pedido = $qryPedido[0]["nrPedido"] . '/' . $qryPedido[0]["nrAnoPedido"] . '-' . $qryPedido[0]["idPedido"]; ?></td>
        </tr>
        <tr>
            <td width="15%">Data:</td>
            <td width="85%"><?php echo date('d/m/Y - H:i:s', strtotime($qryPedido[0]["dtDataPedido"])); ?></td>
        </tr>
        <tr>
            <td width="15%">Cliente:</td>
            <td width="85%">
                <?php
                $qryCliente = $db->query("SELECT nmCliente FROM tb_cliente WHERE idCliente = " . $qryPedido[0]["idCliente"]);
                echo $qryCliente[0]["nmCliente"];
                ?>
            </td>
        </tr>
        <tr>
            <td width="15%">Tipo de Pedido:</td>
            <td width="85%">
                <?php
                switch ($qryPedido[0]["idTipoPedido"]) {
                    case '1':
                        echo 'Or&ccedil;amento Aberto';
                        break;
                    case '2':
                        echo 'Or&ccedil;amento Solicitado';
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
        </tr>
        <tr>
            <td width="15%">Consultor:</td>
            <td width="85%">
                <?php
                echo $qryPedido[0]["nmConsultor"];
                ?>
            </td>
        </tr>
        <tr>
            <td width="15%">Observa&ccedil;&otilde;es:<br/><span class="destaque">(<span id="contador">0/1000</span>)</span></td>
            <td width="85%">
                <textarea name="nmObservacoesPedido" cols="60" rows="5" id="nmObservacoesPedido" onKeyUp="limitadorCampo(this, 1000, 'contador', 'nmObservacoesPedido');"  wrap="virtual" onBlur="concatenaCampos(this, 'nmObservacoesPedido');"><?php echo $qryPedido[0]["nmObservacoesPedido"]; ?></textarea>
            </td>
        </tr>
        <?php if ($qryPedido[0]["idTipoPedido"] == 2) { ?>
            <tr>
                <td width="15%">Adicionar Produtos?</td>
                <td width="85%" valign="middle">
                    <select name="idNovoProduto" id="idNovoProduto">
                        <option value="">Selecione</option>
                        <?php for ($i = 0; $i < count($qryListaProdutos); $i++) { ?>
                            <option value="<?php echo $qryListaProdutos[$i]["idConteudo"]; ?>"><?php echo $qryListaProdutos[$i]["nmTituloConteudo"]; ?></option>
                        <?php } ?>
                    </select> 
                    <img src="<?php echo $url_raiz; ?>/admin/img/iconeAdicao.png" border="0" align="top" width="25" height="25" alt="Excluir" class="imgBotao" title="Excluir"
                         onclick="adicionarOrcamento('<?php echo $url_raiz; ?>controller/orcamento-controller', '<?php echo $qryPedido[0]["idPedido"]; ?>');"
                         />

                </td>
            </tr>
        </table>
    <?php } ?>

    <?php if ($geralConfig[0]["inPagSeguro"] == 1) { ?>
        <br/>
        <h2 class="separadador">PagSeguro</h2>
        <table width="100%" border="0" align="center" class="tabelaForm">
            <tr>
                <td width="15%">Código PagSeguro:</td>
                <td width="85%"><?php echo $qryPedido[0]["idPagSeguro"]; ?></td>
            </tr>
            <tr>
                <td width="15%">Data Compra:</td>
                <td width="85%"><?php
                    if ($qryPedido[0]["dtPagSeguro"]) {
                        echo date('d/m/Y - H:i:s', strtotime($qryPedido[0]["dtPagSeguro"]));
                    }
                    ?></td>
            </tr>
        </table>
    <?php } ?>

    <br/>
    <h2 class="separadador">Produtos</h2>
    <fieldset>
        <table width="100%" border="0" align="center" class="tbLista">
            <tr class="tbTitulo">
                <td width="6%" colspan="2" align="center">Op&ccedil;&otilde;es</td>
                <td align="center" width="54%">Produto</td>
                <td align="center" width="10%">Unidade</td>
                <td align="center" width="10%">Quantidade</td>
                <td align="center" width="10%">Valor</td>
                <td align="center" width="10%">Total</td>
            </tr>
            <?php if (!$qryProdutos) { //Se n�o existem produtos aguardando envio / compra na tabela de pedido  ?>
                <tr>
                    <td colspan="7" align="center" class="linkCategoriaTitulo">Nenhum item adicionado.</td>
                </tr>

            <?php } else { //Se existem produtos no carrinho, exibe ?>
                <?php
                $coluna = 1;
                $totalFinal = 0;
                for ($i = 0; $i < count($qryProdutos); $i++) {

                    if ($coluna % 2 == 0) {
                        $classe = 'tbNormal';
                    } else {
                        $classe = 'tbNormalAlt';
                    }
                    ?>
                    <tr class="<?php echo $classe; ?>">
                        <td align="center" width="3%">
                            <?php if ($qryPedido[0]["idTipoPedido"] == 2) { ?>
                                <img src="<?php echo $url_raiz; ?>/admin/img/del.png" border="0" align="absbottom" alt="Excluir" class="imgBotao" title="Excluir"
                                     onclick="excluirOrcamento('<?php echo $url_raiz; ?>controller/orcamento-controller?id=<?php echo $qryProdutos[$i]["idProduto"]; ?>&retorno=admin&pedido=<?php echo $qryPedido[0]["idPedido"]; ?>&acao=Excluir');"
                                     />
                                 <?php } else { ?>
                                -
                            <?php } ?>
                        </td>
                        <td align="center" width="3%">
                            <?php if ($qryPedido[0]["idTipoPedido"] == 2) { ?>
                                <img src="<?php echo $url_raiz; ?>/admin/img/iconeAtualizar.png" border="0" align="absbottom" alt="Atualizar" title="Atualizar" class="imgBotao"
                                     onclick="enviaAlteracao('<?php echo $qryProdutos[$i]["idProduto"]; ?>', '<?php echo $qryPedido[0]["idPedido"]; ?>', '<?php echo $coluna; ?>', '<?php echo $url_raiz; ?>', 'admin');"
                                     />
                                 <?php } else { ?>
                                -
                            <?php } ?>
                        </td>
                        <td align="center" width="54%">
                            <input type="hidden" name="item_id_<?php echo $coluna; ?>" value="<?php echo $qryProdutos[$i]["idProduto"]; ?>">
                            <?php echo $qryProdutos[$i]["nmProduto"]; ?>
                        </td>
                        <td align="center" width="10%"><?php echo $qryProdutos[$i]["nmUnidade"]; ?></td>
                        <td align="center" width="10%">

                            <?php if ($qryPedido[0]["idTipoPedido"] == 2) { ?>

                                <input type="text" name="item_quant_<?php echo $coluna; ?>" id="item_quant_<?php echo $coluna; ?>" value="<?php echo $qryProdutos[$i]["nrQuantidade"]; ?>" size="2" maxlength="10"></td>
                            <?php
                        } else {
                            echo $qryProdutos[$i]["nrQuantidade"];
                        }
                        ?>

                        <td align="center" width="10%">

                            <?php
                            $total = ($qryProdutos[$i]["nrValor"] * $qryProdutos[$i]["nrQuantidade"]);

                            if ($qryPedido[0]["idTipoPedido"] == 2) {
                                ?>

                                <input type="text" name="valor_<?php echo $coluna; ?>" id="valor_<?php echo $coluna; ?>" onKeyPress="return(FormataReais(this, '.', ',', event))" onBlur="limpaStringNumerica(this, 'item_valor_<?php echo $coluna; ?>');" value="<?php echo valorMoedaBR($qryProdutos[$i]["nrValor"]); ?>">

                                <input type="hidden" name="item_valor_<?php echo $coluna; ?>" id="item_valor_<?php echo $coluna; ?>" value="<?php echo valorMoedaBR($qryProdutos[$i]["nrValor"]); ?>">
                                <?php
                            } else {
                                echo valorMoedaBR($qryProdutos[$i]["nrValor"]);
                            }
                            ?>

                        </td>
                        <td align="center" width="10%">

                            <?php
                            echo 'R$ ' . valorMoedaBR($total);
                            $totalFinal = $totalFinal + $total;
                            ?>

                        </td>
                    </tr>
                    <?php
                    $coluna++;
                }
                ?>
            <?php } //Fim do IF   ?>
            <tr>
                <td colspan="7" align="left"><h1 class="separadadorAzul"></h1></td>
            </tr>
            <tr class="tbTituloPreto">
                <td align="center" valign="top">&nbsp;</td>
                <td align="center" valign="top">&nbsp;</td>
                <td align="center" valign="top">&nbsp;</td>
                <td align="center" valign="top">&nbsp;</td>
                <td align="center" valign="top">&nbsp;</td>
                <td align="center" valign="top">&nbsp;</td>
                <td align="center" valign="top">R$ <?php echo valorMoedaBR($totalFinal); ?></td>
            </tr>
        </table>
    </fieldset>

    <br/>
    <div align="left">
        <?php if (!isset($_REQUEST["idPedido"])) { ?>
            <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Cadastrar</button>
            <input type="hidden" id="acao" name="acao" value="Cadastrar" />
        <?php } else { ?>
            <?php if ($qryPedido[0]["idTipoPedido"] == 2 && $qryPedido) { ?>
                <button type="submit" name="btEnviar2" id="btEnviar2" onClick="return validaFormularioVazio(this.form);">Finalizar</button>
                <input type="hidden" id="acao" name="acao" value="Finalizar" />
            <?php } ?>
            <input type="hidden" id="idPedido" name="idPedido" value="<?php echo $qryPedido[0]["idPedido"]; ?>" /> 
        <?php } ?>
        <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href = 'menu-pedidos';">Voltar</button>
        <button type="button" name="btNovo2" id="btNovo2" onClick="javascript: location.href = 'cad-cliente?pedido';">Cadastrar Novo</button>
    </div>
    <br/>
</form>
