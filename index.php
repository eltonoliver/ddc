<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header("WWW-Authenticate: Basic realm=\"Private Area\"");
    header("HTTP/1.0 401 Unauthorized");
    include('start.html');
    //print "Sorry - you need valid credentials to be granted access!\n";
    exit;
} else {
    if (($_SERVER['PHP_AUTH_USER'] == 'ddc') && ($_SERVER['PHP_AUTH_PW'] == '123')) {
        //print "Welcome to the private area!";
    } else {
        header("WWW-Authenticate: Basic realm=\"Private Area\"");
        header("HTTP/1.0 401 Unauthorized");
        //print "Sorry - you need valid credentials to be granted access!\n";
        include('start.html');
        exit;
    }
}
?>
<?php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN'); // or SAMEORIGIN
ini_set('session.cookie_httponly', true);
ob_start();
session_start();

class url_redirecionar {

    protected $uri;
    protected $script_name;
    protected $file_php;
    protected $_path_controller = '';
    protected $_url_raiz;

    public function __construct($url_raiz = null) {
        $this->motar_dir_arquivo($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']);
        $this->_url_raiz = $url_raiz;
    }

    public function motar_dir_arquivo($REQUEST_URI, $SCRIPT_NAME) {
        $this->uri = $REQUEST_URI;
        $this->script_name = $SCRIPT_NAME;
        if (!strstr($this->uri, '.php')) {
            $this->uri = ($p = strpos($this->uri, '?')) ? substr($this->uri, 0, $p) : $this->uri;
            $s = substr($this->script_name, 0, strrpos($this->script_name, '/'));
            if (strlen($s)) {
                $pos = strpos($this->uri, $s . '/');
                if ($pos !== false) {
                    $newstring = substr_replace($this->uri, '', $pos, strlen($s . '/'));
                }
                $c = explode('/', $newstring);
            } else {
                $c = explode('/', substr($this->uri, 1));
            }
            if (strstr($c[0], '.php')) {
                $this->file_php = '404.php';
            } else {
                if ($c[0] && $c[0] == 'controller') {
                    $this->file_php = $c[1] ? $c[1] . '.php' : '404.php';
                    if ($c[1]) {
                        $this->_path_controller = 'controller/';
                    }
                    array_splice($c, 0, 2);
                } else {
                    $this->file_php = $c[0] ? $c[0] . '.php' : 'principal.php';
                    //array_splice($c, 0, 1);
                }
            }
            $total = count($c);
            if ($total) {
                if ($total == 2)
                    $c[0] = "titulo";
                else
                    array_splice($c, 0, 1);
                for ($i = 0; $i < $total; $i++) {
                    $_GET[$c[$i]] = isset($c[++$i]) ? $c[$i] : null;
                }
                $_REQUEST = array_merge($_REQUEST, $_GET);
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

    public function urlAtualCompleta() {
        return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

}

include_once('application.php');

//limpando todos os dados com XSS Injection------------------------
require_once $raiz . 'class' . DIRECTORY_SEPARATOR . 'HTMLPurifier' . DIRECTORY_SEPARATOR . 'HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$config->set('Core.Encoding', 'UTF-8');
$config->set('Cache', 'DefinitionImpl', null);
$config->set('HTML.SafeIframe', true);
$config->set('URI.SafeIframeRegexp', '%^http://(www.youtube.|player.vimeo.|maps.google.|www.slideshare.)%');

$purifier = new HTMLPurifier($config);

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
            $_POST[$k] = $purifier->purify($v);
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
$url_redirecionar = new url_redirecionar($url_raiz);
$urlAtualCompleta = $url_redirecionar->urlAtualCompleta();
$defaultPathModulo = 'modulos' . DIRECTORY_SEPARATOR;
/* Idiomas */
/*
  if ($_GET["lang"] && !vazio($_GET["lang"])) {
  $_SESSION["lang"] = trim($_GET["lang"]);
  }
  if (!isset($_SESSION["lang"])) {
  if (strtolower(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2)) != "pt")
  $def_lang = 'en-us';
  else
  $def_lang = 'pt-br';
  $_SESSION["lang"] = $def_lang;
  $_SESSION["tLang"] = parse_ini_file("lang/" . $def_lang . ".ini.php", true);
  header("Location: ./intro");
  } else {
  $def_lang = $_SESSION["lang"];
  $_SESSION["tLang"] = parse_ini_file("lang/" . $def_lang . ".ini.php", true);
  }
  $tLang = $_SESSION["tLang"];
 */
/* Idiomas */
if ($d = $url_redirecionar->obter_path_controller()) {
    require $defaultPathModulo . $d . ($url_redirecionar->obter_file_php());
    exit;
}
$id = (int) $_GET["id"];
$titulo = mysql_real_escape_string($_GET['titulo']);
include 'header.php';
$ajax = ((isset($_REQUEST['ajax']) && $_REQUEST['ajax']) ? true : false);
if (!$ajax):
    $_SESSION['incluirCss'] = true;
    //include 'header.php';
    ?>
    <body>
        <!-- Facebook Like !-->
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=698677026827221";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <div class="old-browser">
            Você está usando um navegador em uma versão muito antiga.
            <a target="_blank" href="http://browsehappy.com/">Atualize-se!</a>
        </div>
        <?php
        include 'topo.php';
        ?>
        <section class="content" role="main">
            <div class="cf">
                <div class="fl cf">
                    <div id="loadingAjax">
                    <?php endif; ?>
                    <?php
                    try {
                        if (file_exists($defaultPathModulo . $url_redirecionar->obter_file_php())) {
                            if ($arquivoModulo != 'principal' and !$ajax) {
                                include $defaultPathModulo . 'newsletter.php';
                            }
                            include $defaultPathModulo . $url_redirecionar->obter_file_php();
                        } else {
                            include $defaultPathModulo . '404.php';
                        }
                    } catch (Exception $e) {
                        include $defaultPathModulo . '404.php';
                    }
                    ?>
<?php if (!$ajax): ?>
                    </div>
                </div>
                <?php
                include $defaultPathModulo . 'lateralDireita.php';
                ?>
                <a class="to-top" href="#to-top"></a>
            </div>
        </section>
        <?php
        include 'rodape.php';
    endif;
    include 'scripts-rodape.php';
    if (!$ajax):
        ?>
    </body>
    <script>
    <?php if (!vazio($geralConfig[0]["nmGoogleAnalytics"])) { ?>
        <?php echo stripslashes($geralConfig[0]["nmGoogleAnalytics"]); ?>
    <?php } ?>
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://www.ddchannel.com.br/js/cycle.js"></script>
    <script type="text/javascript">
        $(function(){  
                
              $('#acervovideos').cycle({ timeout:  -600 });
              $('#acervohistorico').cycle({ timeout:  -600 });


        });
    </script>
    </html>
    <?php
endif;
ob_end_flush();
?>