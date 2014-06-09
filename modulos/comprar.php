<h2 class="title-section">
    <span class="title-section__name">
        Store

    </span>
    <a class="title-section__more" href="javascript:history.back(-1);">
        Voltar
    </a>
</h2>
<article class="post">
    <?php
//echo var_dump(session_id());
    $qry = "
			SELECT 		A.*,
						B.*,
						C.nmTituloConteudo AS nmProduto,
						year(A.dtDataPedido) as nrAnoPedido
					
			FROM 		tb_pedido A
			LEFT JOIN	tb_pedido_produto B ON (A.idPedido = B.idPedido)
			LEFT JOIN	tb_conteudo C ON (B.idProduto = C.idConteudo)
			
			
			WHERE 		A.idSessao = '" . session_id() . "' 
			
			AND 		A.idTipoPedido = 1	
	";
    //AND			C.idTipoConteudo = 3
    $qryPedido = $db->query($qry);

    //new dbug($_SESSION["ID_SESSAO"]);
    //new dbug($GLOBAL["ID_SESSAO"]);
    ?>
    <!--- Inicio tabela conteudo --->
    <table width="100%" border="0" cellspacing="10" cellpadding="0" class="tabelaConteudo" style="border: none;">
     <!--   <tr>
            <td class="FonteTitulo">
        <?php
        // if ($geralConfig[0]["inPagSeguro"] == 1) {
        //echo 'COMPRAR';
        // } else {
        // echo 'ORÇAMENTO';
        // }
        ?>
            </td>
        </tr> -->
        <tr>
            <td align="left" valign="top">

                <br/>    
                <!-- FORMULÁRIO -->
                <?php
                if ($geralConfig[0]["inPagSeguro"] == 1) {
                    $destino = $geralConfig[0]["nmLinkPagSeguro"];
                    $target = 'target="pagseguro"';
                    $qryPS = $db->query("SELECT nmEmailPagSeguro FROM tb_pag_seguro_config LIMIT 1");
                } else {
                    $destino = 'controller/compra-controller';
                    $target = '';
                }
                $pedido = $qryPedido[0]["nrPedido"] . '/' . $qryPedido[0]["nrAnoPedido"] . '-' . $qryPedido[0]["idPedido"];
                ?> 

                <form <?php echo $target; ?> method="post" action="<?php echo $destino; ?>">

                    <?php if ($geralConfig[0]["inPagSeguro"] == 1) { //Se o cliente estï¿½ usando PagSeguro  ?>

                        <!-- Informar aqui o seu e-mail que deverï¿½ estar cadastrado e verificado junto ao PagSeguro. -->
                        <input type="hidden" name="email_cobranca" value="<?php echo $qryPS[0]["nmEmailPagSeguro"]; ?>">

                        <!-- Formato da moeda a qual serï¿½ utilizada pelo PagSeguro. 
                        Atualmente, o ï¿½nico valor aceito ï¿½ BRL (Brasil - Real). -->
                        <input type="hidden" name="moeda" value="BRL">

                        <!-- Informar o tipo de POST que estï¿½ sendo enviado ao PagSeguro. 
                    (CBR para usar o carrinho PagSeguro e CP para usar seu prï¿½prio carrinho). 
                    Para carrinho prï¿½prio use "CP" (que ï¿½ o padrï¿½o) -->
                        <input type="hidden" name="tipo" value="CP">


                        <!-- Cï¿½digo da Transaï¿½ï¿½o -->
                        <!-- Nï¿½mero do pedido / Cï¿½digo identificador para seu website.
                            Este campo ï¿½ livre e poderï¿½ ser utilizado para identificar um cliente (cliente ID), 
                            ou como o nï¿½mero do pedido ou qualquer outra referï¿½ncia que vocï¿½ queira usar.
                            Obs.: Vocï¿½ nï¿½o precisa repetir este campo para cada item do carrinho, basta incluï¿½-lo 
                            apenas uma vez.
                        -->
                        <input type="hidden" name="ref_transacao" value="<?php echo $pedido; ?>">
                        <!-- URL de Retorno para o PAG SEGURO 
                             http://ugagogo.no-ip.org/pacote3/controller/orcamentoController.php?acao=concluirPS
                        
                        PARï¿½METRO
                            id_pagseguro
                        

                      <input type="hidden" name="acao" value="concluirPS">-->
                    <?php } else { ?>
                        <input type="hidden" name="acao" value="concluir">
                    <?php } ?>
                    <input type="hidden" name="idPedido" value="<?php echo $qryPedido[0]["idPedido"]; ?>">
                    <input type="hidden" name="nrTotalItens" value="<?php echo count($qryPedido); ?>">

                    <table width="100%" border="0" cellpadding="4" cellspacing="1" class="tabelaOrcamento">
                        <tr>
                            <td width="10%" colspan="2" align="center" class="tituloTabelaOrcamento">Op&ccedil;&otilde;es</td>
                            <td width="30%" class="tituloTabelaOrcamento" align="center">Descri&ccedil;&atilde;o</td>
                            <td width="10%" class="tituloTabelaOrcamento" align="center">Un.</td>
                            <td width="10%" class="tituloTabelaOrcamento" align="center">Qtd.</td>
                            <td width="15%" class="tituloTabelaOrcamento" align="center">Valor</td>
                            <td width="15%" class="tituloTabelaOrcamento" align="center">Total</td>	
                        </tr>
                        <?php if ($qryPedido[0]["idPedido"] == 0) { //Se nï¿½o existem produtos aguardando envio / compra na tabela de pedido ?>
                            <tr>
                                <td colspan="7" align="center" class="linkCategoriaTitulo">Nenhum item adicionado ao seu carrinho.</td>
                            </tr>

                        <?php } else { //Se existem produtos no carrinho, exibe  ?>

                            <?php
                            $coluna = 1;
                            $totalFinal = 0;
                            for ($i = 0; $i < count($qryPedido); $i++) {

                                if ($coluna % 2 == 0) {
                                    $classeTabela = 'conteudoTabela';
                                } else {
                                    $classeTabela = 'conteudoTabelaAlt';
                                }
                                ?>

                                <tr class="<?php echo $classeTabela; ?>">

                                    <td width="5%" align="center">
                                        <img src="<?php echo $url_raiz; ?>img/del.png" border="0" align="absbottom" width="20" height="20" alt="Excluir" class="imgBotao" title="Excluir"
                                             onclick="excluirOrcamento('<?php echo $url_raiz; ?>controller/compra-controller?id=<?php echo $qryPedido[$i]["idProduto"]; ?>&pedido=<?php echo $qryPedido[$i]["idPedido"]; ?>&acao=Excluir');"
                                             />
                                    </td>
                                    <td width="5%" align="center">
                                        <img src="<?php echo $url_raiz; ?>img/iconeAtualizar.png" border="0" align="absbottom" width="20" height="20" alt="Atualizar" title="Atualizar" class="imgBotao"
                                             onclick="enviaAlteracaoExternoCompra('<?php echo $qryPedido[$i]["idProduto"]; ?>', '<?php echo $qryPedido[$i]["idPedido"]; ?>', '<?php echo $coluna; ?>', '<?php echo $url_raiz; ?>', '');"
                                             />
                                    </td>
                                    <td width="30%" align="center">
                                        <!-- Nï¿½mero do primeiro produto do carrinho. Nï¿½mero ï¿½nico que 
                                        identifica o produto em sua loja (nï¿½o pode se repetir). -->
                                        <input type="hidden" name="item_id_<?php echo $coluna; ?>" value="<?php echo $qryPedido[$i]["idProduto"]; ?>">

                                        <!-- Descri&ccedil;&atilde;o do primeiro produto no carrinho. T&iacute;tulo do Produto. -->
                                        <input type="hidden" name="item_descr_<?php echo $coluna; ?>" value="<?php echo $qryPedido[$i]["nmProduto"]; ?>" />
                                        <?php echo $qryPedido[$i]["nmProduto"]; ?>

                                        <?php if ($geralConfig[0]["inPagSeguro"] == 1 && $geralConfig[0]["inFreteFixo"] == 1) { ?>
                                            <!-- Valor do frete da mercadoria sem vï¿½rgulas ou pontos.
                                            Para um frete de R$ 2,00 vocï¿½ deverï¿½ informar 200 (somente nï¿½meros). -->
                                            <input type="hidden" name="item_frete_<?php echo $coluna; ?>" value="<?php echo $qryPedido[$i]["nrValorFrete"]; ?>">
                                            <input type="hidden" name="item_peso_<?php echo $coluna; ?>" value="0">

                                        <?php } else if ($geralConfig[0]["inPagSeguro"] == 1 && $geralConfig[0]["inFreteFixo"] == 0) { ?>
                                            <input type="hidden" name="item_frete_<?php echo $coluna; ?>" value="0">
                                            <!-- Informe em gramas, o peso total dos produtos ï¿½ venda. 
                                            Para 1kg informe 1000 e para 30g informe 30. --> 
                                            <input type="hidden" name="item_peso_<?php echo $coluna; ?>" value="<?php echo $qryPedido[$i]["nrPeso"]; ?>">

                                        <?php } ?>

                                    </td>
                                    <td width="10%" align="center"><?php echo $qryPedido[$i]["nmUnidade"]; ?></td>
                                    <td width="10%" align="center">

                                        <!-- Quantidade do primeiro produto adicionado ao carrinho. -->
                                        <input type="text" name="item_quant_<?php echo $coluna; ?>" id="item_quant_<?php echo $coluna; ?>" value="<?php echo $qryPedido[$i]["nrQuantidade"]; ?>" size="2" maxlength="10">

                                    </td>
                                    <td width="15%" align="center">
                                        <!-- Valor do primeiro produto em seu carrinho, sem vï¿½rgulas ou pontos. 
                                            Para um produto que custa R$ 1,00 vocï¿½ deverï¿½ informar 100 (somente nï¿½meros). -->
                                        <input type="hidden" name="item_valor_<?php echo $coluna; ?>" id="item_valor_<?php echo $coluna; ?>" value="<?php echo valorMoedaIngles(valorMoedaIngles(valorMoedaBR($qryPedido[$i]["valor"]))); ?>">
                                        <?php
                                        if ($geralConfig[0]["inPagSeguro"] == 1) {
                                            echo valorMoedaBR($qryPedido[$i]["valor"]);
                                        } else {
                                            echo '-';
                                        }
                                        ?>


                                    </td>
                                    <td width="15%" align="center">
                                        <?php
                                        if ($geralConfig[0]["inPagSeguro"] == 1) {
                                            $total = ($qryPedido[$i]["valor"] * $qryPedido[$i]["nrQuantidade"]);
                                            echo valorMoedaBR($total);
                                            $totalFinal = $totalFinal + $total;
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>	
                                </tr>
                                <?php
                                $coluna++;
                            } //Fim do loop
                            ?>

                        <?php } //Fim do IF  ?>
                    </table>
                    <?php if ($geralConfig[0]["inPagSeguro"] == 1) { ?>
                        <table width="100%" border="0" cellpadding="4" cellspacing="1">
                            <tr>
                                <td colspan="7" align="center" class="linkCategoriaTitulo"><h1 class="separador"></h1></td>
                            </tr>
                            <tr>
                                <td colspan="7" align="right" class="linkCategoriaTitulo">Valor Total: R$ <?php echo valorMoedaBR($totalFinal); ?></td>
                            </tr>
                        </table>
                        <br/>
                    <?php } ?>
                    <br/>


                    <div align="center">
                        <button class="rounded" type="button" name="btMais" id="btMais" onclick="javascript: location.href = 'store';">
                            <span>Adicionar Produtos</span>
                        </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="rounded" type="submit" name="submit" id="submit" onclick="return validaFormularioVazio(this.form);">
                            <span>Finalizar compra</span>
                        </button>
                    </div>
                    <br/>
                    <br/>
                    <div align="center">
                        <img alt="Logotipos de meios de pagamento do PagSeguro" src="<?php $url_raiz; ?>img/todos_animado_550_50.gif" title="Este site aceita pagamentos com Visa, MasterCard, Diners, American Express, Hipercard, Aura, Bradesco, Itaú, Banco do Brasil, Banrisul, Oi Paggo, saldo em conta PagSeguro e boleto." />
                    </div>                
                    <br/>
                    <h1 class="separador">DADOS DO CLIENTE</h1>

                    <table width="80%" border="0" cellpadding="1" cellspacing="0">
                        <tr>
                            <td colspan="2" align="left" class="formLabel">Nome Completo<span class="destaqueBold">*</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left"><input type="text" name="cliente_nome" id="cliente_nome" maxlength="200" size="50" style="top:auto"/></td>
                        </tr>
                        <tr>
                            <td align="left" class="formLabel">Logradouro<span class="destaqueBold">*</span></td>
                            <td class="formLabel" align="left">N&uacute;mero<span class="destaqueBold">*</span></td>
                        </tr>
                        <tr>
                            <td align="left"><input type="text" name="cliente_end" id="cliente_end" maxlength="200" size="50" style="top:auto"/></td>
                            <td align="left"><input type="text" name="cliente_num" id="cliente_num" maxlength="10" size="21" style="top:auto"/></td>
                        </tr>
                        <tr> 
                            <td colspan="2" align="left" class="formLabel">Complemento</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left"><input type="text" name="cliente_compl" id="cliente_compl"  maxlength="200" size="50"/></td>
                        </tr>
                        <tr>
                            <td align="left" class="formLabel">Bairro<span class="destaqueBold">*</span></td>
                            <td class="formLabel" align="left">CEP<span class="destaqueBold">*</span></td>
                        </tr>
                        <tr>
                            <td align="left">
                                <input type="text" name="cliente_bairro" id="cliente_bairro" maxlength="200" size="50" style="top:auto"/>
                            </td>
                            <td align="left">
                                <input type="text" name="cliente_cep" id="cliente_cep" onkeypress="MascaraCep(this);" maxlength="10" size="21" onblur="limpaStringNumerica(this, 'cliente_cep');" style="top:auto"/>
                                <!-- <input type="hidden" name="cliente_cep" id="cliente_cep" value=""  maxlength="8" /> -->
                            </td>
                        </tr>
                        <tr>
                            <td align="left" class="formLabel">Cidade<span class="destaqueBold">*</span></td>
                            <td align="left" class="formLabel" >Estado<span class="destaqueBold">*</span></td>
                        </tr>
                        <tr>
                            <td align="left">
                                <input type="text" name="cliente_cidade" id="cliente_cidade" maxlength="200" size="50" style="top:auto"/>
                            </td>
                            <td align="left">
                                <select name="cliente_uf" id="cliente_uf" style="top:auto">
                                    <option value="">Selecione</option>
                                    <?php for ($i = 0; $i < count($estados); $i++) { ?>
                                        <option value="<?php echo $estados[$i][0]; ?>"><?php echo $estados[$i][1]; ?></option>
                                    <?php } ?>
                                </select>

                                <input type="hidden" name="cliente_pais" id="cliente_pais" value="BRA" /></td>
                        </tr>
                        <tr>
                            <td align="left" class="formLabel">E-mail<span class="destaqueBold">*</span></td>
                            <td class="formLabel" align="left">Telefone<span class="destaqueBold">*</span></td>
                        </tr>
                        <tr>
                            <td align="left"><input type="text" name="cliente_email" id="cliente_email"  maxlength="200" size="50" style="top:auto"/></td>
                            <td align="left">
                                <input name="cliente_telefone" type="text" id="cliente_telefone" style="top:auto" onKeyPress="MascaraTelefone(this);" onblur="trataTelefone(this, 'cliente_ddd', 'cliente_tel');" size="21" maxlength="14"/>
                            </td>
                        </tr>
                        <tr> 
                            <td colspan="2" align="left" class="formLabel">Consultor</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left"><input type="text" name="nmConsultor" id="nmConsultor"  maxlength="200" size="80"/></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left" class="formLabel">Observa&ccedil;&otilde;es / Coment&aacute;rio</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left"><textarea name="mensagem" id="mensagem" cols="60" rows="5"></textarea></td>
                        </tr>
                    </table>
                    <br/>
                    <input type="hidden" name="cliente_ddd" id="cliente_ddd" value="">
                    <input type="hidden" name="cliente_tel" id="cliente_tel" value="">
                    <br/>
                    <div align="center">
                        <button class="rounded" type="button" name="btMais" id="btMais" onclick="javascript: location.href = 'store';">
                            <span>Adicionar Produtos</span>
                        </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="rounded" type="submit" name="submit" id="submit" onclick="return validaFormularioVazio(this.form);">
                            <span>Finalizar compra</span>
                        </button>
                    </div>
                </form>
                <!-- -->
            </td>
        </tr>
    </table> 
</article>
<?php
include 'ddcProgramas.php';
?>