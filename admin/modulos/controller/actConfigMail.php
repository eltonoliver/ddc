<?php 

	/**
	 *
	 * Classe de regencimento da newsletters
	 */
	class actConfigMail{
		protected $_db;
		protected $_url_raiz;
		protected $_limiteEnvioHora = 100;
		protected $_geralConfig;
		protected $_qryRodape;
		protected $_mail;
		
		public function __construct(array $opcoes = array()){
			$this->_db 			= $opcoes['db'];
			$this->_url_raiz 	= $opcoes['url_raiz'];
			$this->_geralConfig = $opcoes['geralConfig'];
			$this->_qryRodape 	= $opcoes['qryRodape'];
			$this->_mail 		= $opcoes['mail'];
		}
		
		protected function _gerKeyDados(){
			$key = md5(uniqid());
			$_SESSION[$key] = array();
			return $key;
		}
		
		public function atualizar(){
			$dados = array(
				'inSmtpMail' => $_POST['inSmtpMail'],
				'nmHostMail' => $_POST['nmHostMail'],
				'nmUserMail' => $_POST['nmUserMail'],
				'nmPassMail' => $_POST['nmPassMail'],
				'nrPortMail' => $_POST['nrPortMail'],
				'inAuthMail' => $_POST['inAuthMail']
			);
			
			$r = $this->_db->update($this->_db->updateQuery($dados, 'tb_geral', 'idGeral=1'));
			if($r){
				$_SESSION['msg'] = 'Dados atualizados com sucesso!';
			}else{
				$_SESSION[$key = $this->_gerKeyDados()]['dados'] = $dados;
				$_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
			}
			
			header('Location: '.$this->_url_raiz.'admin/cad-config-mail'.($key?'?key'.$key:''));
		}
	}
	
	$act = new actConfigMail(array(
		'db' 			=> $db,
		'url_raiz' 		=> $url_raiz,
		'geralConfig' 	=> $geralConfig,
		'qryRodape' 	=> $qryRodape,
		'mail' 			=> $mail
	));
	$acao = $_REQUEST['acao']?$_REQUEST['acao']:'';
	
	if(strlen($acao) && method_exists($act, $acao)){
		try{
			$act->{$acao}();
		}catch(Exception $e){
			header('Location: ' . $url_raiz . 'admin/sis-erro-sistema');
		}
	}else{
		header('Location: ' . $url_raiz . 'admin/sis-acesso-negado');
	}
	exit();
?>