<?php

//Função Específica
//Verifica se um campo do formulário pode ser exibido para o tipo de conteúdo desejado.
function mostraCampo($nome, $idTipoConteudo) {

    $campoConteudo = array();
    $campoConteudo[0][0] = "nmTituloConteudo";
    $campoConteudo[0][1] = "7,6,3,9,10,2,8,11,13,5,4,12";

    $campoConteudo[1][0] = "nmResumo";
    $campoConteudo[1][1] = "6,9,10,2,3,8,11,5,4,12";

    $campoConteudo[2][0] = "nmConteudo";
    $campoConteudo[2][1] = "9,2,11,13,3,4";

    $campoConteudo[3][0] = "nmFonteExterna";
    $campoConteudo[3][1] = "9,2,11";

    $campoConteudo[4][0] = "nmLinkExterno";
    $campoConteudo[4][1] = "7,6,9,2,8,11,5,10,12,4";

    $campoConteudo[5][0] = "nmLinkImagem";
    $campoConteudo[5][1] = "6,3,9,2,11,13,5,4";

    $campoConteudo[6][0] = "idCategoria";
    $campoConteudo[6][1] = "3,2,8,11";

    $campoConteudo[7][0] = "dtDataConteudo";
    $campoConteudo[7][1] = "2";

    $campoConteudo[8][0] = "nmObservacoes";
    $campoConteudo[8][1] = "0";

    $campoConteudo[9][0] = "inPublicar";
    $campoConteudo[9][1] = "7,6,3,9,10,2,8,11,13,5,4,12";

    $campoConteudo[10][0] = "inDestaque";
    $campoConteudo[10][1] = "2,8,5,3,7";

    $campoConteudo[11][0] = "nmLinkMiniatura";
    $campoConteudo[11][1] = "";

    $campoConteudo[12][0] = "nmTagConteudo";
    $campoConteudo[12][1] = "0";

    $campoConteudo[13][0] = "horarios";
    $campoConteudo[13][1] = "11";

    $campoConteudo[14][0] = "inCompartilhar";
    $campoConteudo[14][1] = "11";

    $retorno = false;
    for ($i = 0; $i < count($campoConteudo); $i++) {
        for ($j = 0; $j < count($campoConteudo[$i]); $j++) {

            //Se achou o item no array.
            if (strlen(strpos($campoConteudo[$i][$j], $nome)) > 0) {

                $array = explode(',', $campoConteudo[$i][$j + 1]);
                //verifica na lista de tipos deisponíveis do campo se o tipo desejado está incluído
                $retorno = in_array($idTipoConteudo, $array);
            } //fim - IF
        }//fim - FOR J
    }//fim- FOR I

    return $retorno;
}

//fim - Função

function pastaArquivo($idTipoArquivo) {

    switch ($idTipoArquivo) {
        case 1:
            $arrayTipo["pasta"] = 'file';
            $arrayTipo["icone"] = 'outros';
            break;
        case 2:
            $arrayTipo["pasta"] = 'file';
            $arrayTipo["icone"] = 'rar';
            break;
        case 3:
            $arrayTipo["pasta"] = 'file';
            $arrayTipo["icone"] = 'documentos';
            break;
        case 4:
            $arrayTipo["pasta"] = 'media';
            $arrayTipo["icone"] = 'audio';
            break;
        case 5:
            $arrayTipo["pasta"] = 'media';
            $arrayTipo["icone"] = 'video';
            break;
        case 6:
            $arrayTipo["pasta"] = 'image';
            $arrayTipo["icone"] = 'imagem';
            break;
        default:
            $arrayTipo["pasta"] = 'outros';
            $arrayTipo["icone"] = 'outros';
            break;
    }
    return $arrayTipo;
}

//Fim - pastaArquivo($idTipoArquivo);

function tipoArquivo($arquivo) {

    //Extensões permitias por tipo de arquivo
    $imagens = 'jpg,png,gif,jpge';
    $imagens = explode(",", $imagens);

    $audio = 'mp3,ogg';
    $audio = explode(",", $audio);

    $video = 'flv,wmv,mp4,mpge';
    $video = explode(",", $video);

    $compactados = 'zip,rar';
    $compactados = explode(",", $compactados);

    $documentos = 'pdf';
    $documentos = explode(",", $documentos);

    $ext = pathinfo($arquivo, PATHINFO_EXTENSION);
    $ext = strtolower($ext);

    switch ($ext) {
        case in_array($ext, $imagens):
            //echo 'Imagem';
            $arrayTipo["idTipoArquivo"] = 6;
            $arrayTipo["pasta"] = 'image';
            $arrayTipo["icone"] = 'imagem';
            break;
        case in_array($ext, $audio):
            //echo 'Áudio';
            $arrayTipo["idTipoArquivo"] = 4;
            $arrayTipo["pasta"] = 'media';
            $arrayTipo["icone"] = 'audio';
            break;
        case in_array($ext, $video):
            //echo 'Vídeo';
            $arrayTipo["idTipoArquivo"] = 5;
            $arrayTipo["pasta"] = 'media';
            $arrayTipo["icone"] = 'video';
            break;
        case in_array($ext, $compactados):
            //echo 'Compactado';
            $arrayTipo["idTipoArquivo"] = 2;
            $arrayTipo["pasta"] = 'file';
            $arrayTipo["icone"] = 'rar';
            break;
        case in_array($ext, $documentos):
            //echo 'Documento';
            $arrayTipo["idTipoArquivo"] = 3;
            $arrayTipo["pasta"] = 'file';
            $arrayTipo["icone"] = 'documentos';
            break;
        default:
            //echo 'Outros';
            $arrayTipo["idTipoArquivo"] = 1;
            $arrayTipo["pasta"] = 'outros';
            $arrayTipo["icone"] = 'outros';
            break;
    }

    return $arrayTipo;
}

//Fim - pastaArquivo($idTipoArquivo);
?>