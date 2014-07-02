<?php

/**
 *
 * Classe de gerenciamento da galeria
 */
class actNews {

    protected $_db;
    protected $_url_raiz;
    protected $_limiteEnvioHora = 100;
    protected $_geralConfig;
    protected $_qryRodape;
    protected $_mail;
    protected $_raiz;

    public function __construct(array $opcoes = array()) {
        $this->_db = $opcoes['db'];
        $this->_url_raiz = $opcoes['url_raiz'];
        $this->_geralConfig = $opcoes['geralConfig'];
        $this->_qryRodape = $opcoes['qryRodape'];
        $this->_mail = $opcoes['mail'];
        $this->_raiz = $opcoes['raiz'];
    }

    protected function _gerKeyDados() {
        $key = md5(uniqid());
        $_SESSION[$key] = array();
        return $key;
    }

    public function validarGaleria(array $dados = array(), $idGaleria = null) {
        $semErro = true;
        if (!strlen($dados['nmGaleria'])) {
            $semErro = false;
            $_SESSION['msgErro'] = 'Digite o nome da galeria.';
        } else {
            $g = $this->_db->select(
                    array('nmGaleria' => $dados['nmGaleria']), 'tb_galeria');

            if ($g && $g[0]['idGaleria'] != $idGaleria) {
                $semErro = false;
                $_SESSION['msgErro'] = 'Galeria já está cadastrada, favor cadastrar outra.';
            }
        }

        $arquivo = $_FILES['nmArquivo'];
        if ($arquivo['error'] === 0) {
            if (!in_array(strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION)), array('pdf'))) {
                $semErro = false;
                $_SESSION['msgErro'] = 'Erro no arquivo pdf, extensão inválida.';
            }
        }

        return $semErro;
    }

    public function cadastrarGaleria() {
        $dados = array(
            'nmGaleria' => $_POST['nmGaleria'],
            'nmLocal' => $_POST['nmLocal'],
            'inAtivo' => $_POST['inAtivo'],
            'inDestaque' => $_POST['inDestaque'],
            'dtDataGaleria' => converteData($_POST['dtDataGaleria']),
            'nmDescricao' => $_POST['nmDescricao'],
            'inPublicacao' => $_POST['inPublicacao'],
            'inVisibilidade' => $_POST['inVisibilidade'],
            'idSecao' => $_POST['idSecao'],
			'idCategoriaGaleria' => $_POST['nmCategoriaGaleria']
        );

        $_SESSION[$key = $this->_gerKeyDados()]['dados'] = $dados;
        if ($this->validarGaleria($dados)) {
            $this->_db->update($this->_db->insertQuery($dados, 'tb_galeria'));
            $id = mysql_insert_id();
            $this->_moveImgs($id);
            $_SESSION[$key]['dados']['nmArquivo'] = $this->_moverArquivo($id);

            if ($id) {
                $_SESSION[$key]['dados']['idGaleria'] = $id;
                $_SESSION['msg'] = 'Dados inseridos com sucesso!';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
        }
        redirecionar($this->_url_raiz . 'admin/cad-galeria?key=' . $key);
    }

    public function atualizarGaleria() {

        $key = $_POST['key'];
        if (!strlen($key)) {
            throw new Exception('Erro no na atualização.');
        }

        $id = $_SESSION[$key]['dados']['idGaleria'];
        $imagensS = $_SESSION[$key]['dados']['imagens'];

        $dados = array(
            'nmGaleria' => $_POST['nmGaleria'],
            'nmLocal' => $_POST['nmLocal'],
            'inAtivo' => $_POST['inAtivo'],
            'inDestaque' => $_POST['inDestaque'],
            'idSecao' => $_POST['idSecao'],
            'nmDescricao' => $_POST['nmDescricao'],
            'inPublicacao' => $_POST['inPublicacao'],
            'dtDataGaleria' => converteData($_POST['dtDataGaleria']),
            'inVisibilidade' => $_POST['inVisibilidade'],
			'idCategoriaGaleria' => $_POST['nmCategoriaGaleria'],
            'idGaleria' => $id);

        $_SESSION[$key]['dados'] = $dados;
        if ($this->validarGaleria($dados, $id)) {
            $resposta = $this->_db->update($this->_db->updateQuery($dados, 'tb_galeria', 'idGaleria = ' . $this->_db->clean($id)));
            $this->_moveImgs($id, $imagensS);
            $_SESSION[$key]['dados']['nmArquivo'] = $this->_moverArquivo($id);

            if ($resposta) {
                $_SESSION['msg'] = 'Dados atualizados com sucesso!';
            } else {
                $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
            }
        }

        redirecionar($this->_url_raiz . 'admin/cad-galeria?key=' . $key);
    }

    public function excluirGaleria() {
        $id = $_REQUEST['id'];
        if (!is_numeric($id)) {
            throw new Exception('Erro interno');
        }

        $imagens = $this->_db->select(array('idGaleria' => $id), 'tb_galeria_imagem');
        if ($imagens) {
            $f = new moveFile();
            foreach ($imagens as $im) {
                /* $filename1 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                  'enviados' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR .
                  $f->montarSubDir($id) . $im['nmImagem'];
                  $filename2 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                  'enviados' . DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR .
                  $f->montarSubDir($id) . $im['nmImagem'];
                  $filename3 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                  'enviados' . DIRECTORY_SEPARATOR . 'miniaturas' . DIRECTORY_SEPARATOR .
                  $f->montarSubDir($id) . $im['nmImagem']; */
                $filename4 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                        'enviados' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR .
                        $f->montarSubDir($id) . $im['nmImagem'];
                $filename5 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                        'enviados' . DIRECTORY_SEPARATOR . 'galeria' . DIRECTORY_SEPARATOR .
                        $f->montarSubDir($id) . $im['nmImagem'];
                /* $f->remover($filename1, $id);
                  $f->remover($filename2, $id);
                  $f->remover($filename3, $id); */
                $f->remover($filename4, $id);
                $f->remover($filename5, $id);
            }
        }

        $gal = $this->_db->query('SELECT nmArquivo FROM tb_galeria WHERE idGaleria = ' . $this->_db->clean($id));
        if ($gal[0]['nmArquivo']) {
            $f = new moveFile();
            $f->remover($this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR . $f->montarSubDir($id) . $gal[0]['nmArquivo']);
        }

        $this->_db->update('DELETE FROM tb_galeria WHERE idGaleria = ' . $id);
        $this->_db->update('DELETE FROM tb_galeria_imagem WHERE idGaleria = ' . $id);
        $_SESSION['msg'] = 'Item excluído com sucesso.';
        redirecionar($this->_url_raiz . 'admin/menu-galeria');
    }

    public function ativar() {
        $id = $_REQUEST['id'];
        if (!is_numeric($id)) {
            throw new Exception('Erro interno');
        }

        $r = $this->_db->update('UPDATE tb_galeria SET inAtivo=1 WHERE idGaleria=' . $this->_db->clean($id));
        if ($r) {
            $_SESSION['msg'] = 'Galeria ativada com sucesso!';
        } else {
            $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
        }

        redirecionar($this->_url_raiz . 'admin/menu-galeria');
    }

    public function desativar() {
        $id = $_REQUEST['id'];
        if (!is_numeric($id)) {
            throw new Exception('Erro interno');
        }

        $r = $this->_db->update('UPDATE tb_galeria SET inAtivo=0 WHERE idGaleria=' . $this->_db->clean($id));
        if ($r) {
            $_SESSION['msg'] = 'Galeria desativada com sucesso!';
        } else {
            $_SESSION['msgErro'] = 'Ocorreu um erro! Tente novamente ou contate o suporte.';
        }

        redirecionar($this->_url_raiz . 'admin/menu-galeria');
    }

    public function _moverArquivo($id = null) {
        $arquivo = $_FILES['nmArquivo'];
        if ($arquivo['error'] === 0) {

            $f = new moveFile();
            $nmArquivo = $f->move(
                    $arquivo, $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'file', $id
            );

            if ($nmArquivo) {
                $gal = $this->_db->query('SELECT nmArquivo FROM tb_galeria WHERE idGaleria = ' . $this->_db->clean($id));
                $f->remover($this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR . $f->montarSubDir($id) . $gal[0]['nmArquivo']);
                $this->_db->update($this->_db->updateQuery(array('nmArquivo' => $nmArquivo), 'tb_galeria', 'idGaleria = ' . $this->_db->clean($id)));

                return $nmArquivo;
            }
        }

        return null;
    }

    protected function _moveImgs($id = null, $imagensS = array()) {
        $f = new moveFile();
        $imagens = array();
        //$orderm = 0;
        foreach ($_POST['statusImagem'] as $i => $statusImagem) {
            $imagem = array(
                'idGaleria' => $id,
                'nmLegenda' => $_POST['legenda'][$i],
                'inCapa' => (($i == $_POST['capa']) ? true : false),
                'inOrdem' => $_POST['inOrdem'][$i]
            );

            switch ($statusImagem) {
                case 'i'://inserir

                    $img = array(
                        'name' => $_FILES['imagem']['name'][$i],
                        'type' => $_FILES['imagem']['type'][$i],
                        'tmp_name' => $_FILES['imagem']['tmp_name'][$i],
                        'error' => $_FILES['imagem']['error'][$i],
                        'size' => $_FILES['imagem']['size'][$i]
                    );

                    if ($img['error'] <= 0) {
                        $nmImagem = $f->move(
                                $img, $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'temp'
                        );
                        $opcoes = array(
                            'image_versions' => array(
                                /* 'image' => array(
                                  'upload_dir' => $this->_raiz . '/arquivos/enviados/image/' . $id . '/',
                                  'upload_url' => $this->_url_raiz . '/arquivos/enviados/image/' . $id . '/',
                                  'max_width' => 640,
                                  'max_height' => 480,
                                  'crop' => false
                                  ), */
                                'galeria' => array(
                                    'upload_dir' => $this->_raiz . '/arquivos/enviados/galeria/' . $id . '/',
                                    'upload_url' => $this->_url_raiz . '/arquivos/enviados/galeria/' . $id . '/',
                                    'max_width' => 800,
                                    'max_height' => 600,
                                    'crop' => false
                                ),
                                /* 'home' => array(
                                  'upload_dir' => $this->_raiz . '/arquivos/enviados/home/' . $id . '/',
                                  'upload_url' => $this->_url_raiz . '/arquivos/enviados/home/' . $id . '/',
                                  'max_width' => 320,
                                  'max_height' => 240,
                                  'crop' => true
                                  ),
                                  'miniatura' => array(
                                  'upload_dir' => $this->_raiz . '/arquivos/enviados/miniaturas/' . $id . '/',
                                  'upload_url' => $this->_url_raiz . '/arquivos/enviados/miniaturas/' . $id . '/',
                                  'max_width' => 138,
                                  'max_height' => 138,
                                  'crop' => true
                                  ), */
                                'thumbnail' => array(
                                    'upload_dir' => $this->_raiz . '/arquivos/enviados/thumbnails/' . $id . '/',
                                    'upload_url' => $this->_url_raiz . '/arquivos/enviados/thumbnails/' . $id . '/',
                                    'max_width' => 80,
                                    'max_height' => 80,
                                    'crop' => true
                                )
                            )
                        );
                        foreach ($opcoes['image_versions'] as $version => $options) {
                            $f->versao_image($nmImagem, $options, $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'temp/');
                        }
                        if ($nmImagem) {
                            //remove temp/
                            $filenameUnlink = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                                    'enviados' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . $nmImagem;
                            $f->remover($filenameUnlink);
                            $this->_db->update(
                                    $this->_db->insertQuery($imagem + array('nmImagem' => $nmImagem), 'tb_galeria_imagem'));
                        }
                    }

                    break;

                case 'a'://alterar

                    $img = array(
                        'name' => $_FILES['imagem']['name'][$i],
                        'type' => $_FILES['imagem']['type'][$i],
                        'tmp_name' => $_FILES['imagem']['tmp_name'][$i],
                        'error' => $_FILES['imagem']['error'][$i],
                        'size' => $_FILES['imagem']['size'][$i]
                    );

                    if ($img['error'] <= 0) {
                        $nmImagem = $f->move(
                                $img, $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'temp'
                        );
                        $opcoes = array(
                            'image_versions' => array(
                                /* 'image' => array(
                                  'upload_dir' => $this->_raiz . '/arquivos/enviados/image/' . $id . '/',
                                  'upload_url' => $this->_url_raiz . '/arquivos/enviados/image/' . $id . '/',
                                  'max_width' => 640,
                                  'max_height' => 480,
                                  'crop' => false
                                  ), */
                                'galeria' => array(
                                    'upload_dir' => $this->_raiz . '/arquivos/enviados/galeria/' . $id . '/',
                                    'upload_url' => $this->_url_raiz . '/arquivos/enviados/galeria/' . $id . '/',
                                    'max_width' => 800,
                                    'max_height' => 600,
                                    'crop' => false
                                ),
                                /* 'home' => array(
                                  'upload_dir' => $this->_raiz . '/arquivos/enviados/home/' . $id . '/',
                                  'upload_url' => $this->_url_raiz . '/arquivos/enviados/home/' . $id . '/',
                                  'max_width' => 218,
                                  'max_height' => 218,
                                  'crop' => true
                                  ),
                                  'miniatura' => array(
                                  'upload_dir' => $this->_raiz . '/arquivos/enviados/miniaturas/' . $id . '/',
                                  'upload_url' => $this->_url_raiz . '/arquivos/enviados/miniaturas/' . $id . '/',
                                  'max_width' => 200,
                                  'max_height' => 150,
                                  'crop' => true
                                  ), */
                                'thumbnail' => array(
                                    'upload_dir' => $this->_raiz . '/arquivos/enviados/thumbnails/' . $id . '/',
                                    'upload_url' => $this->_url_raiz . '/arquivos/enviados/thumbnails/' . $id . '/',
                                    'max_width' => 80,
                                    'max_height' => 80,
                                    'crop' => true
                                )
                            )
                        );
                        foreach ($opcoes['image_versions'] as $version => $options) {
                            $f->versao_image($nmImagem, $options, $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'temp/');
                        }
                        if ($nmImagem) {
                            //remove temp/
                            $filenameUnlink = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                                    'enviados' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . $nmImagem;
                            $f->remover($filenameUnlink);
                            /* $filename1 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                              'enviados' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR .
                              $f->montarSubDir($id) . $imagensS[$i]['nmImagem'];
                              $filename2 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                              'enviados' . DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR .
                              $f->montarSubDir($id) . $imagensS[$i]['nmImagem'];
                              $filename3 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                              'enviados' . DIRECTORY_SEPARATOR . 'miniaturas' . DIRECTORY_SEPARATOR .
                              $f->montarSubDir($id) . $imagensS[$i]['nmImagem']; */
                            $filename4 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                                    'enviados' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR .
                                    $f->montarSubDir($id) . $imagensS[$i]['nmImagem'];
                            $filename5 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR .
                                    'enviados' . DIRECTORY_SEPARATOR . 'galeria' . DIRECTORY_SEPARATOR .
                                    $f->montarSubDir($id) . $imagensS[$i]['nmImagem'];

                            /* $f->remover($filename1, $id);
                              $f->remover($filename2, $id);
                              $f->remover($filename3, $id); */
                            $f->remover($filename4, $id);
                            $f->remover($filename5, $id);
                            $imagem['nmImagem'] = $nmImagem;
                        }
                    }

                    $this->_db->update(
                            $this->_db->updateQuery($imagem, 'tb_galeria_imagem', 'idImagem=' . $imagensS[$i]['idImagem']));

                    break;

                case 'd':

                    /* $filename1 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $f->montarSubDir($id) . $imagensS[$i]['nmImagem'];
                      $filename2 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR . $f->montarSubDir($id) . $imagensS[$i]['nmImagem'];
                      $filename3 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'miniaturas' . DIRECTORY_SEPARATOR . $f->montarSubDir($id) . $imagensS[$i]['nmImagem']; */
                    $filename4 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR . $f->montarSubDir($id) . $imagensS[$i]['nmImagem'];
                    $filename5 = $this->_raiz . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'galeria' . DIRECTORY_SEPARATOR . $f->montarSubDir($id) . $imagensS[$i]['nmImagem'];
                    /* $f->remover($filename1, $id);
                      $f->remover($filename2, $id);
                      $f->remover($filename3, $id); */
                    $f->remover($filename4, $id);
                    $f->remover($filename5, $id);
                    $this->_db->update('DELETE FROM tb_galeria_imagem WHERE idImagem=' . $imagensS[$i]['idImagem']);

                    break;

                default:break;
            }

            unset($img);
            unset($nmImagem);
            unset($filenam);
        }
    }

}

$act = new actNews(array(
    'db' => $db,
    'url_raiz' => $url_raiz,
    'geralConfig' => $geralConfig,
    'qryRodape' => $qryRodape,
    'mail' => $mail,
    'raiz' => $raiz
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