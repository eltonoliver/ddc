<?php
if (!isset($_GET["continuar"])) {
    //Deleta todos os arquivos na pasta tempor�ria
    $diretorio = $raiz . 'arquivos/enviados/temp/';
    $dir->setDiretorio($diretorio);
    $dir->limpaDiretorio();

    /* //Deleta as tumbnails dos arquivos na pasta tempor�ria
      $diretorio = $raiz . 'arquivos/enviados/thumbnails/';
      $dir->setDiretorio($diretorio);
      $dir->limpaDiretorio(); */
}
?>

<h1>Enviar Arquivos</h1>
<br/>
<h2 class="separadador">Etapa <span class="destaqueVermelho">1</span> de 3: Selecionar os arquivos</h2>
<span class="destaque_italico">&raquo; Selecione arquivos que você deseja incluir no sistema e clique em <span class="destaqueForte">Enviar todos</span>;<br/>
    &raquo; <span class="destaque_italico">Extensões permitidas <strong><span class="destaqueForte">Imagens: jpg, png, gif, jpge</span> - <span class="destaqueForte">Áudio: mp3,ogg</span> - <span class="destaqueForte">Vídeos: flv, wmv, mp4, mpge</span> - <span class="destaqueForte">Documentos: zip, rar, pdf</span></strong></span>
    <br/>
    &raquo; <span class="destaqueForte">Não saia desta página</span> até que o processo seja concluído, para que os arquivos enviados não sejam excluídos.<br/>
    &raquo; Após concluir o envio de todos os arquivos, clique em <span class="destaqueForte">Próxima Etapa</span>.</span>
<br/>
<br/>
<div align="left">
    <button type="button" name="btEnviar" id="btEnviar"  onclick="proximaEtapa();">Próxima Etapa</button>
    <?php if (!$_REQUEST['noTopoRodape']): ?>
        <button type="button" name="btVoltar" id="btVoltar"  onclick="javascript: location.href = 'menu-arquivos';">Voltar</button>
    <?php endif; ?>
</div>

<br/>
<div id="fileupload">
    <form action="<?php echo $url_raiz; ?>upload.php" method="POST" enctype="multipart/form-data">
        <div class="fileupload-buttonbar">
            <label class="fileinput-button">
                <span>Adicionar Arquivos...</span>
                <input type="file" name="files[]" multiple>
            </label>
            <button type="submit" class="start">Enviar todos</button>
            <button type="reset" class="cancel">Cancelar envio</button>
            <button type="button" class="delete">Deletar todos</button>
        </div>
    </form>
    <div class="fileupload-content">
        <table class="files"><tr><td></td></tr></table>
        <div class="fileupload-progressbar"></div>
    </div>
</div>

<script type="text/javascript">
        function proximaEtapa() {
            enableCache = false;
            displayMessage('<?php echo $url_raiz; ?>admin/popup-msg-aguarde?ajax=1?', 188, 300);
            $('#DHTMLSuite_modalBox_contentDiv').css('opacity', 0);
            $('#DHTMLSuite_modalBox_contentDiv').animate({opacity: 1}, 'fast', function() {
                ajaxRequest.sendJson('<?php echo $url_raiz; ?>admin/controller/act-arquivos?acao=validarArquivosTemp', function(d) {
                    if (!d.status) {
                        $('#msgResposta').html('Nao foi possível passar para próxima etapa, favor selecione pelo menos um arquivo.');
                        $('#msgResposta').css('color', '#C83C12');
                    } else {
                        window.location = 'cad-arquivos2?noTopoRodape=<?php echo $_REQUEST['noTopoRodape']; ?>&idConteudo=<?php echo $_REQUEST['idConteudo']; ?>';
                    }
                });
            });
        }

</script>

<script id="template-upload" type="text/x-jquery-tmpl">
    <tr class="template-upload{{if error}} ui-state-error{{/if}}">
    <td class="preview"></td>
    <td class="name">{{if name}}${name}{{else}}Untitled{{/if}}</td>
    <td class="size">${sizef}</td>
    {{if error}}
    <td class="error" colspan="2">Error:
    {{if error === 'maxFileSize'}}File is too big
    {{else error === 'minFileSize'}}File is too small
    {{else error === 'acceptFileTypes'}}Filetype not allowed
    {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
    {{else}}${error}
    {{/if}}
    </td>
    {{else}}
    <td class="progress"><div></div></td>
    <td class="start"><button>Start</button></td>
    {{/if}}
    <td class="cancel"><button>Cancel</button></td>
    </tr>
</script>
<script id="template-download" type="text/x-jquery-tmpl">
    <tr class="template-download{{if error}} ui-state-error{{/if}}">
    {{if error}}
    <td></td>
    <td class="name">${name}</td>
    <td class="size">${sizef}</td>
    <td class="error" colspan="2">Error:
    {{if error === 1}}File exceeds upload_max_filesize (php.ini directive)
    {{else error === 2}}File exceeds MAX_FILE_SIZE (HTML form directive)
    {{else error === 3}}File was only partially uploaded
    {{else error === 4}}No File was uploaded
    {{else error === 5}}Missing a temporary folder
    {{else error === 6}}Failed to write file to disk
    {{else error === 7}}File upload stopped by extension
    {{else error === 'maxFileSize'}}File is too big
    {{else error === 'minFileSize'}}File is too small
    {{else error === 'acceptFileTypes'}}Filetype not allowed
    {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
    {{else error === 'uploadedBytes'}}Uploaded bytes exceed file size
    {{else error === 'emptyResult'}}Empty file upload result
    {{else}}${error}
    {{/if}}
    </td>
    {{else}}
    <td class="preview">
    {{if thumbnail_url}}
    <a href="${url}" target="_blank"><img src="${thumbnail_url}"></a>
    {{/if}}
    </td>
    <td class="name">
    <a href="${url}"{{if thumbnail_url}} target="_blank"{{/if}}>${name}</a>
    </td>
    <td class="size">${sizef}</td>
    <td colspan="2"></td>
    {{/if}}
    <td class="delete">
    <button data-type="${delete_type}" data-url="${delete_url}">Delete</button>
    </td>
    </tr>
</script>
<br/>
<div align="left">
    <button type="button" name="btEnviar2" id="btEnviar2" onClick="proximaEtapa();">Próxima Etapa</button>
    <?php if (!$_REQUEST['noTopoRodape']): ?>
        <button type="button" name="btVoltar2" id="btVoltar2"  onclick="javascript: location.href = 'menu-arquivos';">Voltar</button>
    <?php endif; ?>
</div>


<script src="<?php echo $url_raiz; ?>admin/js/multiUpload/jquery.min.js"></script>
<script src="<?php echo $url_raiz; ?>admin/js/multiUpload/jquery-ui.min.js"></script>
<script src="<?php echo $url_raiz; ?>admin/js/multiUpload/jquery.tmpl.min.js"></script>
<script src="<?php echo $url_raiz; ?>admin/js/multiUpload/jquery.iframe-transport.js"></script>
<script src="<?php echo $url_raiz; ?>admin/js/multiUpload/jquery.fileupload.js"></script>
<script src="<?php echo $url_raiz; ?>admin/js/multiUpload/jquery.fileupload-ui.js"></script>
<script src="<?php echo $url_raiz; ?>admin/js/multiUpload/application.js"></script>
