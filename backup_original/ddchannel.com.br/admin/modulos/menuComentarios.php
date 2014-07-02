<?php
//PREPARA�AO DO PAGINADOR
//Define o total de registros por p�gina
$limite = 10;

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
if (isset($_REQUEST["nmNome"])) {
    $nmNome = $_REQUEST["nmNome"];
}

if (isset($_REQUEST["idComentario"])) {
    $idComentario = $_REQUEST["idComentario"];
}

if (isset($_REQUEST["inTipo"])) {
    $inTipo = $_REQUEST["inTipo"];
}

if (strlen($inTipo) > 0) {
    $filtro .= ' AND inTipo = ' . $db->clean($inTipo);
}

if (strlen($idComentario) > 0) {
    $filtro .= ' AND idComentario = ' . $db->clean($idComentario);
}

if (strlen($nmNome) > 0) {
    $filtro .= ' AND nmNome like ' . $db->clean('%' . $nmNome . '%');
}

//Busca o total de registros da consulta nao paginada
$qrTotal = "SELECT COUNT(idComentario) as total_registros FROM tb_comentarios WHERE idComentario > 0" . $filtro;
$total_registros = $db->query($qrTotal);
$total_registros = $total_registros[0]["total_registros"];
?>

<?php
//QUERY PAGINADA
//Exemplo: "SELECT * FROM nome_da_tabela LIMIT $inicio,$limite"

$qryCont = "
			SELECT 		*
			FROM 		tb_comentarios
			WHERE 		idComentario > 0
			" . $filtro . " 
			ORDER BY 	idComentario DESC
			LIMIT 		" . $inicio . "," . $limite . "		
		";

$qryPaginada = $db->query($qryCont);
?>

<form name="formGeral" id="formGeral" action="menu-comentarios" method="post">
    <h1>Moderação de Comentários</h1>
    <br/>
    <?php include('sisMensagem.php'); ?>

    <h2 class="separadador">Últimos Cadastrados (<?php echo $total_registros; ?>)</h2>
    <div align="left">
        <table  class="tabelaForm" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td width="7%">Filtrar por Nome</td>
                <td width="93%"><input name="nmNome" id="nmNome" size="30" maxlength="200" style="top:auto" value="<?php echo $nmNome; ?>"/></td>
            </tr>
            <tr>
                <td width="7%">Filtrar por Tipo</td>
                <td>
                    <select name="inTipo" id="inTipo">
                        <option value="">Todos</option>
                        <option value="1" <?php
                        if ($inTipo == 1) {
                            echo 'selected="selected"';
                        }
                        ?>>Comentários sobre Conteúdos</option>
                        <option value="2" <?php
                        if ($inTipo == 2) {
                            echo 'selected="selected"';
                        }
                        ?>>Relatos sobre Bairros</option>
                        <option value="3" <?php
                        if ($inTipo == 3) {
                            echo 'selected="selected"';
                        }
                        ?>>Redes Sociais (Twitter e Facebook)</option>
                    </select>
                </td>
            </tr> 
        </table>
        <button type="submit" name="btEnviar" id="btEnviar">Consultar</button>
    </div>
    <br/>
</form> 

<?php if ($total_registros == 0) { //Se a consulta voltou sem nenhum resultado. ?>
    <div class="msgBox">
        <p><img src="<?php echo $url_raiz; ?>admin/img/alerta.gif" border="0"align="absbottom"/> Nenhum resultado para a consulta realizada.</p>
    </div>

<?php } else { //Se foram encontrados resultados. ?>
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
    <fieldset>
        <table width="100%" border="0" align="center" class="tbLista">
            <tr class="tbTitulo">
                <td colspan="3" align="center">Opções</td>
                <td width="23%" align="ceter">Nome / E-mail</td>
                <td width="15%" align="left">Categoria</td>
                <td width="19%" align="left">Conteúdo Relacionado</td>
                <td width="33%" align="left">Comentário</td>
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
                <script type="text/javascript">
                    function getConteudoCategoria(idCategoria, coluna) {
                        $.getJSON(
                                "<?php echo str_replace("/admin", "", $url_raiz); ?>con-ajax-conteudo/idTipoConteudo/23/idCategoria/" + idCategoria + "/?ajax=1&callback=?",
                                function(data) {
                                    $('#cResposta' + coluna).css('display', 'none');
                                    $('#cLoading' + coluna).css('display', 'inline');
                                    var i = 0;
                                    var resultado = data.retorno;
                                    if (resultado.length > 0) {
                                        var seletor = '<select name="idConteudo' + coluna + '" id="idConteudo' + coluna + '" style="top:auto;">';
                                        var seletor = seletor + '<option value="">[Selecione uma resposta]</option>';
                                        for (i == 0; i < resultado.length; i++) {
                                            var seletor = seletor + '<option value="' + resultado[i].idConteudo + '">' + resultado[i].nmTituloConteudo + '</option>';
                                        }
                                        var seletor = seletor + '</select>';
                                        $('#cResposta' + coluna).html(seletor);
                                        $('#cLoading' + coluna).css('display', 'none');
                                        $('#cResposta' + coluna).css('display', 'inline');
                                        $('#btAtual' + coluna).attr('src', '<?php echo $url_raiz; ?>admin/img/iconeAtualizar.png');
                                    } else {
                                        var seletor = 'Nenhuma resposta para esta categoria.';
                                        var seletor = seletor + '<br/><input type="hidden" name="idConteudo' + coluna + '" id="idConteudo' + coluna + '" value=""/>';
                                        $('#btAtual' + coluna).attr('src', '<?php echo $url_raiz; ?>admin/img/iconeAtualizar.png');

                                        $('#cResposta' + coluna).html(seletor);
                                        $('#cLoading' + coluna).css('display', 'none');
                                        $('#cResposta' + coluna).css('display', 'inline');
                                    }
                                }
                        );

                    }
                    function atualizaComentario(coluna) {

                        $('#btAtual' + coluna).attr('src', '<?php echo $url_raiz; ?>img/ajax-loader.gif');

                        var idCategoria = $('#idCategoria' + coluna).val();
                        var idConteudo = $('#idConteudo' + coluna).val();
                        var idComentario = $('#idComentario' + coluna).val();

                        if (idCategoria.length > 0 && idConteudo.length > 0 && idComentario.length > 0) {

                            $.ajax({
                                type: 'POST',
                                url: '<?php echo $url_raiz; ?>admin/controller/act-comentarios',
                                data: {acao: 'atualizaComentario', idCategoria: idCategoria, idConteudo: idConteudo, idComentario: idComentario}
                                //									,sucess: function(retorno){
                                //										if(!retorno){
                                //											alert('Falha na atualiza��o. Tente novamente.')	;
                                //										} 
                                //									}
                            });
                            alert('Item atualizado.');
                            $('#btAtual' + coluna).attr('src', '<?php echo $url_raiz; ?>admin/img/iconeAtualizar.png');

                        } else {

                            alert('Voc� deve selecionar uma categoria e uma resposta para atualziar o coment�rio.');
                            $('#btAtual' + coluna).attr('src', '<?php echo $url_raiz; ?>admin/img/iconeAtualizar.png');

                        }

                    }

                    function ativarComentario(id, o) {
                        var s = 0;
                        if ($(o).attr('publicar') == 'true') {
                            s = 0;
                            $(o).attr('publicar', 'false');
                            $(o).attr('src', '<?php echo $url_raiz; ?>admin/img/bola_cinza.png');
                        } else {
                            s = 1;
                            $(o).attr('publicar', 'true');
                            $(o).attr('src', '<?php echo $url_raiz; ?>admin/img/bola_verde.png');
                        }

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $url_raiz; ?>admin/controller/act-comentarios',
                            data: {status: s, acao: 'AtivarDesativar', idComentario: id}
                        });
                    }
                </script>				
                <form name="formCom<?php echo $coluna; ?>" id="formCom<?php echo $coluna; ?>" action="controller/act-comentarios" method="post">
                    <tr class="<?php echo $classe; ?>">
                        <td align="center" width="3%">
                            <img src="<?php echo $url_raiz; ?>admin/img/del.png" 
                                 onclick="javascript: location.href = 'controller/act-comentarios?acao=Excluir&idComentario=<?php echo $qryPaginada[$i]["idComentario"]; ?>';" 
                                 onmouseover="javascript: this.style.cursor = 'pointer';"
                                 title="Excluir"
                                 alt="Excluir"/>
                        </td>
                        <td align="center" width="3%">

                            <img src="<?php echo $url_raiz; ?>admin/img/iconeAtualizar.png" 
                                 onClick="atualizaComentario('<?php echo $coluna; ?>');" 
                                 title="Atualizar"
                                 alt="Atualizar" 
                                 class="imgBotao" id="btAtual<?php echo $coluna; ?>" width="30" height="30"/>
                        </td>
                        <td align="center" width="4%">
                            <?php /* ?>                    	<?php if($qryPaginada[$i]["inPublicar"] == 1){ ?>
                              <img src="<?php echo $url_raiz; ?>admin/img/bola_verde.png"
                              onclick="javascript: location.href='controller/act-comentarios?acao=Ocultar&idComentario=<?php echo $qryPaginada[$i]["idComentario"]; ?>';"
                              onmouseover="javascript: this.style.cursor='pointer';"
                              title="Publicado. Clique para ocultar"
                              alt="Publicado. Clique para ocultar"/>

                              <?php } else { ?>
                              <img src="<?php echo $url_raiz; ?>admin/img/bola_cinza.png"
                              onclick="javascript: location.href='controller/act-comentarios?acao=Publicar&idComentario=<?php echo $qryPaginada[$i]["idComentario"]; ?>';"
                              onmouseover="javascript: this.style.cursor='pointer';"
                              title="Oculto. Clique para publicar."
                              alt="Oculto. Clique para publicar"/>

                              <?php } ?>
                              <?php */ ?>                        

                            <img src="<?php echo $url_raiz; ?>admin/img/<?php echo ($qryPaginada[$i]["inPublicar"] ? 'bola_verde.png' : 'bola_cinza.png'); ?>" 
                                 border="0" 
                                 class="linkado" 
                                 onclick="ativarComentario('<?php echo $qryPaginada[$i]["idComentario"]; ?>', this);"
                                 <?php echo ($arq[$i]["inPublicar"] ? 'publicar="true"' : 'publicar="false"'); ?>
                                 />

                        </td>
                        <td align="left">
                            <?php
                            if ($qryPaginada[$i]["idRedeSocial"] == 1) {

                                $linkRede = 'http://www.twitter.com/' . $qryPaginada[$i]["nmUsernameRede"];
                                ?>
                                <a href="<?php echo $linkRede; ?>" target="_blank"><?php echo $qryPaginada[$i]["nmNome"]; ?></a>
                                <?php
                            } else if ($qryPaginada[$i]["idRedeSocial"] == 2) {

                                $linkRede = 'http://www.facebook.com/' . $qryPaginada[$i]["nmUsernameRede"];
                                ?>
                                <a href="<?php echo $linkRede; ?>" target="_blank"><?php echo $qryPaginada[$i]["nmNome"]; ?></a>
                                <?php
                            } else {

                                echo $qryPaginada[$i]["nmNome"];
                            }
                            if (!vazio($qryPaginada[$i]["nmEmail"])) {
                                ?><br/>(<a href="mailto:<?php echo $qryPaginada[$i]["nmEmail"]; ?>" target="_blank"><?php echo $qryPaginada[$i]["nmEmail"]; ?></a>)<?php
                            }
                            ?>
                        </td>
                        <td width="15%" align="left">
                            <?php
                            $strcat = "SELECT * FROM vwtipoconteudocategoria WHERE idTipoConteudo = 23 ORDER BY nmCategoria ASC";
                            $qryCategorias = $db->query($strcat);
                            ?>
                            <select name="idCategoria<?php echo $coluna; ?>" id="idCategoria<?php echo $coluna; ?>" style="top:auto;" onchange="getConteudoCategoria(this.value, '<?php echo $coluna; ?>');">
                                <option value="">[Sem Categoria]</option>
                                <?php
                                if ($qryCategorias) {
                                    foreach ($qryCategorias as $ccat) {
                                        ?>
                                        <option value="<?php echo $ccat["idCategoria"]; ?>" <?php
                                        if ($ccat["idCategoria"] == $qryPaginada[$i]["idCategoria"]): echo 'selected';
                                        endif;
                                        ?>><?php echo $ccat["nmCategoria"]; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                        </td>
                        <td width="19%" align="left">
                            <div id="cLoading<?php echo $coluna; ?>" style="display:none;"><img src="<?php echo $url_raiz; ?>img/ajax-loader.gif" width="18" height="18"/> Consultando...</div>
                            <?php
                            if ($qryPaginada[$i]["idConteudo"] > 0) {
                                $str = "SELECT * FROM tb_conteudo WHERE idTipoConteudo = 11 AND idCategoria = '" . $qryPaginada[$i]["idCategoria"] . "' AND inPublicar = 1 ORDER BY nmTituloConteudo DESC";
                                $qryRespostas = $db->query($str);
                                ?>
                                <div id="cResposta<?php echo $coluna; ?>">
                                    <select name="idConteudo<?php echo $coluna; ?>" id="idConteudo<?php echo $coluna; ?>" style="top:auto;">
                                        <option value="">[Sem Categoria]</option>
                                        <?php
                                        if ($qryRespostas) {
                                            foreach ($qryRespostas as $rsp) {
                                                ?>
                                                <option value="<?php echo $rsp["idConteudo"]; ?>" <?php
                                                if ($rsp["idConteudo"] == $qryPaginada[$i]["idConteudo"]): echo 'selected';
                                                endif;
                                                ?>><?php echo $rsp["nmTituloConteudo"]; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                    </select>
                                </div>
                            <?php } else { ?>
                                <div id="cResposta<?php echo $coluna; ?>">Nenhuma resposta para esta categoria.</div>
                            <?php } ?>
                        </td>
                        <td align="left">

                            <?php
                            if ($qryPaginada[$i]["idRedeSocial"] == 1) {

                                $linkComentario = 'http://twitter.com/' . $qryPaginada[$i]["nmUsernameRede"] . '/status/' . $qryPaginada[$i]["idPostRede"];
                                ?>
                                <a href="<?php echo $linkComentario; ?>" target="_blank"><?php echo $qryPaginada[$i]["nmComentario"]; ?></a>
                                <?php
                            } else if ($qryPaginada[$i]["idRedeSocial"] == 2) {

                                $postID = explode('_', $qryPaginada[$i]["idPostRede"]);
                                $linkComentario = 'http://www.facebook.com/' . $postID[0] . '/posts/' . $postID[1];
                                ?>
                                <a href="<?php echo $linkComentario; ?>" target="_blank"><?php echo $qryPaginada[$i]["nmComentario"]; ?></a>
                                <?php
                            } else {

                                echo $qryPaginada[$i]["nmComentario"];
                            }
                            ?>


                            <!--                    https://www.facebook.com/eusouhenrique22/posts/401842426546526
                                                https://twitter.com/eusouhenrique22/status/241524805667549185					
                            -->					
                            <?php //echo $qryPaginada[$i]["nmComentario"];  ?></td>
                    </tr>
                    <input type="hidden" name="idComentario<?php echo $coluna; ?>" id="idComentario<?php echo $coluna; ?>" value="<?php echo $qryPaginada[$i]["idComentario"]; ?>"/>
                </form>
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
<br/>