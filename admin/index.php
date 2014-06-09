<?php
# Informa qual o conjunto de caracteres será usado.
//header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN'); // or SAMEORIGIN
ini_set('session.cookie_httponly', true);

ob_start();
$sess_name = session_name();
if (session_start()) {
    setcookie($sess_name, session_id(), null, '/', null, null, true);
    session_regenerate_id();
}

class url_redirecionar {

    protected $uri;
    protected $script_name;
    protected $file_php;
    protected $_path_controller = '';

    public function __construct() {
        $this->motar_dir_arquivo($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']);
    }

    public function motar_dir_arquivo($REQUEST_URI, $SCRIPT_NAME) {
        $this->uri = $REQUEST_URI;
        $this->script_name = $SCRIPT_NAME;

        if (!strstr($this->uri, '.php')) {

            $this->uri = ($p = strpos($this->uri, '?')) ? substr($this->uri, 0, $p) : $this->uri;
            $c = explode('/', str_replace(substr($this->script_name, 0, strrpos($this->script_name, '/') + 1), '', $this->uri));
            if (strstr($c[0], '.php')) {
                $this->file_php = '404.php';
            } else {
                if ($c[0] && $c[0] == 'controller') {
                    $this->file_php = $c[1] ? $c[1] . '.php' : '404.php';
                    if ($c[1]) {
                        $this->_path_controller = 'controller' . DIRECTORY_SEPARATOR;
                    }
                } else {
                    $this->file_php = $c[0] ? $c[0] . '.php' : 'admin.php';
                }
            }
        } else {
            $this->file_php = '404.php';
        }
    }

    public function obter_path_controller() {
        return $this->_path_controller;
    }

    /**
     *
     * Obtem o arquivo identificado
     * @return string
     */
    public function obter_file_php() {
        //$f = lcfirst(implode('', array_map('ucfirst', explode('-', $this->file_php))));
        $f = (implode('', array_map('ucfirst', explode('-', $this->file_php))));
        $f{0} = strtolower($f{0});
        return $f;
    }

    /**
     *
     * Faz a inclusao do arquivo identificado
     * @return string
     */
    public function include_file_php() {
        ob_start();
        if (file_exists($this->obter_file_php())) {
            require($this->obter_file_php());
        } else {
            require('404.php');
        }
        return ob_get_clean();
    }

}

include_once ('../application.php');

//limpando todos os dados com XSS Injection------------------------
require_once $raiz . 'class' . DIRECTORY_SEPARATOR . 'HTMLPurifier' . DIRECTORY_SEPARATOR . 'HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$config->set('Core.Encoding', 'UTF-8');
$config->set('Cache', 'DefinitionImpl', null);
$config->set('HTML.SafeIframe', true);
$config->set('URI.SafeIframeRegexp', '%^http://(www.youtube.|player.vimeo.|maps.google.|www.slideshare.)%');

$purifier = new HTMLPurifier($config);

//Decripta dados seguros de formul�rios
if (isset($_POST['jqckval'])) {
    $chave = $_POST['jqckval'];
    $aUtil->arrayDecript($_POST, $fn, $chave);
    $_REQUEST["acao"] = $_POST["acao"];
}


if ($_REQUEST) {
    foreach ($_REQUEST as $k => $v) {
        if (!is_array($v)) {
            $_REQUEST[$k] = $purifier->purify($v);
        }
    }
}

if ($_POST) {
    foreach ($_POST as $k => $v) {
        if (!is_array($v)) {
            if ($k != 'nmConteudo' and $k != 'nmGoogleAnalytics' and $k != 'nmCodigoMaps') {
                $_POST[$k] = $purifier->purify($v);
            }
        }
    }
}

if ($_GET) {
    foreach ($_GET as $k => $v) {
        if (!is_array($v)) {
            $_GET[$k] = $purifier->purify($v);
        }
    }
}
//-----------------------------------------------------------------


$url_redirecionar = new url_redirecionar();
$defaultPathModulo = 'modulos' . DIRECTORY_SEPARATOR;
$d = $url_redirecionar->obter_path_controller();
$arquivoNome = $url_redirecionar->obter_file_php();


if (!file_exists($defaultPathModulo . $d . $arquivoNome)) {
    //verificando se tenho o p�gina a ser acessada
    header('Location: ' . $url_raiz . 'admin/404' . (($_REQUEST['ajax']) ? '?ajax=1' : ''));
    exit;
} else {
    if (!$auth->isLogado() && !in_array($arquivoNome, array(
                'login.php',
                'loginAlterarSenha.php',
                'loginEsqueceuSenha.php',
                'actLogin.php'
            ))) {
        //se nao estou logado, verifico se tenho livre acesso as p�ginas
        header('Location: ' . $url_raiz . 'admin/login');
        exit;
    } elseif ($auth->isLogado() && in_array($arquivoNome, array('login.php'))) {
        //se estou logado, verifico se estou acessando p�gina que somente vejo quando nao estou logado
        header('Location: ' . $url_raiz . 'admin/admin');
        exit;
    } elseif ($auth->isLogado() && !$auth->isAcessivel($arquivoNome)) {
        //se estou logado, verifico se tenho acessa a p�gina solicitada es se nao est� numa de livre acesso
        header('Location: ' . $url_raiz . 'admin/sis-acesso-negado');
        exit;
    }
}

if ($d = $url_redirecionar->obter_path_controller()) {
    require $defaultPathModulo . $d . ($url_redirecionar->obter_file_php());
    exit;
}

//se tiver vindo de uma refer�ncia fora do site n�o deixo passar
if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
    $_SESSION['referer'] = $_SESSION['current_page'];
    $_SESSION['current_page'] = $urlAtual;
    $_SERVER['HTTP_REFERER'] = $_SESSION['referer'];
}
if ((GetDomain($_SERVER['HTTP_REFERER']) != GetDomain($url_raiz) && strlen($_SERVER['HTTP_REFERER'])) or (!strlen($_SERVER['HTTP_REFERER']) and $arquivoNome != "login.php")) {
    redirecionar($url_raiz . 'admin/controller/act-login?acao=deslogando');
}

$ajax = ((isset($_REQUEST['ajax']) && $_REQUEST['ajax']) ? true : false);
if (!$ajax):
    include('header.php');
    ?>
    <body>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <?php if (!$_REQUEST['noTopoRodape']) include('topo.php'); ?>
                        <tr>
                            <td class="conteudo" valign="top" colspan="2" height="500">
                                <?php
                            endif;
                            try {
                                if (file_exists($defaultPathModulo . $url_redirecionar->obter_file_php())) {
                                    require ($defaultPathModulo . $url_redirecionar->obter_file_php());
                                } else {
                                    require($defaultPathModulo . '404.php');
                                }
                            } catch (Exception $e) {
                                require($defaultPathModulo . 'sisErroSistema.php');
                            }
                            if (!$ajax):
                                ?>
                            </td>
                        </tr>
                        <?php if (!$_REQUEST['noTopoRodape']) include('rodape.php'); ?>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    <!--
        CMS System powered by Ugadmin v2.0.0.3
        More: http://www.ugagogo.com.br/softwares/ugadmin
    -->
    <?php
endif;
ob_end_flush();
?>