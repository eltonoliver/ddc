<h1>Enviar Arquivos</h1>
<form name="formGeral" id="formGeral" action="controller/act-arquivos" method="post" enctype="multipart/form-data">
    <br/>
    <h2 class="separadador">Etapa <span class="destaqueVermelho">2</span> de 3: Editar e Salvar</h2>
    <span class="destaque_italico">&raquo; <span class="destaqueForte">Edite as informa&ccedil;&otilde;es</span> que voc&ecirc; deseja associar aos arquivos<span class="destaqueForte"></span>;<br/>
        &raquo; <span class="destaqueForte">Nao saia desta página</span> até que o processo seja concluído, para que os arquivos enviados nao sejam excluídos.<br/>
        &raquo; Após concluir, clique em <span class="destaqueForte">Finalizar</span>;</span>
    <br/>
    <br/>
    <div align="left">
        <button type="submit" name="btEnviar" id="btEnviar"   onclick="javascript: location.href = 'cad-arquivos2?noTopoRodape=<?php echo $_REQUEST['noTopoRodape']; ?>';">Próxima Etapa</button>
        <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href = 'cad-arquivos?continuar&noTopoRodape=<?php echo $_REQUEST['noTopoRodape']; ?>';">Voltar</button>
    </div>

    <br/>
    <fieldset>
        <?php
        $caminho = 'arquivos/enviados/temp/';
        $thumb = 'arquivos/enviados/thumbnails/';
        $diretorio = $raiz . $caminho;
        $dir->setDiretorio($diretorio);
        $dir->setExtensao('*');
        $arq = $dir->listaArquivos();
        ?>
        <table width="100%" border="0" align="center" class="tbLista">
            <tr class="tbTitulo">
                <td width="10%" align="center">Miniatura</td>
                <td width="20%" align="left">Arquivo</td>
                <td width="21%" align="left">Legenda</td>
                <td align="left">Descri&ccedil;&atilde;o</td>
            </tr>
            <?php
            $coluna = 1;
            for ($i = 0; $i < count($arq); $i++) {

                if ($coluna % 2 == 0) {
                    $classe = 'tbNormal';
                } else {
                    $classe = 'tbNormalAlt';
                }

                $miniatura = $url_raiz . $thumb . $arq[$i];
                $raizMiniatura = $raiz . $thumb . $arq[$i];

                $tipoArquivo = tipoArquivo($raizMiniatura); //Esta fun�ao est� na no arquivo lib/lib_especficia.php
                $idTipoArquivo = $tipoArquivo["idTipoArquivo"];
                $icone = $tipoArquivo["icone"];
                if ($idTipoArquivo == 6) {
                    
                } else {
                    $miniatura = $url_raiz . 'arquivos/enviados/thumbnails/icones/' . $icone . '.png';
                }
                ?>
                <tr class="<?php echo $classe; ?>">
                    <td width="10%" align="center"><img src="<?php echo $miniatura; ?>" border="0"/></td>
                    <td width="20%" align="left"><a href="<?php echo $url_raiz . $caminho . $arq[$i]; ?>" target="_blank"><?php echo $arq[$i]; ?></a></td>
                    <td width="21%" align="left">
                        <input type="hidden" id="nmNomeArquivo_<?php echo $i; ?>" name="nmNomeArquivo_<?php echo $i; ?>" value="<?php echo $arq[$i]; ?>"/> 
                        <input type="text" name="nmTituloArquivo_<?php echo $i; ?>" id="nmTituloArquivo_<?php echo $i; ?>" style="top:auto;" value="<?php //echo $arq[$i];  ?>" size="40"/>
                    </td>
                    <td align="left">
                        <textarea name="nmDescricaoArquivo_<?php echo $i; ?>" id="nmDescricaoArquivo_<?php echo $i; ?>" cols="60" rows="3"></textarea>
                    </td>
                </tr>
                <?php $coluna++;
            } ?>
        </table>      
    </fieldset>

    <br/>
    <div align="left">
        <input type="hidden" id="noTopoRodape" name="noTopoRodape" value="<?php echo $_REQUEST['noTopoRodape']; ?>"/> 
        <input type="hidden" id="totalArquivos" name="totalArquivos" value="<?php echo count($arq); ?>"/> 
        <input type="hidden" id="idConteudo" name="idConteudo" value="<?php echo $_REQUEST['idConteudo']; ?>" />
        <input type="hidden" name="acao" id="acao" value="Cadastrar" />
        <button type="submit" name="btEnviar2" id="btEnviar2"   onclick="javascript: location.href = 'cad-arquivos2?noTopoRodape=<?php echo $_REQUEST['noTopoRodape']; ?>';">Próxima Etapa</button>
        <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href = 'cad-arquivos?continuar&noTopoRodape=<?php echo $_REQUEST['noTopoRodape']; ?>';">Voltar</button>
    </div>
</form>