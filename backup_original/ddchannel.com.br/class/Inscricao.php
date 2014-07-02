<?php
class Inscricao {

    protected $_db;
    private $dadosInscricao;
    private $idPromocao;

    public function __construct($db = null, $idPromocao = 0, $dadosInscricao = Array()) {
        $this->_db = $db;
        $this->idPromocao = $idPromocao;
        $this->dadosInscricao = $dadosInscricao;
        $trans = array("." => "", "-" => "");
        $this->dadosInscricao['nrCPF'] = strtr($this->dadosInscricao['nrCPF'], $trans);
        $arrayData = explode("/", $this->dadosInscricao['dataNascimento']);
        $this->dadosInscricao['dataNascimento'] = $arrayData[2] . "-" . $arrayData[1] . "-" . $arrayData[0];
        $this->dadosInscricao['nmEmail'] = trim(strtolower($this->dadosInscricao['nmEmail']));
    }

    private function validarCPF($cpf) {
        //VERIFICA SE O QUE FOI INFORMADO É NÚMERO
        if (!is_numeric($cpf)) {
            $status = false;
        } else {
            //VERIFICA
            if (($cpf == '11111111111') || ($cpf == '22222222222') || ($cpf == '33333333333') || ($cpf == '44444444444') || ($cpf == '55555555555') || ($cpf == '66666666666') || ($cpf == '77777777777') || ($cpf == '88888888888') || ($cpf == '99999999999') || ($cpf == '00000000000')) {
                $status = false;
            } else {
                //PEGA O DIGITO VERIFICADOR
                $dv_informado = substr($cpf, 9, 2);
                for ($i = 0; $i <= 8; $i++) {
                    $digito[$i] = substr($cpf, $i, 1);
                }
                //CALCULA O VALOR DO 10º DIGITO DE VERIFICAÇÃO
                $posicao = 10;
                $soma = 0;
                for ($i = 0; $i <= 8; $i++) {
                    $soma = $soma + $digito[$i] * $posicao;
                    $posicao = $posicao - 1;
                }
                $digito[9] = $soma % 11;
                if ($digito[9] < 2) {
                    $digito[9] = 0;
                } else {
                    $digito[9] = 11 - $digito[9];
                }
                //CALCULA O VALOR DO 11º DIGITO DE VERIFICAÇÃO
                $posicao = 11;
                $soma = 0;
                for ($i = 0; $i <= 9; $i++) {
                    $soma = $soma + $digito[$i] * $posicao;
                    $posicao = $posicao - 1;
                }
                $digito[10] = $soma % 11;
                if ($digito[10] < 2) {
                    $digito[10] = 0;
                } else {
                    $digito[10] = 11 - $digito[10];
                }
                //VERIFICA SE O DV CALCULADO É IGUAL AO INFORMADO
                $dv = $digito[9] * 10 + $digito[10];
                if ($dv != $dv_informado) {
                    $status = false;
                }
                else
                    $status = true;
            }//FECHA ELSE
        }//FECHA ELSE(is_numeric)
        return $status;
    }

    private function validarData($date) {
        $data = explode("-", $date);
        return checkdate($data[1], $data[2], $data[0]);
    }

    private function validarEmail($str_mail) {
        if (eregi("^[-_a-z0-9]+(\.[-_a-z0-9]+)*\@([-a-z0-9]+\.)*([a-z]{2,4})$", $str_mail)) {
            $dns_mail = explode("@", $str_mail);
            if (checkdnsrr($dns_mail[1])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function validarDados() {
        foreach ($this->dadosInscricao as $dado) {
            if ($dado == "") {
                $_SESSION['msgErro'] = "Preencha todos os campos";
                return false;
            }
        }
        if (!$this->validarCPF($this->dadosInscricao['nrCPF'])) {
            $_SESSION['msgErro'] = "CPF Inválido!";
            return false;
        } elseif (!$this->validarData($this->dadosInscricao['dataNascimento'])) {
            $_SESSION['msgErro'] = "Data inválida!";
            return false;
        } elseif (!$this->validarEmail($this->dadosInscricao['nmEmail'])) {
            $_SESSION['msgErro'] = "E-mail inválido!";
            return false;
        }
        return true;
    }

    public function verificarInscricao() {
        $nInscricao = $this->_db->query("select idInscricao from tb_inscricao where nrCPF=" . $this->dadosInscricao['nrCPF'] . " and idPromocao=" . $this->idPromocao);
        if (count($nInscricao) > 0) {
            $_SESSION['msgErro'] = "Usuário já inscrito nesta promoção!";
            return false;
        } else {
            return true;
        }
    }

    public function Inscrever() {
        $tipoPromo = $this->_db->query("select idCategoria from tb_conteudo where idConteudo=" . $this->idPromocao);
        if ($tipoPromo[0]['idCategoria'] == 73) {
            if (strlen($_FILES['foto']['tmp_name']) > 0) {
                $caminho = $path . 'arquivos' . DIRECTORY_SEPARATOR . 'enviados' . DIRECTORY_SEPARATOR . 'promocoes' . DIRECTORY_SEPARATOR;
                $arquivo['name'] = strtolower($_FILES['foto']['name']);
                $arquivo['type'] = strtolower($_FILES['foto']['type']);
                $arquivo['tmp_name'] = $_FILES['foto']['tmp_name'];
                $arquivo['error'] = $_FILES['foto']['error'];
                $arquivo['size'] = $_FILES['foto']['size'] / 1000;
                $extensoes = "jpg,jpeg,png,gif";
                $up = new upload();
                $up->setCaminho($caminho);
                $up->setArquivo($arquivo);
                $up->setTamanho(12428800); //até 50MB
                $up->setExtensoes($extensoes);
                $up->setRenomear(1);
                $retornoFoto = $up->enviarArquivo();
                if (strlen($retornoFoto['erro'])) {
                    $_SESSION['msgErro'] = $retornoFoto['erro'];
                    return false;
                } else {
                    $this->dadosInscricao['nmObjeto'] = $retornoFoto['nome_arquivo'];
                }
            } else {
                $_SESSION['msgErro'] = "Envie uma foto!";
                return false;
            }
        }
        $queryInsert = $this->_db->insertQuery($this->dadosInscricao, 'tb_inscricao');
        $insere = $this->_db->update($queryInsert);
        if ($insere) {
            //$idInscricao = mysql_insert_id();
            $_SESSION['msg'] = 'Inscrição realizada com sucesso. Aguarde o sorteio. Boa sorte!';
            return true;
        } else {
            $_SESSION['msgErro'] = "Erro na inscrição. Tente novamente!";
            return false;
        }
    }

} ?>