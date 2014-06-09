<?php

class boletimController {

    protected $_db;
    protected $_url_raiz;
    protected $_limiteEnvioHora = 100;
    protected $_geralConfig;
    protected $_qryRodape;
    protected $_mail;

    public function __construct(array $opcoes = array()) {
        $this->_db = $opcoes['db'];
        $this->_url_raiz = $opcoes['url_raiz'];
        $this->_geralConfig = $opcoes['geralConfig'];
        $this->_qryRodape = $opcoes['qryRodape'];
        $this->_mail = $opcoes['mail'];
    }

    protected function _gerKeyDados() {
        $key = md5(uniqid());
        $_SESSION[$key] = array();
        return $key;
    }

    protected function _validarEmailBoletim($dados = array()) {
        $semErro = true;
        if (eregi("^[-_a-z0-9]+(\.[-_a-z0-9]+)*\@([-a-z0-9]+\.)*([a-z]{2,4})$", $dados['email'])) {
            $dns_mail = explode("@", $dados['email']);
            if (!checkdnsrr($dns_mail[1])) {
                $semErro = false;
            }
        } else {
            $semErro = false;
        }
        if (!count($dados['grupos'])) {
            $semErro = false;
        }
        return $semErro;
    }

    public function cadastrarEmailBoletim() {
        $dados = array(
            'email' => tratastr($_POST['email']),
            'grupos' => $_POST['grupos']
        );
        if ($this->_validarEmailBoletim($dados)) {
            $emailNews = $this->_db->select(
                    array('nmEmail' => $dados['email']), 'tb_news_emails');
            if ($emailNews) {
                $idEmail = $emailNews[0]['idEmail'];
            }

            $d = array(
                'nmEmail' => $dados['email'],
                'inAtivo' => true);
            if ($idEmail) {
                //$q = $this->_db->updateQuery($d, 'tb_news_emails', 'idEmail=' . $idEmail);
                $retorno = array('status' => 2, 'msg' => 'E-mail já cadastrado.');
            } else {
                $q = $this->_db->insertQuery($d, 'tb_news_emails');
                $this->_db->update($q);
                $idEmail = $idEmail ? $idEmail : mysql_insert_id();
                $this->_db->update('DELETE FROM tb_news_grupo_emails WHERE idEmail=' . $idEmail);
                if ($dados['grupos']) {
                    foreach ($dados['grupos'] as $idGrupo) {
                        $this->_db->query($this->_db->insertQuery(array('idEmail' => $idEmail, 'idGrupo' => (int) $idGrupo), 'tb_news_grupo_emails'));
                    }
                }
                $retorno = array('status' => 1);
            }
        } else {
            $retorno = array('status' => 2, 'msg' => 'Digite um e-mail válido.');
        }
        echo json_encode($retorno);
        exit;
    }

    public function visualizar() {

        $idEnvio = $_REQUEST['idEnvio'];
        if (!is_numeric($idEnvio)) {
            throw new Exception('Erro no código.');
        }

        if (!$idEnvio) {
            return null;
        }

        $envio = $this->_db->query(
                'SELECT n.nmTituloAmigavel,e.*, e.idTipoConteudo as idTipoConteudo2 , c.* FROM tb_news_envio e ' .
                'INNER JOIN tb_news_envio_conteudo ec ON e.idEnvio = ec.idEnvio ' .
                'INNER JOIN vwconteudoarquivo c ON ec.idConteudo = c.idConteudo ' .
                'INNER JOIN tb_conteudo n ON n.idConteudo = c.idConteudo ' .
                'WHERE e.idEnvio = (' . $this->_db->clean($idEnvio) . ')');
        $diretorio = str_replace("modulos", "admin/modulos", dirname(__FILE__));
        switch ($envio[0]['idTipoConteudo2']) {
            case 11:
                ob_start();
                $url_raiz = $this->_url_raiz;
                include $r = $diretorio . '/../html/boletim.phtml';
                $html = ob_get_contents();
                ob_clean();
                break;

            case 4:
                ob_start();
                $url_raiz = $this->_url_raiz;
                include $r = $diretorio . '/../html/informativo.phtml';
                $html = ob_get_contents();
                ob_clean();
                break;

            case -1:
                ob_start();
                $url_raiz = $this->_url_raiz;
                include $r = $diretorio . '/../html/clipping.phtml';
                $html = ob_get_contents();
                ob_clean();
                break;

            default:
                $html = null;
                break;
        }
        echo $html;
        exit;
    }

}

$act = new boletimController(array(
    'db' => $db,
    'url_raiz' => $url_raiz,
    'geralConfig' => $geralConfig,
    'qryRodape' => $qryRodape,
    'mail' => $mail
        ));
$acao = $_REQUEST['acao'] ? $_REQUEST['acao'] : '';

if (strlen($acao) && method_exists($act, $acao)) {
    try {
        $act->{$acao}();
    } catch (Exception $e) {
        header('Location: ' . $url_raiz . '404');
    }
} else {
    header('Location: ' . $url_raiz . '404');
}

exit();
?>