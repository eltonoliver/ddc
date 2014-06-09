<?php

/**
 *
 * Classe de regencimento da newsletters
 */
class actNews {

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

    public function validarGrupo(array $dados = array(), $idGrupo = null) {
        $semErro = true;
        if (!strlen($dados['nmGrupo'])) {
            $semErro = false;
            $_SESSION['msgErro'] = 'Digite o nome do grupo.';
        } else {
            $g = $this->_db->select(
                    array('nmGrupo' => $dados['nmGrupo']), 'tb_news_grupo');

            if ($g && $g[0]['idGrupo'] != $idGrupo) {
                $semErro = false;
                $_SESSION['msgErro'] = 'Grupo já está cadastrado, favor cadastrar outro.';
            }
        }

        return $semErro;
    }

    public function cadastrarGrupo() {
        $dados = array(
            'nmGrupo' => $_POST['nmGrupo'],
            'inAtivo' => $_POST['inAtivo']);
        $_SESSION[$key = $this->_gerKeyDados()]['dados'] = $dados;

        if ($this->validarGrupo($dados)) {
            $this->_db->update($this->_db->insertQuery($dados, 'tb_news_grupo'));

            $id = mysql_insert_id();
            if ($id) {
                $_SESSION['msg'] = 'Dados inseridos com sucesso!';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
        }
        header('Location: ' . $this->_url_raiz . 'admin/cad-news-grupo?id=' . $id . '&key=' . $key);
    }

    public function atualizarGrupo() {
        $id = $_POST['id'];
        $dados = array(
            'nmGrupo' => $_POST['nmGrupo'],
            'inAtivo' => $_POST['inAtivo']);
        $_SESSION[$key = $this->_gerKeyDados()]['dados'] = $dados;

        if ($this->validarGrupo($dados, $id)) {
            $resposta = $this->_db->update($this->_db->updateQuery($dados, 'tb_news_grupo', 'idGrupo = ' . $this->_db->clean($id)));
            if ($resposta) {
                $_SESSION['msg'] = 'Dados atualizados com sucesso!';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
        }

        header('Location: ' . $this->_url_raiz . 'admin/cad-news-grupo?id=' . $id . '&key=' . $key);
    }

    public function excluirGrupo() {
        $id = $this->_db->clean($_REQUEST['id']);
        $this->_db->update('DELETE FROM tb_news_grupo WHERE idGrupo = ' . $id);
        header('Location: ' . $this->_url_raiz . 'admin/menu-news-grupo');
    }

    public function validarEmail(array $dados = array(), $idEmail = null) {
        $semErro = true;
        if (!strlen($dados['nmEmail'])) {
            $semErro = false;
            $_SESSION['msgErro'] = 'Digite o e-mail.';
        } elseif (!preg_match("/^([[:alnum:]_.-]){3,}@([[:lower:][:digit:]_.-]{3,})(\.[[:lower:]]{2,3})(\.[[:lower:]]{2})?$/", $dados['nmEmail'])) {
            $semErro = false;
            $_SESSION['msgErro'] = 'Digite um e-mail válido.';
        } else {
            $g = $this->_db->select(
                    array('nmEmail' => $dados['nmEmail']), 'tb_news_emails');

            if ($g && $g[0]['idEmail'] != $idEmail) {
                $semErro = false;
                $_SESSION['msgErro'] = 'E-mail já está cadastrado, favor cadastrar outro.';
            }
        }

        if (!count($dados['grupos'])) {
            $_SESSION['msgErro'] = 'Selecione um grupo.';
        }

        return $semErro;
    }

    public function cadastrarEmail() {
        $dados = array(
            'nmNome' => $_POST['nmNome'],
            'nmEmail' => $_POST['nmEmail'],
            'inAtivo' => $_POST['inAtivo']);

        $_SESSION[$key = $this->_gerKeyDados()]['dados'] = $dados + array('grupos' => $_POST['grupos']);

        if ($this->validarEmail($dados + array('grupos' => $_POST['grupos']))) {
            $this->_db->update($this->_db->insertQuery($dados, 'tb_news_emails'));
            $id = mysql_insert_id();
            if ($id) {
                $grupos = $_POST['grupos'];
                if ($grupos) {
                    foreach ($grupos as $idGrupo) {
                        $this->_db->update($this->_db->insertQuery(array(
                                    'idEmail' => $id,
                                    'idGrupo' => $idGrupo
                                        ), 'tb_news_grupo_emails'));
                    }
                }
                $_SESSION['msg'] = 'Dados inseridos com sucesso!';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
        }
        header('Location: ' . $this->_url_raiz . 'admin/cad-news-emails?id=' . $id . '&key=' . $key);
    }

    public function atualizarEmail() {
        $id = $_POST['id'];
        $dados = array(
            'nmNome' => $_POST['nmNome'],
            'nmEmail' => $_POST['nmEmail'],
            'inAtivo' => $_POST['inAtivo']);
        $_SESSION[$key = $this->_gerKeyDados()]['dados'] = $dados + array('grupos' => $_POST['grupos']);

        if ($this->validarEmail($dados + array('grupos' => $_POST['grupos']), $id)) {
            $resposta = $this->_db->update($this->_db->updateQuery($dados, 'tb_news_emails', 'idEmail=' . $this->_db->clean($id)));
            if ($resposta) {
                $this->_db->update('DELETE FROM tb_news_grupo_emails WHERE idEmail = ' . $this->_db->clean($id));
                $grupos = $_POST['grupos'];
                if ($grupos) {
                    foreach ($grupos as $idGrupo) {
                        $this->_db->update($this->_db->insertQuery(array(
                                    'idEmail' => $id,
                                    'idGrupo' => $idGrupo
                                        ), 'tb_news_grupo_emails'));
                    }
                }
                $_SESSION['msg'] = 'Dados atualizados com sucesso!';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
        }
        header('Location: ' . $this->_url_raiz . 'admin/cad-news-emails?id=' . $id . '&key=' . $key);
    }

    public function excluirEmail() {
        $id = $this->_db->clean($_REQUEST['id']);
        $this->_db->update('DELETE FROM tb_news_emails WHERE idEmail = ' . $id);
        $this->_db->update('DELETE FROM tb_news_grupo_emails WHERE idEmail = ' . $id);

        header('Location: ' . $this->_url_raiz . 'admin/menu-news-emails');
    }

    public function validarEnvio(array $dados = array()) {
        $semErro = true;
        if (!count($dados['conteudo'])) {
            $semErro = false;
            $_SESSION['msgErro'] = 'Selecione a mala para envio.';
        } else {
            if (!count($dados['grupos'])) {
                $semErro = false;
                $_SESSION['msgErro'] = 'Selecione um grupo.';
            }
        }

        return $semErro;
    }

    public function cadastrarEnvio() {

        $dados = array(
            'idTipoConteudo' => $_REQUEST['idTipoConteudo'],
            'inAtivo' => $_POST['inAtivo'],
            'dtDataCadastro' => date('Y-m-d H:i:s')
        );

        $dR = array();
        $dR = $dR + array('grupos' => $_POST['grupos']) + array('conteudo' => $_POST['conteudo']);
        $_SESSION[$key = $this->_gerKeyDados()]['dados'] = $dados + $dR;

        if ($this->validarEnvio($dados + $dR)) {
            $n = $this->_db->query(
                    'SELECT (numeroEnvio+1) numeroEnvio FROM tb_news_envio WHERE idTipoConteudo = ' .
                    $this->_db->clean($dados['idTipoConteudo']) . ' ORDER BY numeroEnvio DESC LIMIT 1'
            );
            $n = $n ? ((int) $n[0]['numeroEnvio']) : 1;
            $dados['numeroEnvio'] = $n;

            $r = $this->_db->update($this->_db->insertQuery($dados, 'tb_news_envio'));
            if ($r) {
                $idEnvio = mysql_insert_id();
                $grupos = $_POST['grupos'];
                if ($grupos) {
                    foreach ($grupos as $idGrupo) {
                        $this->_db->update($this->_db->insertQuery(array('idEnvio' => $idEnvio, 'idGrupo' => $idGrupo), 'tb_news_envio_grupo'));
                    }
                }

                $conteudo = $_POST['conteudo'];
                if ($conteudo) {
                    foreach ($conteudo as $idConteudo) {
                        $this->_db->update($this->_db->insertQuery(array(
                                    'idEnvio' => $idEnvio, 'idConteudo' => $idConteudo), 'tb_news_envio_conteudo'));
                    }
                }

                $_SESSION['msg'] = 'Dados inseridos com sucesso!';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
        }
        header('Location: ' . $this->_url_raiz . 'admin/cad-news-envio?id=' . $idEnvio . '&key=' . $key);
    }

    public function atualizarEnvio() {

        $id = $_POST['id'];
        $dados = array(
            'idTipoConteudo' => $_REQUEST['idTipoConteudo'],
            'inAtivo' => $_POST['inAtivo'],
            'dtDataCadastro' => date('Y-m-d H:i:s')
        );

        $dR = array();
        $dR = $dR + array('grupos' => $_POST['grupos']) + array('conteudo' => $_POST['conteudo']);
        $_SESSION[$key = $this->_gerKeyDados()]['dados'] = $dados + $dR;

        if ($this->validarEnvio($dados + $dR)) {
            $r = $this->_db->update($this->_db->updateQuery($dados, 'tb_news_envio', 'idEnvio=' . $this->_db->clean($id)));
            if ($r) {
                $this->_db->update('DELETE FROM tb_news_envio_grupo WHERE idEnvio=' . $this->_db->clean($id));
                $grupos = $_POST['grupos'];
                if ($grupos) {
                    foreach ($grupos as $idGrupo) {
                        $this->_db->update($this->_db->insertQuery(array('idEnvio' => $id, 'idGrupo' => $idGrupo), 'tb_news_envio_grupo'));
                    }
                }

                $this->_db->update('DELETE FROM tb_news_envio_conteudo WHERE idEnvio=' . $this->_db->clean($id));
                $conteudo = $_POST['conteudo'];
                if ($conteudo) {
                    foreach ($conteudo as $idConteudo) {
                        $this->_db->update($this->_db->insertQuery(array('idEnvio' => $id, 'idConteudo' => $idConteudo), 'tb_news_envio_conteudo'));
                    }
                }

                $_SESSION['msg'] = 'Dados atualizados com sucesso!';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
        }
        header('Location: ' . $this->_url_raiz . 'admin/cad-news-envio?id=' . $id . '&key=' . $key);
    }

    public function excluirEnvio() {
        $id = $this->_db->clean($_REQUEST['id']);
        $this->_db->update('DELETE FROM tb_news_envio WHERE idEnvio = ' . $id);
        $this->_db->update('DELETE FROM tb_news_envio_grupo WHERE idEnvio = ' . $id);
        $this->_db->update('DELETE FROM tb_news_envio_conteudo WHERE idEnvio = ' . $id);
        header('Location: ' . $this->_url_raiz . 'admin/menu-news-envio');
    }

    public function iniciarEnvio() {
        $idEnvio = $_REQUEST['idEnvio'];
        $enviando = $this->_db->query(
                'SELECT COUNT(*) total FROM tb_news_envio_enviando ' .
                'WHERE idEnvio <> ' . $this->_db->clean($idEnvio));
        if ($enviando[0]['total']) {
            header('Location: ' . $this->_url_raiz . 'admin/pop-news-iniciar-erro?ajax=1');
        } else {
            header('Location: ' . $this->_url_raiz . 'admin/pop-news-iniciar?ajax=1&idEnvio=' . $idEnvio);
        }
        exit;
    }

    public function comecarEnvio() {
        $id = $_REQUEST['id'];
        if (!$id) {
            echo json_encode(array('status' => 0));
        } else {
            //verificando o envio
            $enviando = $this->_db->query('SELECT COUNT(*) total, tb_news_envio_enviando.* FROM tb_news_envio_enviando');
            if ($enviando[0]['total'] && $enviando[0]['idEnvio'] != $id) {
                //se o id que estou tentando fazer o envio nao for o que estou no momento enviando, isso � um erro
                echo json_encode(array('status' => 0));
            } else {
                if (!$enviando[0]['total']) {
                    //iniciando o envio
                    $this->_db->update($this->_db->insertQuery(array(
                                'idEnvio' => $id,
                                'limite' => $this->_limiteEnvioHora,
                                'dataCadastro' => date('Y-m-d H:i:s'),
                                'enviando' => 1, //para j� bloquear um outro envio
                                'posicao' => 0
                                    ), 'tb_news_envio_enviando'));
                    $this->enviarNews($id);
                } elseif ($enviando[0]['total'] && $enviando[0]['enviando'] == 1) {
                    //continua verificando ap�s um tempo se j� consigo continuar a enviar
                    echo json_encode(array('status' => 888));
                } elseif ($enviando[0]['total'] && $enviando[0]['enviando'] == 0) {
                    //continuando o envio
                    $this->enviarNews($this->_db->clean($id));
                } else {
                    //caso nao ocorra nenhuma das op�oes acima, isso � um erro
                    echo json_encode(array('status' => 0));
                }
            }
        }
    }

    public function enviarNews($idEnvio) {
        $this->_db->update($this->_db->updateQuery(array('enviando' => 1, 'dataAtualizada' => date('Y-m-d H:i:s')), 'tb_news_envio_enviando', 'idEnvio = ' . $idEnvio));

        //corpo do envio --------------------

        $newsLetter = $this->_db->query(
                'SELECT ee.*, e.idTipoConteudo FROM tb_news_envio_enviando ee ' .
                'INNER JOIN tb_news_envio e ON ee.idEnvio = e.idEnvio ' .
                'WHERE e.idEnvio = ' . $idEnvio . ' ' .
                'AND e.inAtivo = 1');

        //verificando se posso fazer o envio
        if (!$newsLetter) {
            $finalizou = true;
        } else {
            //montanto o html
            $html = $this->montarHtmlEnvio($newsLetter[0]['idEnvio']);

            $emails = $this->_db->query(
                    'SELECT email.* FROM tb_news_emails email ' .
                    'INNER JOIN tb_news_grupo_emails grupoemail ON email.idEmail = grupoemail.idEmail ' .
                    'INNER JOIN tb_news_grupo grupo ON grupoemail.idGrupo = grupo.idGrupo ' .
                    'INNER JOIN tb_news_envio_grupo grupoenvio ON grupoemail.idGrupo = grupoenvio.idGrupo ' .
                    'INNER JOIN tb_news_envio envio ON grupoenvio.idEnvio = envio.idEnvio ' .
                    'WHERE grupoenvio.idEnvio = ' . $idEnvio . ' AND email.inAtivo = 1 AND grupo.inAtivo = 1 AND envio.inAtivo = 1 ' .
                    'GROUP BY email.nmEmail LIMIT ' . $newsLetter[0]['posicao'] . ', ' . $newsLetter[0]['limite']
            );

            //se nao tenho e-mails, entao finalizou o envio
            if (!$emails) {
                $finalizou = true;
            } else {

                $emailFrom = $this->_db->query('					
							SELECT   nmEmailContato 
							FROM  vwcontatosite
							ORDER BY  inContatoPrincipal DESC
							LIMIT 1
					');

                $assunto = array(
                    '11' => 'Boletim',
                    '-1' => 'Clipping',
                    '4' => 'Informativo'
                );

                //tenho e-mails
                foreach ($emails as $email) {
                    try {
                        $trocas = array("{codEmail}" => base64_encode($email['idEmail']), "{emailUsuario}" => $email['nmEmail']);
                        $html = strtr($html, $trocas);
                        //fezendo o envio da news
                        $this->_mail->From = $emailFrom[0]['nmEmailContato'];
                        $this->_mail->FromName = $this->_geralConfig[0]['nmTituloSite'];
                        $this->_mail->AddAddress($email['nmEmail'], $email['nmNome']);
                        $this->_mail->IsHTML(true);
                        $this->_mail->CharSet = 'utf-8';
                        $this->_mail->Subject = $assunto[$newsLetter[0]['idTipoConteudo']];
                        $this->_mail->Body = $html;
                        $resultado = ($this->_mail->Send() ? 1 : 0);
                        $this->_mail->ClearAllRecipients();
                    } catch (Exception $e) {
                        //continua
                    }
                }
            }
        }
        //-----------------------------------
        //se finalizou retiro a mala da lista de envio
        if ($finalizou) {
            $this->_db->update('DELETE FROM tb_news_envio_enviando WHERE idEnvio = ' . $idEnvio);
            $this->_db->update('UPDATE tb_news_envio set inEnviado=1 WHERE idEnvio = ' . $idEnvio);
            echo json_encode(array('status' => '1'));
        } else {
            $this->_db->update(
                    'UPDATE tb_news_envio_enviando SET enviando = 0, posicao = (posicao+' . $this->_limiteEnvioHora . ') WHERE idEnvio = ' . $idEnvio);
            echo json_encode(array('status' => '888'));
        }
    }

    public function montarHtmlEnvio($idEnvio = null) {

        if (!$idEnvio) {
            return null;
        }

        $envio = $this->_db->query(
                'SELECT n.nmTituloAmigavel,e.*, e.idTipoConteudo as idTipoConteudo2 , c.* FROM tb_news_envio e ' .
                'INNER JOIN tb_news_envio_conteudo ec ON e.idEnvio = ec.idEnvio ' .
                'INNER JOIN vwconteudoarquivo c ON ec.idConteudo = c.idConteudo ' .
                'INNER JOIN tb_conteudo n ON n.idConteudo = c.idConteudo ' .
                'WHERE e.idEnvio = ' . $idEnvio);

        switch ($envio[0]['idTipoConteudo2']) {
            case 11:
                ob_start();
                $url_raiz = $this->_url_raiz;
                include $r = dirname(__FILE__) . '/../html/boletim.phtml';
                $html = ob_get_contents();
                ob_clean();
                break;

            case 4:
                ob_start();
                $url_raiz = $this->_url_raiz;
                include $r = dirname(__FILE__) . '/../html/informativo.phtml';
                $html = ob_get_contents();
                ob_clean();
                break;

            case -1:
                ob_start();
                $url_raiz = $this->_url_raiz;
                include $r = dirname(__FILE__) . '/../html/clipping.phtml';
                $html = ob_get_contents();
                ob_clean();
                break;

            default:
                $html = null;
                break;
        }

        return $html;
    }

    public function buscarConteudos() {
        $idTipoConteudo = $_REQUEST['idTipoConteudo'];
        if ($idTipoConteudo == '-1') {
            $where = ' AND idTipoConteudo IN(11,12,13,19)';
        } else {
            $where = ' AND idTipoConteudo=' . $this->_db->clean($idTipoConteudo);
        }

        $r = $this->_db->query($s = 'SELECT idConteudo, nmTituloConteudo, nmTipoConteudo FROM vwconteudoarquivo WHERE 1=1' . $where . ' ORDER BY dtDataConteudo DESC');
        $retorno = array();
        if ($r) {
            foreach ($r as $c) {
                $c['nmTituloConteudo'] = $c['nmTituloConteudo'];
                $c['nmTipoConteudo'] = $c['nmTipoConteudo'];
                $retorno[$c['idConteudo']] = $c;
            }
        }
        echo json_encode($retorno);
        exit;
    }

}

$act = new actNews(array(
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
        header('Location: ' . $url_raiz . 'admin/sis-erro-sistema');
    }
} else {
    header('Location: ' . $url_raiz . 'admin/sis-acesso-negado');
}
exit();
?>