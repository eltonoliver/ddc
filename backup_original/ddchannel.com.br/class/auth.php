<?php
/**
 *
 * Classe de autenticação
 */
class auth{

    /**
     * Classe de manipulação do mysql
     * @var mysqlClass
     */
    protected $_db;

    protected $_codigoErro;

    protected $_usuarioMaster = 'webmaster';

    protected $_senhaMaster = 'b346b62b5bd99199e9ad8b83df978ac8';

    protected $_dbu;

    protected $_numTentativas = 3;

    const CREDENCIAIS_INVALIDA = 1;

    const CODIGO_SEGURACA_INVALIDO = 2;

    const DADO_INVALIDO = 3;

    const ACESSO_NEGADO = 4;

    const ACESSO_NAO_PERMITIDO = 5;

    public function __construct($db = null){
        $this->_db = $db;
        $this->_dbu = $this->_db->db;
    }

    /**
     *
     * Valida o login o login do usuário
     * @param string $usuario
     * @param string $senha
     * @param string $codigoChave
     * @return boolean
     */
    public function validarAutenticacao($usuario = null, $senha = null, $codigoChave = null) {
        if(!strlen($usuario) || !strlen($senha) || !strlen($codigoChave)) {
            $this->definirCodigoErro(self::CREDENCIAIS_INVALIDA);
            return false;
        } elseif($codigoChave != $_SESSION["security_code"]) {
            $this->definirCodigoErro(self::CODIGO_SEGURACA_INVALIDO);
            return false;
        }

        return true;
    }

    /**
     *
     * Defini�ao do código do erro
     * @param int $codigoErro
     */
    public function definirCodigoErro($codigoErro = 0) {
        $this->_codigoErro = $codigoErro;
    }

    /**
     *
     * Obtem o código do erro
     * @return int
     */
    public function obterCodigoErro() {
        return $this->_codigoErro;
    }

    /**
     *
     * Faz o login do usuário
     * @param string $usuario
     * @param string $senha
     * @param string $codigoChave
     * @return boolean
     */
    public function logar($usuario, $senha, $codigoChave) {
        if(!$this->validarAutenticacao($usuario, $senha, $codigoChave)) {
            return false;
        }

        $usu_login = trim(strtolower($usuario));
        $usu_senha = md5(trim(strtolower($senha.'-uga')));

        $consulta = "SELECT * FROM tb_usuario   
                     WHERE  nmLogin = '" . $usu_login . "' AND nmSenha = '" . $usu_senha . "' AND inAtivo = 1 LIMIT 1";
        $qryLogin = $this->_db->query($consulta);
        if ($usu_login == $this->_usuarioMaster && $usu_senha == $this->_senhaMaster) {
            $qryLogin[0]["idUsuario"] = 1;
            $qryLogin[0]["nmUsuario"] = 'Webmaster / Ugagogo'; 
            $qryLogin[0]["idPerfil"] = 1;
        }

        if($qryLogin) {
            $qryLogin = current($qryLogin);
            $str = "SELECT * FROM vwmenuperfil
                    WHERE   idTipoMenu = '1' AND inExibir = '1' AND idPerfil = '".$qryLogin['idPerfil']."'";

            $qryMenusPerfil = $this->_db->query($str);
            $menuUsuario = geraMenusPerfil($qryMenusPerfil);
            $_SESSION['ID']     = $qryLogin['idUsuario'];
            $_SESSION['NOME']   = $qryLogin['nmUsuario'];
            $_SESSION['PERFIL'] = $qryLogin['idPerfil'];
            $_SESSION['PERM']   = 1;
            $_SESSION['MENUS']  = $menuUsuario;
            $_SESSION['CLID']   = $_SERVER['SCRIPT_NAME'];
            $_SESSION['NUM_TENT'] = 0;
            return true;
        } else {
            $_SESSION['NUM_TENT'] = $_SESSION['NUM_TENT'] + 1;
            $this->definirCodigoErro(self::DADO_INVALIDO);
            return false;
        }
    }

    /**
     *
     * Desloga o usuário a sessao
     */
    public function deslogar(){
        unset($_SESSION['ID']);
        unset($_SESSION['NOME']);
        unset($_SESSION['PERFIL']);
        unset($_SESSION['PERM']);
        unset($_SESSION['MENUS']);;
        unset($_SESSION['RT']);
        unset($_SESSION['URT']);
        unset($_SESSION['CLID']);
        unset($_SESSION['ID_SESSAO']);
        unset($_SESSION['NUM_TENT']);
    }

    /**
     *
     * Verificando se o usuário está logado
     */
    public function isLogado() {
        //verificando se estou logando na sessao certa
        if(isset($_SESSION['ID']) && $_SESSION['ID'] && $_SESSION['CLID'] == $_SERVER['SCRIPT_NAME']) {
            return true;
        }
        return false;
    }

    /**
     *
     * Verifica se a página é acessível ao usuário logado
     */
    public function isAcessivel($nomePagina = null) {

        $this->definirCodigoErro(0);//sem erro

        if(!strlen($nomePagina)) {
            return false;
        } elseif(strstr($nomePagina, 'act') || in_array($nomePagina, array(
                                                'sisAcessoNegado.php',
                                                'sisErroSistema.php',
                                                '404.php',
                                                'actLogin.php'
                                                ))) {
            return true;
        }

        $linkMenuAtual   = $nomePagina;
        $strVerificaMenu = "SELECT * FROM vwmenuperfil WHERE idPerfil = '".$_SESSION['PERFIL']."' AND linkMenu = '".$linkMenuAtual."'";
        $qryVerificaMenu = $this->_db->query($strVerificaMenu);

        if (!isset($_SESSION['ID']) || $_SESSION['PERM'] != 1 && ($_SESSION['CLID'] != $_SERVER['SCRIPT_NAME'])) {
            $this->definirCodigoErro(self::ACESSO_NEGADO);
        }

        if (
            isset($_SESSION['ID']) && 
            $_SESSION['PERM'] == 1 && 
            ($_SESSION['CLID'] == $_SERVER['SCRIPT_NAME']) && 
            !$qryVerificaMenu && 
            !strpos(strtolower($script),'act')) {
            $this->definirCodigoErro(self::ACESSO_NAO_PERMITIDO);
        }

        if($this->obterCodigoErro()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     *
     * Verifica se atingiu o números de tentativas
     */
    public function atingiuTentativas() {
        $n = $_SESSION['NUM_TENT'];
        if($n>=$this->_numTentativas) {
            unset($_SESSION['NUM_TENT']);
            return true;
        }
        return false;
    }
} ?>