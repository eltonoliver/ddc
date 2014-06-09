<header id="to-top" class="top-bar">
    <div class="cf">
        <?php /* ?>
            <a class="radio-ddc" data-modal="<br /><br /><br /> oi :D tá me vendo? <br /><br /><br /><br />" href="#">
                Rádio DDC
                <span title="Dê o play" aria-hidden="true"></span>
            </a>
        <? */ ?>
        <a class="radio-ddc" href="#">
            Rádio DDC
            <span title="Dê o play" aria-hidden="true"></span>
        </a>
    </div>
</header>

<header class="top" role="banner">
    <div class="cf">
        <h1 class="logo fl">
            <a title="DDC | Durango Duarte Channel" href="">
                <img alt="DDC | Durango Duarte Channel" src="img/logo.svg" />
            </a>
        </h1>

        <div class="top-pub fr">
            <?php
                $strPublicidade = "SELECT nmLinkImagem,nmLinkExterno,nmTituloConteudo FROM tb_conteudo WHERE idTipoConteudo = 14 and inPublicar = 1 and NOW() between dtDataConteudo and dtDataExpiracao and ordem = 1 order by RAND() LIMIT 1";
                $qryPublicidade = $db->query($strPublicidade);
                foreach ($qryPublicidade as $item) {
                    
            ?>
                <a title="<?php echo $item["nmTituloConteudo"]; ?>" target="_blank" href="<?php echo $item["nmLinkExterno"]; ?>">
                    <img alt="<?php echo $item["nmTituloConteudo"]; ?>" src="<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>" width="740" height="100" />
                    <?php if ($item["nmLinkExterno"]) { ?>
                </a>
            <?php } } ?>
        </div>
    </div>
</header>

<nav class="main-nav">
    <div class="cf">
        <form class="fl form-search" role="search" action="busca/">
            <a class="oc-form-search oc-open" title="Procurar no site" href="#form-search"></a>
            <div class="form-search__container closed">
                <label for="search">Procurar no site:</label>
                <input id="search" name="nmTermoBusca" placeholder="Procurar no site" type="search" value="" />
                <input type="submit" value="Procurar" />
            </div>
        </form>

        <ul class="fl menu" role="navigation">
            <li>
                <a href="#main-menu">
                    <i class="menu-close" aria-hidden="true"></i>
                    <span>Menu</span>
                </a>

                <ul class="main-menu">
                    <li>
                        <a href="<?php echo $url_raiz; ?>">Página Inicial</a>
                    </li>
                    <li>
                        <a href="pagina/institucional">Institucional</a>
                    </li>
                    <li>
                        <a href="noticias">Notícias</a>
                    </li>
                   <!-- <li>
                        <a href="#ddc_programas" >DDC - Programas</a>
                    </li> -->
                    <li>
                        <a href="articulistas/durango-duarte">Durango Duarte</a>
                    </li>
                    <li>
                        <a href="articulistas">Articulistas</a>
                    </li>
                    <li>
                        <a href="charges">Charge do Dia</a>
                    </li>
                    <li>
                        <a href="galeria">Acervo Fotográfico</a>
                    </li>
                    <li>
                        <a href="acervo-historico">Acervo Histórico</a>
                    </li>
                    <li>
                        <a href="minha-reportagem">Minha Reportagem</a>
                    </li>
                    <li>
                        <a>Pesquisas</a>
                        <ul class="novo-mn">
                            <li><a href="pagina/totens-interativos">Totens Interativos </a></li>
                            <li><a href="">Responda Online </a></li>
                            <li><a href="resultados">Resultados </a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="servicos">Serviços</a>
                    </li>
                    <li>
                        <a href="pagina/como-anunciar">Como Anunciar</a>
                    </li>
                    <li>
                        <a href="contato">Contato</a>
                    </li>
                </ul>
            </li>
        </ul>

        <ul class="fr social">
            <li>
                <a class="facebook" title="Facebook" target="_blank" href="<?php echo $redesSociais[1]["nmURLCompleta"]; ?>"></a>
            </li>
            <li>
                <a class="googleplus" title="Google+" target="_blank" href="<?php echo $redesSociais[3]["nmURLCompleta"]; ?>"></a>
            </li>
            <li>
              <a class="youtube" title="Youtube" target="_blank" href="<?php echo $redesSociais[4]["nmURLCompleta"]; ?>"></a>
            </li>
            <li>
                <a class="pinterest" title="Pinterest" target="_blank" href="<?php echo $redesSociais[10]["nmURLCompleta"]; ?>"></a>
            </li>
            <li>
                <a class="instagram" title="Instagram" target="_blank" href="<?php echo $redesSociais[9]["nmURLCompleta"]; ?>"></a>
            </li>
            <li>
                <a class="linkedin" title="Linked In" target="_blank" href="<?php echo $redesSociais[5]["nmURLCompleta"]; ?>"></a>
            </li>
            <?php /* ?>
              <li>
              <a class="wikipedia" title="Wikipédia" target="_blank" href=""></a>
              </li>
              <?php */ ?>
            <li>
                <a class="slideshare" title="Slideshare" target="_blank" href="<?php echo $redesSociais[11]["nmURLCompleta"]; ?>"></a>
            </li>
        </ul>
    </div>
</nav>