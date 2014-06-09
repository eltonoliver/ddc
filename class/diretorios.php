<?php
/*
Autor: 		Eder Martins Franco
Data: 		04/10/2011
Atualizado: 30/11/2011
Versão: 	1.1
Descrição:	Classe simples para operações com diretórios.

v1.1 - Adicionada a função limpaDiretorio(), para apagar todos os arquivos de um diretório. Recebe como parâmetros o nome do diretório
		e um arquivo para "exceção", que não será excluído.
*/


class diretorios{
	
	public $diretorio,$extensao,$nomeArquivo;

//	************************************************
//	  METODOS(set) PARA SETAR OS ATRIBUTOS
//	*************************************************
	Function setDiretorio($pDiretorio)  		{ $this->diretorio 	= $pDiretorio;}
	Function setExtensao($pExtensao)  			{ $this->extensao 	= $pExtensao;}
	Function setNomeArquivo($pNomeArquivo)  	{ $this->nomeArquivo 	= $pNomeArquivo;}
	
//	************************************************
//	  METODOS(get) PARA BUSCAR OS ATRIBUTOS
//	*************************************************
	Function getDiretorio()             	     {return ( $this->diretorio); }
	Function getExtensao()             	    	 {return ( $this->extensao); }
	Function getNomeArquivo()             	     {return ( $this->nomeArquivo); }
	
	
	function buscaExtensao($nomeArquivo){
		$extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
		return $extensao;	
	}
	
	function listaArquivos(){
		
		$arrayArquivos = array();
		$diretorio = $this->getDiretorio();
		$extensao = $this->getExtensao();

		//Verifica se o diretório é válido
		if (is_dir($diretorio)) {
			
			if ($dir = opendir($diretorio)) {//Abre o diretório
			
				$arq = scandir($diretorio);
				
				if($extensao && $extensao != '*'){//Se foi definido um filtro de extensao, cria um novo array com os arquivos filtrados
					
					$j = 0;
					for($i=0; $i<count($arq); $i++){
						
						$ext = $this->buscaExtensao($arq[$i]);
						if($ext == $extensao){

							$arrayArquivos[$j] = $arq[$i];
							$j++;
						}
					
					}
					
				} else if($extensao && $extensao == '*'){//Se foi definido um filtro de extensao, cria um novo array com os arquivos filtrados
					
					$j = 0;
					for($i=0; $i<count($arq); $i++){
						
						if($arq[$i] != '..' && $arq[$i] != '.'){

							$arrayArquivos[$j] = $arq[$i];
							$j++;
						}
					
					}
					
				} else {//Se não foi definido um filtro de extensao, retorna um array com o conteudo completo do diretorio
					$arrayArquivos = $arq;
				}
				
			
			}  else {//Se ocorreu erro ao abrir o diretório
							
				$arrayArquivos[0] = 'Erro na abertura do diretorio';	
			}
			
		}  else {//Se o diretório não é válido
			
			$arrayArquivos[0] = 'Diretorio invalido';	
		}
		
		return $arrayArquivos;

	}//Fim - função
	
	
	function limpaDiretorio()
	{
		//$dir = 'images/';
		$name = $this->getNomeArquivo();
		$dir = $this->getDiretorio();
		if(is_dir($dir))
		{
			if($handle = opendir($dir))
			{
				while(($file = readdir($handle)) !== false)
				{
					if($file != '.' && $file != '..')
					{
						if( $file != $name)
						{
							unlink($dir.$file);
						}
					}
				}
			}
			return true;
			
		} else {
			
			die("Erro ao abrir dir: $dir");
			return false;

		}
	}	
	
	function moverArquivo($origem,$destino){
		
		$arquivo = $this->getNomeArquivo();
		$dir = $this->getDiretorio();
		
		$origem = $dir.$origem.$arquivo;
		$destino = $dir.$destino.$arquivo;
		
		if(copy($origem, $destino)){
			//echo "Arquivos copiados com sucesso.";
			return true;
		} else {
			echo "Ocorreu um erro. Tente novamente.";
			return false;
		}
	}
		

}

?>