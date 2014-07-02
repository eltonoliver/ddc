<?php
//Autor: 	Eder Martins Franco
//Data:		Março/2011

//Incluir a classe dBug
require_once("dBug.php");

class Erros extends Exception
{

public $mensagem,$arquivo,$linha,$codErro,$detalhes,$tipo,$raiz;
	
	/*************************************************
	  METODOS(set) PARA SETAR OS ATRIBUTOS
	**************************************************/
	Function setMensagem($pMensagem)  		{ $this->mensagem   = $pMensagem;}
	Function setArquivo($pArquivo)  		{ $this->arquivo  	= $pArquivo;}	
	Function setLinha($pLinha)  			{ $this->linha  	= $pLinha;}
	Function setCodErro($pCodErro)  		{ $this->codErro 	= $pCodErro;}
	Function setDetalhes($pDetalhes)  		{ $this->detalhes  	= $pDetalhes;}
	Function setTipo($pTipo)  				{ $this->tipo  		= $pTipo;}
	Function setRaiz($pRaiz)  				{ $this->raiz  		= $pRaiz;}
	
	/*************************************************
	  METODOS(get) PARA BUSCAR OS ATRIBUTOS
	**************************************************/
	Function getMensagem()             {return ( $this->mensagem); }
	Function getArquivo()              {return ( $this->arquivo); }
	Function getLinha()                {return ( $this->linha); }
    Function getCodErro()              {return ( $this->codErro); }
	Function getDetalhes()             {return ( $this->detalhes); }
    Function getTipo()                 {return ( $this->tipo); }
    Function getRaiz()                 {return ( $this->raiz); }
	

public function trataErros(){
	
	echo('Aqui fará o log de erros.');
	
}

public function gravaLog(){
	
//	echo('Mensagem: '); var_dump($this->getMensagem());
//	echo('<br/>');


	
	if ($this->getMensagem() != '' && !stristr($this->getArquivo(),'tpl_c')){
		
		//1. grava o log em uma tabela do banco de dados, se não for um erro de banco
		if (!stristr($this->getMensagem(),'connect') && !stristr($this->getMensagem(),'db')){
			
			
//			$DB = new Mssql;
//			
//			if( $DB->conectarMssql() )
//			{
//				
//				$parametros["navegador"] 		= $_SERVER["HTTP_USER_AGENT"]; //navegador
//				$parametros["servidor"] 		= $_SERVER["SERVER_ADDR"]; //servidor
//				$parametros["template"] 		= $_SERVER["SCRIPT_NAME"]; //arquivo onde ocorreu o erro ou a chamada de função
//				$parametros["SID"] 				= $_SESSION["SID"]; //ID da sessão
//				$parametros["IP"] 				= $_SESSION["IP"]; //IP do usuário
//				
//				$parametros["mensagem"] 		= $this->getMensagem();
//				$parametros["arquivo"] 			= $this->getArquivo();
//				$parametros["linha"] 			= $this->getLinha();
//				$parametros["codErro"] 			= $this->getCodErro();
//				$parametros["detalhes"] 		= $this->getDetalhes();
//				
//				$DB->setStoreProcedure("gravaLogErroPHP");
//				
//				$retorno =  $DB->executarStoreProcedure($parametros);
//				
//				$DB->desconectarMssql();
//		
//			}
//			else
//			{
//				$retorno =  false;
//			}

			
		} else {
			//$this->chamaMensagem();
			//exit;	
			$this->mostraMensagem();
		}
		$retorno =  false;
		
		//2. gera um arquivo .txt com o log do erro 
//		$t = date("H:i:s");
//		$strTexto = "$t | Numero: ".$this->getCodErro()." Mensagem: ".$this->getMensagem().", Arquivo: ".$this->getArquivo().", Linha: ".$this->getLinha()." | Detalhes: ".$this->getDetalhes()."\n";
//		
//		//$nomeArquivo = $this->getRaiz().'log'.date('d-m-Y').'.txt';
//
//		$nomeArquivo = $_SERVER['DOCUMENT_ROOT'].'/log/'.'log'.date('d-m-Y').'.txt';
//		chmod ($nomeArquivo, 0777);
//		$arquivo = fopen($nomeArquivo,"a");
//		fwrite($arquivo, "$strTexto \n");
//		fclose($arquivo);
		
		
		// 3. chamar uma função para exibir uma interface informando erro ao usuário
		return($retorno);
		
	}
	
}


public function mostraMensagem(){
	
	echo('
		<div style="background-color:#ffffff; border:#666666 1px solid; width: 100%; min-height:25px;">
			<span style="color:#e72936;">Ocorreu um erro insperado. Tente novamente ou contate o suporte.</span>
			<br/>
			 Erro: '.$this->getCodErro().'<br/>Mensagem: '.$this->getMensagem().'<br/>Arquivo: '.$this->getArquivo().'<br/>Linha: '.$this->getLinha().'
			 <br/>Detalhes: ');
			 //new dBug($this->getDetalhes());
	echo('</div>');
	
	//<br/>Detalhes: '.new dBug($this->getDetalhes()).'
	
//	echo '<div style="background-color:#ffffff; border:#666666 1px solid; width: 100%;">';
//	new dBug($this->getDetalhes());
//	echo '</div>';
	
}

public function chamaMensagem(){
	$caminhoServidor = $_SERVER["HTTP_HOST"].'/protestafacil/';
	echo('
		<script type="text/javascript">
			alert("Ocorreu um erro inesperado no Sistema, que já foi enviado à equipe de suporte.\nPor favor, tente novamente ou entre em contato com o\nsetor responsável.");
			location.href="http://'.$caminhoServidor.'index.php";
		
		</script>
	');	
}


}

?>