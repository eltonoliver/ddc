<? if (!isset($titulo) || !$titulo) { ?>
    <?
    //half banners
    $strBanner = "SELECT b.nmLinkImagem,b.nmLinkExterno,c.idConteudo,b.nmTituloConteudo FROM tb_conteudo b left join tb_conteudo c on c.idConteudo=b.idConteudoRelacionado WHERE b.idTipoConteudo = 14 and b.idCategoria=2 and b.inPublicar = 1 order by b.ordem LIMIT 3";
    $qryBanner = $db->query($strBanner);
    //PREPARAÇÃO DO PAGINADOR
    //Define o total de registros por página
    $limite = 4;
    //Pega o número da página que vem pela URL
    $pagina = (int) $_GET['pag'];
    //Seta a página inicial
    if (!$pagina) {
        $pagina = 1;
    }
    //Calcula os registros inicial e final as serem pesquisados no banco de dados
    $inicio = ($pagina * $limite) - $limite;
    $filtro = '';
    $ano = ($_REQUEST['ano'] ? (int) $_REQUEST['ano'] : date('Y'));
    $mes = ($_REQUEST['mes'] ? (int) $_REQUEST['mes'] : false);
    $filtro .= ' AND YEAR(dtDataConteudo) = ' . $db->clean($ano);
    if ($mes)
        $filtro .= ' AND MONTH(dtDataConteudo) = ' . $db->clean($mes);
    $str = "SELECT nmTituloAmigavel,nmUsuario,day(dtDataConteudo) as dia,month(dtDataConteudo) as mes,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE inPublicar=1 and idTipoConteudo=11 " . $filtro . " order by dtDataConteudo desc limit " . $inicio . "," . $limite;
    $qry = $db->query($str);
    foreach ($qry as $key => $item) {
        if ($qryBanner[$key]["idConteudo"] > 0)
            $link = 'posts/id/' . $qryBanner[$key]["idConteudo"];
        elseif ($qryBanner[$key]["nmLinkExterno"] != '')
            $link = $qryBanner[$key]["nmLinkExterno"];
        else
            $link = "javascript: void(0);";
        ?>
        <div class="banner-blog">
            <a title="<?php echo $qryBanner[$key]["nmTituloConteudo"]; ?>" href="<?php echo $link; ?>">
                <img title="<?php echo $qryBanner[$key]["nmTituloConteudo"]; ?>" src="arquivos/enviados/image/<?php echo $qryBanner[$key]["nmLinkImagem"]; ?>" />
            </a>
        </div>

        <article class="blog-post">
            <header class="blog-post-header">
                <div class="blog-post-date">
                    <span><?php echo $item["dia"]; ?></span>
                    <?php echo $meses[$item["mes"] - 1]["siglaMes"]; ?>
                </div>

                <div class="blog-post-header-text">
                    <h2 class="blog-post-title">
                        <a href="posts/<?php echo $item["nmTituloAmigavel"]; ?>">
                            <?php echo $item["nmTituloConteudo"]; ?>
                        </a>
                    </h2>

                    <div class="blog-post-meta">
                        Publicado por <a href=""><?php echo $item["nmUsuario"]; ?></a>
                    </div>
                </div>
            </header>

            <div class="blog-post-options">
                <div class="share-buttons">
                    <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;width&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=128445530690700" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px; display: inline-block; vertical-align: middle; width: 53px" allowTransparency="true"></iframe>
                </div>
                <a class="comment-button" href="">
                    <i class="icon icon-comment"></i>
                    Comente
                </a>
            </div>
            <?
            if (is_file("arquivos/enviados/image/" . $item["nmLinkImagem"])) {
                ?>
                <div class="blog-post-img">
                    <a href="posts/<?php echo $item["nmTituloAmigavel"]; ?>">
                        <img src="arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>" />
                    </a>
                </div>
                <?
            }
            ?>
            <div class="blog-post-resume">
                <?php echo resume($item["nmConteudo"], 480); ?> <a class="read-more" href="posts/<?php echo $item["nmTituloAmigavel"]; ?>">Continue lendo.</a>
            </div>
        </article>
    <? } ?>
    <?
    //NAVEGACAO DO PAGINADOR 
    $srtQtotal = "SELECT COUNT(*) as total_registros FROM tb_conteudo WHERE idTipoConteudo=11 AND inPublicar = 1 " . $filtro;
    //Busca o total de registros da consulta nao paginada
    $total_registros = $db->query($srtQtotal);
    $total_registros = $total_registros[0]["total_registros"];
    //Calcula o total de paginas
    $total_paginas = ceil($total_registros / $limite);
    $filtro2 = '';
    $ano = ($_REQUEST['ano'] ? (int) $_REQUEST['ano'] : date('Y'));
    $mes = ($_REQUEST['mes'] ? (int) $_REQUEST['mes'] : false);
    $filtro2 .= '/ano/' . $ano;
    if ($mes)
        $filtro2 .= '/mes/' . $mes;
    //Nome da pagina 
    $stringPagina = $url_raiz . 'posts' . $filtro2;
    //Chama a funcao que monta a exibicao do paginador
    navegacaoPaginadorExterno($total_registros, $total_paginas, $limite, $stringPagina, $pagina, $url_raiz);
} else {
    $strPagina = "SELECT idConteudo,nmTituloAmigavel,nmUsuario,day(dtDataConteudo) as dia,month(dtDataConteudo) as mes,nmLinkImagem,nmTituloConteudo,nmConteudo,inComentario FROM tb_conteudo c left join tb_usuario u on u.idUsuario=c.idUsuarioCadastro WHERE nmTituloAmigavel= " . $db->clean($titulo) . " AND inPublicar = 1 LIMIT 1";
    $qryPagina = $db->query($strPagina);
    if (!$qryPagina) {
        redirecionar($url_raiz . '404');
    }
    ?>
    <article class="blog-post">
        <header class="blog-post-header">
            <div class="blog-post-date">
                <span><?php echo $qryPagina[0]["dia"]; ?></span>
                <?php echo $meses[$qryPagina[0]["mes"] - 1]["siglaMes"]; ?>
            </div>

            <div class="blog-post-header-text">
                <h2 class="blog-post-title">
                    <a href="posts/<?php echo $qryPagina[0]["nmTituloAmigavel"]; ?>">
                        <?php echo $qryPagina[0]["nmTituloConteudo"]; ?>
                    </a>
                </h2>

                <div class="blog-post-meta">
                    Publicado por <a href=""><?php echo $qryPagina[0]["nmUsuario"]; ?></a>
                </div>
            </div>
        </header>

        <div class="blog-post-options">
            <div class="share-buttons">
                <? include("addThis.php"); ?>
                <!--<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;width&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=128445530690700" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px; display: inline-block; vertical-align: middle; width: 53px" allowTransparency="true"></iframe>               
                ver os js ocultados no rodape
                <span class='st_facebook'></span>
                <span class='st_twitter'></span>
                <span class='st_googleplus'></span>
                <span class='st_email'></span>
                -->
            </div>
            <?
            if ($qryPagina[0]['inComentario']) {
                ?>
                <a class="comment-button" href="<?php echo $urlAtualCompleta ?>#form-comment">
                    <i class="icon icon-comment"></i>
                    Comente
                </a>
                <?
            }
            ?>
        </div>
        <?
        if (is_file("arquivos/enviados/image/" . $qryPagina[0]["nmLinkImagem"])) {
            ?>
            <div class="blog-post-img">
                <a href="posts/<?php echo $qryPagina[0]["nmTituloAmigavel"]; ?>">
                    <img src="arquivos/enviados/image/<?php echo $qryPagina[0]["nmLinkImagem"]; ?>" />
                </a>
            </div>
            <?
        }
        ?>
        <div class="blog-post-text">
            <?php echo stripslashes($qryPagina[0]["nmConteudo"]); ?>
        </div>       
        <? if ($qryPagina[0]['inComentario']) { ?>
            <a name="form-comment"></a>
            <? include($raiz . 'modulos/sisMensagem.php'); ?>
            <form id="form-comment" class="form-comment" name="formComentario" action="<?php echo $url_raiz; ?>controller/enviaComentario" method="post">
                <h4>Deixe seu comentário</h4>
                <div class="label-input label-left">
                    <label for="nmNome">Nome:</label>
                    <input id="nmNome" name="nmNome" type="text" placeholder="Seu nome" required style="top: auto;"/>
                </div>
                <div class="label-input label-right">
                    <label for="nmEmail">E-mail:</label>
                    <input id="nmEmail" name="nmEmail" type="text" placeholder="email@provedor.com" required style="top: auto;"/>
                </div>
                <div class="label-textarea label-full">
                    <label for="nmComentario">Seu comentário:</label>
                    <textarea id="nmComentario" name="nmComentario" required style="top: auto;"></textarea>
                </div>
                <div class="label-captcha label-full">
                    <label for="security_code">Código de Segurança:</label>
                    <img src="<?php echo $urls_raiz ?>lib/captcha/CaptchaSecurityImages.php" alt="CAPTCHA" width="129" height="40" />
                    <input id="security_code" name="security_code" type="text" size="22" style="top: auto;" />
                    <a href="<?php echo $urlAtualCompleta; ?>">[ Gerar outro código ] </a>
                </div>
                <div class="buttons-right">
                    <div class="label-checkbox label-inline">
                        <input id="inExibirContato" name="inExibirContato" type="checkbox" style="top: auto;"/>
                        <label for="inExibirContato">Publicar meu e-mail</label>
                    </div>
                    <input type="hidden" name="acao" id="acao" value="Cadastrar" />
                    <input type="hidden" name="retorno" id="retorno" value="<?php echo $urlAtualCompleta; ?>" />
                    <input type="hidden" name="idConteudo" id="idConteudo" value="<?php echo $qryPagina[0]['idConteudo']; ?>" />
                    <input type="hidden" name="nmTituloConteudo" id="nmTituloConteudo" value="<?php echo $qryPagina[0]["nmTituloConteudo"]; ?>" />
                    <? if (isset($_POST['reply'])) { ?>
                        <input type="hidden" name="idComentarioPai" id="idComentarioPai" value="<?php echo $_POST['reply']; ?>" />
                    <? } else { ?>
                        <input type="hidden" name="idComentarioPai" id="idComentarioPai"value="0" />
                    <? } ?>
                    <button class="btn btn-site">Comentar</button>
                </div>
            </form>
            <!-- #secaoComentarios .boxComentario -->
            <?

            /**
             * Get either a Gravatar URL or complete image tag for a specified email address.
             *
             * @param string $email The email address
             * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
             * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
             * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
             * @param boole $img True to return a complete IMG tag False for just the URL
             * @param array $atts Optional, additional key/value attributes to include in the IMG tag
             * @return String containing either just a URL or a complete image tag
             * @source http://gravatar.com/site/implement/images/php/
             */
            function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
                $url = 'http://www.gravatar.com/avatar/';
                $url .= md5(strtolower(trim($email)));
                $url .= "?s=$s&d=$d&r=$r";
                if ($img) {
                    $url = '<img src="' . $url . '"';
                    foreach ($atts as $key => $val)
                        $url .= ' ' . $key . '="' . $val . '"';
                    $url .= ' />';
                }
                return $url;
            }

            function montaArvoreComentarios($idPai = 0, $idConteudo, &$arrayComentario, &$ordem) { //,$nivel=0
                $db = $GLOBALS["db"];
                $str = "SELECT * FROM tb_comentarios WHERE idComentarioPai = " . $idPai . " AND idConteudo = " . $idConteudo . " AND inPublicar = 1  ORDER BY idComentario ASC";
                //var_dump($str);
                $qryMontaComentario = $db->query($str);
                //$arrayComentario.= '<ul>';
                foreach ($qryMontaComentario as $comentario) {
                    $arrayComentario[$ordem]["idComentario"] = $comentario["idComentario"];
                    $arrayComentario[$ordem]["idConteudo"] = $comentario["idConteudo"];
                    $arrayComentario[$ordem]["idRel"] = $comentario["idRel"];
                    $arrayComentario[$ordem]["nmNome"] = $comentario["nmNome"];
                    $arrayComentario[$ordem]["nmEmail"] = $comentario["nmEmail"];
                    $arrayComentario[$ordem]["nmComentario"] = $comentario["nmComentario"];
                    $arrayComentario[$ordem]["inPublicar"] = $comentario["inPublicar"];
                    $arrayComentario[$ordem]["inExibirContato"] = $comentario["inExibirContato"];
                    $arrayComentario[$ordem]["idComentarioPai"] = $comentario["idComentarioPai"];
                    $arrayComentario[$ordem]["dtDataCadastro"] = $comentario["dtDataCadastro"];
                    $arrayComentario[$ordem]["ordem"] = $ordem;
                    $ordem++;
                    $strFilho = 'SELECT COUNT(idComentario) AS total FROM tb_comentarios WHERE idComentarioPai = ' . $comentario["idComentario"] . ' AND idConteudo = ' . $idConteudo . ' AND inPublicar = 1 LIMIT 1';
                    $qryFilhos = $db->query($strFilho);
                    if ($qryFilhos[0]["total"] > 0) {
                        montaArvoreComentarios($comentario["idComentario"], $idConteudo, $arrayComentario, $ordem);
                    }
                }
                //$menuMontado.= '</ul>';
                return $arrayComentario;
            }

            //Fim-funcaao
            $arrayComentario = array();
            $ordem = 1;
            $arrayComentario = montaArvoreComentarios(0, $qryPagina[0]['idConteudo'], $arrayComentario, $ordem);
            //new dBug($arrayComentario);
            $comentarios = count($arrayComentario);
            if ($comentarios > 0) {
                ?>
                <ul class="comments">
                    <?
                    //print_r($arrayComentario);
                    $idPai = 0;
                    foreach ($arrayComentario as $key => $comentario) {
                        if (($comentario["idRel"] == $comentario["idComentario"]) and ($key > 1)) {
                            ?>
                        </ul>
                        <?
                    }
                    $email = $comentario["nmEmail"];
                    ?>
                    <li class="comentario" id="comentario<?php echo $comentario["idComentario"]; ?>">
                        <div class="comment">
                            <header class="comments-meta">
                                <?
                                $linkEmail = '#';
                                if ($comentario["inExibirContato"]) {
                                    ?>
                                    <a class="comments-meta-name" href="<?php echo 'mailto:' . $email; ?>"><?php echo $comentario["nmNome"]; ?></a>
                                    <?
                                } else {
                                    ?>
                                    <a class="comments-meta-name"><?php echo $comentario["nmNome"]; ?></a>
                                    <?
                                }
                                ?>
                                <span class="comments-meta-data"><?php echo dataExtensoBR($comentario["dtDataCadastro"]); ?></span>
                            </header>
                            <div class="comments-text">
                                <p>
                                    <?php echo $comentario["nmComentario"]; ?>
                                </p>
                            </div>
                            <form id="resp-comment" name="formRespostaComentario" action="<?php echo $urlAtualCompleta; ?>#form-comment" method="post">
                                <input type="hidden" name="reply" value="<?php echo $comentario["idComentario"] ?>"/>
                                <button class="comments-reply-to">Responder ↓</button>
                            </form>
                        </div>
                        <?
                        if (($comentario["idRel"] == $comentario["idComentario"])) {
                            ?>
                            <ul class="replied-comments">
                                <?
                            }
                            ?>
                            <?
                            if ($arrayComentario[$key + 1]["idRel"] != $comentario["idComentario"]) {
                                ?>
                        </li>
                        <?
                    }
                    ?>
                    <!-- .comentario -->
                <? } ?>
            </ul>
        <? } ?>
        <!-- .boxComentarios -->
    <? } ?>
    <!--comentários-->
    </article>
<? } ?>
