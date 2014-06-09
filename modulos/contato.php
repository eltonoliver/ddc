<?php
    $strEndereco = "SELECT * FROM tb_endereco_site e left join tb_tipo_logradouro l on l.idTipoLogradouro=e.idTipoLogradouro WHERE e.inPrincipal=1";
    $qryEndereco = $db->query($strEndereco);
    $strContato = "SELECT nmEmailContato FROM tb_contato_site WHERE inAtivo=1 and inContatoPrincipal=1 and inExibir=1";
    $qryContato = $db->query($strContato);
?>
<h2 class="title-section">
    <span class="title-section__name">
        Contato
    </span>
    
</h2>
    <span style="display:block; margin:-18px auto 30px auto; width:740px; height:200px;">
	   <?php
        echo $qryEndereco[0]['nmCodigoMaps'] . "<br />  ";
       ?>
       <p style="display:block; margin:5px auto 10px auto; width:740px; height:30px;"> Este espaço é dedicado a você, qualquer dúvida, crítica e/ou sugestão por favor entre em contato conosco. A sua opinião é muito importante e certamente nos ajudará a aprimorar ainda mais nosso atendimento. </p>
    </span>
<?php
    $str = "SELECT nmConteudo FROM tb_conteudo WHERE inPublicar=1 and idConteudo=38";
    $qry = $db->query($str);
?>

<article class="text">
    <?php echo stripslashes($qry[0]["nmConteudo"]); ?>

    <?php include($raiz . 'modulos/sisMensagem.php'); ?>
</article>

<form class="form form-contato" action="<?php echo $url_raiz; ?>controller/envia-contato" method="post">
    <fieldset class="cf">
        <div class="block-input fl">
            <label for="nome">Nome:</label>
            <input type="text" placeholder="Seu nome" title="Seu nome" name="nome" id="nome" required style="top:auto;"/>
        </div>

        <div class="block-input fr">
            <label for="email">E-mail:</label>
            <input type="text" placeholder="ex. nome@email.com" title="Seu e-mail" name="email" id="email" required style="top:auto;"/>
        </div>

        <div class="block-input fl">
            <label for="cidade">Cidade:</label>
            <input type="text" placeholder="ex. Manaus" title="Sua Cidade" name="cidade" id="cidade" required style="top:auto;"/>
        </div>

        <div class="block-select fr">
            <label for="estado">Estado:</label>
            <select title="Seu Estado" name="estado" id="estado" required style="top:auto;"/>
                <option>[ Selecione ]</option>
                <option value="AM">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AM">Amazonas</option>
                <option value="AP">Amap&aacute;</option>
                <option value="BA">Bahia</option>
                <option value="CE">Cear&aacute;</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Esp&iacute;rito Santo</option>
                <option value="GO">Goi&aacute;s</option>
                <option value="MA">Maranh&atilde;o</option>
                <option value="MT">Mato Grosso</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MG">Minas Gerais</option>
                <option value="PA">Par&aacute;</option>
                <option value="PB">Para&iacute;ba</option>
                <option value="PR">Paran&aacute;</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piau&iacute;</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RO">Rond&ocirc;nia</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="RR">Roraima</option>
                <option value="SC">Santa Catarina</option>
                <option value="SE">Sergipe</option>
                <option value="SP">S&atilde;o Paulo</option>
                <option value="TO">Tocantins</option>                    
            </select>
        </div>

        <div class="block-select fl">
            <label for="motivo">Assunto:</label>
            <select class="contatocampo" id="assunto" name="assunto" style=" top: auto; ">
                <option>[ Selecione ]</option>
                <option value="Dúvidas">Dúvidas</option>
                <option value="Sugestões">Sugestões</option>
                <option value="Críticas">Críticas</option>
                <option value="Outros">Outros</option>
            </select>
        </div>

        <div class="block-textarea full">
            <label for="mensagem">Mensagem:</label>
            <textarea name="mensagem" id="mensagem" required style="top:auto;" onKeyUp="limitadorCampo(this, 1000, 'contador', 'nmComentario');"></textarea>
        </div>

        <div class="block-captcha block-input">
            <label for="security_code">Código de Segurança:</label>

            <img src="<?php echo $urls_raiz ?>lib/captcha/CaptchaSecurityImages.php" alt="CAPTCHA" width="129" height="40" />

            <input id="security_code" name="security_code" type="text" style="top: auto;" />

            <a href="<?php echo $urlAtualCompleta; ?>">[ Gerar outro código ] </a>
        </div>

        <input type="hidden" name="acao" id="acao" value="Enviar"/>
        <button class="btn btn-enviar fr" onClick="return validaFormularioVazio(this.form);">Enviar</button>
    </fieldset>
</form>
	