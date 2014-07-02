<?php
//PREPARA�AO DO PAGINADOR
//Define o total de registros por p�gina
$limite = 30;
//Pega o n�mero da p�gina que vem pela URL
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
if (isset($_REQUEST["idPromocao"])) {
    $idPromocao = $_REQUEST["idPromocao"];
}
if (isset($_REQUEST["nmNome"])) {
    $nmNome = $_REQUEST["nmNome"];
}
if (strlen($nmNome) > 0) {
    $filtro .= ' AND nmNome like ' . $db->clean('%' . $nmNome . '%');
}
//Busca o total de registros da consulta nao paginada
$qrTotal = "SELECT COUNT(idInscricao) as total_registros FROM tb_inscricao WHERE idPromocao=$idPromocao" . $filtro;
$total_registros = $db->query($qrTotal);
$total_registros = $total_registros[0]["total_registros"];
//QUERY PAGINADA
$qryCont = "
SELECT 		*
FROM 		tb_inscricao
WHERE 		idPromocao=$idPromocao
" . $filtro . "
ORDER BY 	idInscricao 
LIMIT 		" . $inicio . "," . $limite . "
";
$qryPaginada = $db->query($qryCont);
$qryPromocao = $db->query("select idCategoria,nmTituloConteudo from tb_conteudo where idConteudo=" . $idPromocao);
?>

<form name="formGeral" id="formGeral" action="inscritos-promocao"
      method="post">
    <h1>Inscrições da Promoção "<?php echo $qryPromocao[0]['nmTituloConteudo']; ?>"</h1>
    <br />
    <?php include('sisMensagem.php'); ?>
    <h2 class="separadador">
        &Uacute;ltimos Cadastrados (
        <?php echo $total_registros; ?>
        )
    </h2>
    <div align="left">
        <table class="tabelaForm" width="100%" border="0" align="left"
               cellpadding="0" cellspacing="0">
            <tr>
                <td width="7%">Filtrar por Nome</td>
                <td width="93%"><input name="nmNome" id="nmNome" size="30"
                                       maxlength="200" style="top: auto" value="<?php echo $nmNome; ?>" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="idPromocao" value="<?php echo $idPromocao; ?>"/>
        <button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
    </div>
    <br />
</form>

<?php if ($total_registros == 0) { //Se a consulta voltou sem nenhum resultado. ?>
    <div class="msgBox">
        <p>
            <img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"
                 align="absbottom" /> Nenhum resultado para a consulta realizada.
        </p>
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
        //Nome da p�gina
        $stringPagina = '?' . $filtro2;
        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
        ?>
    </div>
    <fieldset>
        <table width="100%" border="0" align="center" class="tbLista">
            <tr class="tbTitulo">
                <td colspan="2" align="center">Op&ccedil;&otilde;es</td>
                <td align="ceter">Nome</td>
                <td width="70px" align="left">CPF</td>
                <td width="130px" align="ceter">Data de nascimento</td>
                <td width="30px" align="ceter">Sexo</td>
                <td width="10%" align="left">E-mail</td>
                <td width="90px" align="left">Telefone</td>
                <td width="5%" align="left">Cidade/UF</td>
                <td width="130px" align="left">Data de cadastro</td>
                <td align="left">Objeto</td>
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
                    <td align="center" width="3%"><img
                            src="<?php echo $url_raiz; ?>admin/img/del.png"
                            onclick="javascript: location.href = 'controller/act-inscricao?acao=Excluir&idInscricao=<?php echo $qryPaginada[$i]["idInscricao"]; ?>&idPromocao=<?php echo $qryPaginada[$i]["idPromocao"]; ?>';"
                            onmouseover="javascript: this.style.cursor = 'pointer';"
                            title="Excluir" alt="Excluir" />
                    </td>
                    <!--<td align="center" width="3%"><img src="<?php echo $url_raiz; ?>admin/img/iconAmpliar.png" onclick="javascript: location.href = 'ver-inscricao?idInscricao=<?php echo $qryPaginada[$i]["idInscricao"]; ?>';" title="Ver detalhes" alt="Ver detalhes" style="cursor: pointer;"></td>-->
                    <td align="center" width="4%"><?php if ($qryPaginada[$i]["selecionada"] == 1) { ?>
                            <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png"
                                 onclick="javascript: location.href = 'controller/act-inscricao?acao=Desativar&idInscricao=<?php echo $qryPaginada[$i]["idInscricao"]; ?>&idPromocao=<?php echo $qryPaginada[$i]["idPromocao"]; ?>';"
                                 onmouseover="javascript: this.style.cursor = 'pointer';"
                                 title="Ativado. Clique para desativar"
                                 alt="Ativado. Clique para desativar" /> <?php } else { ?> <img
                                 src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png"
                                 onclick="javascript: location.href = 'controller/act-inscricao?acao=Ativar&idInscricao=<?php echo $qryPaginada[$i]["idInscricao"]; ?>&idPromocao=<?php echo $qryPaginada[$i]["idPromocao"]; ?>';"
                                 onmouseover="javascript: this.style.cursor = 'pointer';"
                                 title="Desativado. Clique para ativar."
                                 alt="Desativado. Clique para ativar" /> <?php } ?>
                    </td>
                    <td align="left"><?php echo $qryPaginada[$i]["nmNome"]; ?></td>
                    <td width="70px" align="left"><?php echo $qryPaginada[$i]["nrCPF"]; ?></td>
                    <td width="130px" align="left"><?php echo dataBarrasBR($qryPaginada[$i]["dataNascimento"]); ?></td>
                    <td width="30px" align="left"><?php echo $qryPaginada[$i]["sexo"]; ?></td>
                    <td width="10%" align="left"><?php echo $qryPaginada[$i]["nmEmail"]; ?></td>
                    <td width="90px" align="left"><?php echo $qryPaginada[$i]["telefone"]; ?></td>
                    <td width="5%" align="left"><?php echo $qryPaginada[0]['nmCidade'] . "/" . $qryPaginada[0]['nmUf']; ?></td>
                    <td width="130px" align="left"><?php echo dataTimeBarrasBR($qryPaginada[$i]["dtDataCadastro"]); ?></td>
                    <td align="left">
                        <?php
                        if ($qryPromocao[0]['idCategoria'] == 72) {
                            echo $qryPaginada [0]['nmObjeto'];
                        } elseif ($qryPromocao[0]['idCategoria'] == 73) {
                            ?>
                            <img src="<?php echo $url_raiz; ?>/arquivos/enviados/promocoes/<?php echo $qryPaginada [0]['nmObjeto']; ?>"/>
                            <?php
                        } elseif ($qryPromocao[0]['idCategoria'] == 74) {
                            $qryPaginada [0]['nmObjeto'] = str_replace("youtu.be", "youtube.com/v", $qryPaginada [0]['nmObjeto']);
                            ?>
                            <iframe class="fabricatv-video" width="306" height="256" src="<?php echo $qryPaginada [0]['nmObjeto']; ?>" frameborder="0" allowfullscreen></iframe>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                $coluna++;
            }
            ?>
        </table>
    </fieldset>
    <br />
    <div align="center">
        <?php
        //NAVEGA�AO DO PAGINADOR
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

        //Chama a fun�ao que monta a exibi�ao do paginador
        navegacaoPaginadorSimples($total_registros, $total_paginas, $limite, $stringPagina, $pagina);
        ?>
    </div>

<?php } ?>
<br />