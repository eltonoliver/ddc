<?php
/*
Autor: Eder Martins Franco
Data: Julho/2011
Atualizado: 28/09/2011
Versão: 1.2
Descrição: Classe simples para upload de arquivos para o servidor.

v1.1 - Adicionas as funções     limpaString($string), limpaStringArquivo($string) e renomearArquivo($string);
     - Adicionados os métodos Renomear (get,set) que, quando o valor for 1, renomeia arquivo, limpando caracteres especias e espaços.

v1.2 - Adicionada o método geraDataHora(), para usos diversos;
*/

class upload {
    
    public $largura,$altura,$tamanho,$arquivo,$caminho,$extensoes,$renomear;

//  ************************************************
//    METODOS(set) PARA SETAR OS ATRIBUTOS
//  *************************************************
    Function setLargura($pLargura)          { $this->largura    = $pLargura;   }
    Function setAltura($pAltura)            { $this->altura     = $pAltura;    }    
    Function setTamanho($pTamanho)          { $this->tamanho    = $pTamanho;   }
    Function setArquivo($pArquivo)          { $this->arquivo    = $pArquivo;   }
    Function setCaminho($pCaminho)          { $this->caminho    = $pCaminho;   }
    Function setExtensoes($pExtensoes)      { $this->extensoes  = $pExtensoes; }
    Function setRenomear($pRenomear)        { $this->renomear   = $pRenomear;  }
    
//  ************************************************
//    METODOS(get) PARA BUSCAR OS ATRIBUTOS
//  *************************************************
    Function getLargura()                { return ( $this->largura);   }
    Function getAltura()                 { return ( $this->altura);    }
    Function getTamanho()                { return ( $this->tamanho);   }
    Function getarquivo()                { return ( $this->arquivo);   }
    Function getCaminho()                { return ( $this->caminho);   }
    Function getExtensoes()              { return ( $this->extensoes); }
    Function getRenomear()               { return ( $this->renomear);  }

    //Envia imagens para o servidor
    function enviarArquivo() {

        $retorno["erro"] = "";
        $retorno["nome_arquivo"] = "";

        $extensoes = $this->getExtensoes();
        $caminho = $this->getCaminho();
        $arquivo = $this->getarquivo();

        if(strlen($extensoes) == 0) {
            //$extensoes = 'jpg,png,gif,pdf,rar,zip,jpge';
            $extensoes = 'jpg,png,jpge,gif,jpge,mp3,ogg,flv,wmv,mp4,mpge,zip,rar,pdf';
        }

        $mimeTypes = "image/jpeg,image/pjpeg,image/png,image/gif,audio/mpeg3,audio/x-mpeg-3,video/mpeg,video/x-mpeg,video/x-flv,video/ogg,audio/ogg,audio/x-ms-wma,audio/x-ms-wmv,audio/mpeg,video/mp4,video/mpeg,video/mpeg,video/mpeg,application/zip,application/rar,application/pdf";       

        if(strlen($caminho) == 0) {
           $retorno["erro"] = $retorno["erro"] . " ". "O caminho para upload do arquivo não foi definido.";
           return $retorno;
           exit;
        }

        // Se a arquivo estiver sido selecionada
        if (!empty($arquivo["name"])) {

            // Tamanho máximo do arquivo em bytes
            if(strlen($this->getTamanho()) == 0) {
                $tamanho = 1048576; //Padrão: 1MB = 1048576 bytes
            } else {
                $tamanho = $this->getTamanho();
            }

            //Verifica a extensão do arquivo
            $ext = pathinfo($arquivo["name"], PATHINFO_EXTENSION);
            $ext = strtolower($ext);
            $permitidas = explode(",",$extensoes);

            if(!in_array($ext,$permitidas)) {
               $retorno["erro"] = $retorno["erro"] . " ". "O arquivo enviado não é de um tipo válido.";
               return $retorno;
               exit;
            }

            //Verifica o MIME TYPE do arquivo
            $tipoArquivo = $arquivo["type"];
            $permitidos = explode(",",$mimeTypes);

            if(!in_array($tipoArquivo,$permitidos)){
               $retorno["erro"] = $retorno["erro"] . " ". "O arquivo enviado não é de um tipo válido.";
               return $retorno;
               exit;
            }
//          var_dump($retorno);
//          exit;

            // Verifica se o tamanho da arquivo é maior que o tamanho permitido
            if($arquivo["size"] > $tamanho) {
                $retorno["erro"] = $retorno["erro"] . " ". "O arquivo deve ter no máximo ".$tamanho." bytes";
                return $retorno;
                exit;
            }

            //Se o tipo de arquivo for uma imagem
            if(strpos($arquivo["type"],"image")>0) {

                // Largura máxima em pixels
                if(strlen($this->getLargura()) == 0) {
                    $largura = 500;
                } else {
                    $largura = $this->getLargura();
                }

                // Altura máxima em pixels
                if(strlen($this->getAltura()) == 0){
                    $altura = 500;
                } else {
                    $altura = $this->getAltura();
                }

                // Pega as dimensões da arquivo
                $dimensoes = getimagesize($arquivo["tmp_name"]);

                // Verifica se a largura da arquivo é maior que a largura permitida
                if($dimensoes[0] > $largura) {
                    $retorno["erro"] = $retorno["erro"] . " ". "A largura da imagem não deve ultrapassar ".$largura." pixels";
                    return $retorno;
                    exit;
                }

                // Verifica se a altura da arquivo é maior que a altura permitida
                if($dimensoes[1] > $altura) {
                    $retorno["erro"] = $retorno["erro"] . " ". "A altura da imagem não deve ultrapassar ".$altura." pixels";
                    return $retorno;
                    exit;
                }
            }

            if($this->getRenomear() == 1) {
                $nome_arquivo = $this->renomearArquivo($arquivo["name"]);
                //$idUnico = $this->geraDataHora();
                $idUnico = $this->geraDataHora();
                $nome_arquivo = $idUnico.'-'.$nome_arquivo;

            } else {
                // Gera um nome único para a arquivo
                $nome_arquivo = md5(uniqid(time())) . "." . $ext;
                //$nome_arquivo = "teste" . "." . $ext;
            }

            // Caminho de onde ficará a arquivo
            $caminho_arquivo = $caminho . $nome_arquivo;

            // Faz o upload da arquivo para seu respectivo caminho
            $resultado = move_uploaded_file($arquivo["tmp_name"], $caminho_arquivo);

            if($resultado == true) {
                $retorno["nome_arquivo"] = $nome_arquivo; 
                return $retorno;
                exit;
            } else {
                $retorno["erro"] = $retorno["erro"] . " ". "Ocorreu um erro ao tentar enviar o arquivo. Tente novamente.";
                return $retorno;
                exit;
            }
        } else {
            $retorno["erro"] = $retorno["erro"] . " ". "Nenhum arquivo enviado. Tente novamente.";
            return $retorno;
            exit;
        }

    }//Fim- enviaarquivo

    function limpaString($string) {
        $novaString = str_replace('.','',$string);
        $novaString = str_replace(':','',$string);
        $novaString = str_replace(',','',$string);
        $novaString = str_replace('/','',$novaString);
        $novaString = str_replace('\\','',$novaString);
        $novaString = str_replace('-','',$novaString);
        $novaString = str_replace('(','',$novaString);
        $novaString = str_replace(')','',$novaString);
        $novaString = str_replace(' ','',$novaString);

        return $novaString;
    }

    function limpaStringArquivo($string) {
        $novaString = str_replace(',','',$string);
        $novaString = str_replace('/','',$novaString);
        $novaString = str_replace('\\','',$novaString);
        $novaString = str_replace('-','',$novaString);
        $novaString = str_replace('(','',$novaString);
        $novaString = str_replace(')','',$novaString);
        $novaString = str_replace(" ","_",$string);

        return $novaString;
    }

    function renomearArquivo($string) {

        $string = limpaStringArquivo($string);
        $busca = array( 'Á', 'À', 'Ã', 'É', 'Ê', 'Í', 'Ó', 'Õ', 'Ú', 'Ç', 'á', 'à', 'ã', 'é', 'ê', 'í', 'ó', 'õ', 'ú', 'ç');
        $retira = array( 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'U', 'C', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'u', 'c');
        $string = str_replace($busca, $retira, $string);

        setlocale(LC_CTYPE,"pt_BR");
        $novaString = trim(strtolower($string));

        return $novaString;
    }

    function geraDataHora() {

        date_default_timezone_set("America/Manaus");

        $string = date("d/m/Y H:i:s");

        $ano = substr($string,6,4);
        $mes = substr($string,3,2);
        $dia = substr($string,0,2);


        $hora = substr($string,11,2);
        $minuto = substr($string,14,2);
        $segundo = substr($string,17,2);

        $dataCompleta = $ano.'-'.$mes.'-'.$dia.' '.$hora.':'.$minuto.':'.$segundo;

        $dateConvertida = strtotime($dataCompleta);
        $data = date("YmdHis",$dateConvertida);

        return $data;
    }
} ?>