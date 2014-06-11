<?php
//banners
$strBanner = "SELECT b.nmLinkImagem,b.nmLinkExterno,c.nmTituloAmigavel,b.nmTituloConteudo FROM tb_conteudo b left join tb_conteudo c on c.idConteudo=b.idConteudoRelacionado WHERE b.idTipoConteudo = 25 and b.inPublicar = 1 order by b.ordem LIMIT 10";
$qryBanner = $db->query($strBanner);
?>
<div class="cf">
    <h2 class="title-section">
        <span class="title-section__name">
            Notícias
        </span>
        <a class="title-section__more" href="noticias">
            Ver Mais
        </a>
    </h2>
    <?php
    $str = "SELECT nmTituloAmigavel,nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo WHERE inPublicar=1 and idTipoConteudo=11 order by idConteudo desc limit 3";
    $qry = $db->query($str);
    foreach ($qry as $key => $item) {
        ?>
        <div class="col latest-news">
            
                <?php
                if (is_file("arquivos/enviados/image/" . $item["nmLinkImagem"])) {
                    ?>
            <a href="noticias/<?php echo $item["nmTituloAmigavel"]; ?>">
                    <img alt="<?php echo $item["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>&w=480&h=270" />
                    
                    <h3 class="latest-news__title"><?php echo resume(stripslashes($item["nmTituloConteudo"]), 30); ?></h3>
                <p>
                    <?php echo resume(stripslashes($item["nmConteudo"]), 80); ?>
                    <b>Leia mais</b>
                </p>
                 </a>
                    <?php
                }else{
                    ?>
            <a href="noticias/<?php echo $item["nmTituloAmigavel"]; ?>">
                 <h3 class="latest-news__title"><?php echo resume(stripslashes($item["nmTituloConteudo"]), 90); ?></h3>
                <p>
                    <?php echo resume(($item["nmConteudo"]), 250); ?>
                    <b>Leia mais</b>
                </p>    
                </a>
                <?php
                }
                ?>                
                
            
        </div>
        <?php
    }
    ?>
</div>

<?php include $defaultPathModulo . 'newsletter.php'; ?>


<div class="cf">
    <div class="col-durango">
        <h2 class="title-section">
            <span class="title-section__name">
                Durango Duarte
            </span>
            <a class="title-section__more" href="articulistas/autor/durango-duarte">
                Ver Todos
            </a>
        </h2>
        <?php
        $str = "SELECT nmTituloAmigavel,nmLinkImagem,nmTituloConteudo FROM tb_conteudo WHERE inPublicar=1 and idTipoConteudo=26 and idConteudoRelacionado=62 order by idConteudo desc limit 1";
        $qry = $db->query($str);
        ?>
        <a title="<?php echo $qry[0]["nmTituloConteudo"]; ?>" href="artigos/<?php echo $qry[0]["nmTituloAmigavel"]; ?>">
            <?php
            if (is_file("arquivos/enviados/image/" . $qry[0]["nmLinkImagem"])) {
                ?>
                <img alt="<?php echo $qry[0]["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qry[0]["nmLinkImagem"]; ?>&w=480&h=480" />
                <?php
            }
            ?>
        </a>
    </div>

    <div class="col-charge">
        <h2 class="title-section">
            <span class="title-section__name">
                Charge do Dia
            </span>
            <a class="title-section__more" href="charges">
                Ver Todas
            </a>
        </h2>

        <?php
        $str = "SELECT nmTituloAmigavel,nmLinkImagem,nmTituloConteudo,dtDataConteudo as data FROM tb_conteudo WHERE inPublicar=1 and idTipoConteudo=34 ORDER BY idConteudo DESC LIMIT 1";
        $qry = $db->query($str);
        ?>
        <a data-modal='
           <div class="modal-album-img">
           <img src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qry[0]["nmLinkImagem"]; ?>&w=912&h=588" />
           <h4><?php echo $qry[0]["nmTituloConteudo"]; ?></h4>
           <small><?php echo date('d/m/Y', strtotime($qry[0][data])); ?></small>
           <br >    
           </div>
           ' class="fancybox" rel="group" href="">
            <img src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qry[0]["nmLinkImagem"]; ?>&w=912&h=588" />  
        </a>
    </div>
</div>

<?php include $defaultPathModulo . 'ddcProgramas.php'; ?>

<div class="cf">
    <div class="col col-acervo">
        <div class="i-acervo">
            <h2 class="title-section">
                <a class="title-section__name" href="galeria">
                    Acervo Fotográfico
                </a>
                <!-- <a class="title-section__more" href="galeria">
                     Ver Todos
                 </a> -->
            </h2>
            <div class="grid">
                <?php
                $str = "SELECT tb_galeria_imagem.*, tb_galeria.nmGaleria FROM tb_galeria_imagem INNER JOIN tb_galeria ON tb_galeria.idGaleria = tb_galeria_imagem.idGaleria ORDER BY RAND( ) LIMIT 8";
                $qry = $db->query($str);
                foreach ($qry as $item) {
                    ?>
                    <div class="grid-item">
                        <a title="<?php echo $item["nmGaleria"]; ?>" href="galeria/id/<?php echo $item["idGaleria"]; ?>">
                            <img alt="" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/galeria/<?php echo $item["idGaleria"]; ?>/<?php echo $item["nmImagem"]; ?>&w=180&h=180"/>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="i-reportagens">
            <h2 class="title-section">
                <span class="title-section__name">
                    Acervo Histórico
                </span>
            </h2>


            <?php

              /*QUERY PARA PEGAR AS IMAGENS COM A TAG DE SLIDEHISTORICO */
              /*CASO SEJA DELETADO ESSA CATEGORIA POSSIVELMENTE IRÁ GERAR ERRO NO SLIDESHOW*/
              $queryIdImages = "SELECT idArquivo FROM tb_arquivo_categoria WHERE idCategoria = 53";
              $query = $db->query($queryIdImages);
              $qtd = count($query); 
              
              if($qtd > 1){

                    for($i = 0 ; $i <= $qtd; $i++){

                        if(isset($query[$i]['idArquivo'])){
                          $completaConsulta[] = $query[$i]['idArquivo'];
                        }else{break;}
                    }
                   
              /*JUNTANDO POR VIRGULA*/
              $dados = join(',',$completaConsulta);
                
                $queryImage = "SELECT nmNomeArquivo FROM tb_arquivo WHERE idArquivo IN(".$dados.")";   
                $imagesSlide = $db->query($queryImage);
                

              }else{

                   $queryImage = "SELECT nmNomeArquivo FROM tb_arquivo WHERE idArquivo = ".$query[0]['idArquivo'];   
                   $imagesSlide = $db->query($queryImage);     
              } 

              $coutImage = count($imagesSlide);
              
             
              /*FIMQUERY*/  

              $str = "SELECT nmLinkImagem,nmLinkExterno,nmTituloConteudo,ordem FROM tb_conteudo WHERE idTipoConteudo = 25 AND ordem = 20 LIMIT 1";
              $qry = $db->query($str);
            ?>
            <a title="<?php echo $qry[0]["nmTituloConteudo"]; ?>" href="acervo-historico">
               
                <?php

                if (is_file("arquivos/enviados/image/" . $qry[0]["nmLinkImagem"])) {
                    ?>
                    <div id="acervohistorico" class="pics">
                       <!-- <img width="100%" alt="<?php echo $qry[0]["nmCategoria"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qry[0]["nmLinkImagem"]; ?>&w=480&h=270" /> -->
                       <?php 
                            if($coutImage > 1){
                                        //lista imagens
                                        for($i = 0 ; $i < $coutImage ; $i++){
                                            if(isset($imagesSlide[$i]['nmNomeArquivo'])){ 
                                                $listaSlides .= '<img src="arquivos/enviados/image/'.$imagesSlide[$i]['nmNomeArquivo']. '" />';
                                             }else{break;}  
                                       }

                                    echo $listaSlides;
                            }else{

                                    echo '<img src="arquivos/enviados/image/'.$imagesSlide[0]['nmNomeArquivo']. '" />';

                            }

                        ?>

                    </div>
                    <?php
                }

                ?>  


            </a>
        </div>
        <br>
        <!--Acervo de Vídeo   -->
           <div class="i-reportagens">
            <h2 class="title-section">
                <span class="title-section__name">
                    Acervo de Vídeos
                </span>
            </h2>
            <?php
              $str = "SELECT idConteudo, nmLinkImagem,nmLinkExterno,nmTituloConteudo,ordem FROM tb_conteudo WHERE idTipoConteudo = 4 AND idConteudo = 539 LIMIT 1";
              $qry = $db->query($str);

            ?>
            <a title="<?php echo $qry[0]["nmTituloConteudo"]; ?>" href="acervo-videos">
               
                <?php
                if (is_file("arquivos/enviados/image/" . $qry[0]["nmLinkImagem"])) {
                    ?>
                    
                    <div id="acervovideos" class="pics">
                       <!-- <img width="100%" alt="<?php echo $qry[0]["nmCategoria"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qry[0]["nmLinkImagem"]; ?>&w=480&h=270" /> -->
                        <img alt="5 Fatos da Cidade" src="img/5-fatos-da-cidade.jpg" />
                        <img alt="Programa" src="img/videos-eleitorais.jpg" />
                    </div>
                    <?php
                }
                ?>                
            </a>
        </div>   
        <!-- Fim Acervo de Vídeo -->

    </div>

    <div class="col col-articulistas">
        <div class="i-articulistas">
            <h2 class="title-section">
                <span class="title-section__name">
                    Articulistas
                </span>

                <a class="title-section__more" href="articulistas">
                    Ver Todos
                </a>
            </h2>
            <div class="list">
            
                <?php
                $strArt = "SELECT DISTINCT tb1.* FROM tb_conteudo AS tb1 INNER JOIN tb_conteudo AS tb2 ON tb1.idConteudo = tb2.idConteudoRelacionado WHERE tb1.idTipoConteudo = 15 AND tb1.inPublicar = 1 ORDER BY idConteudo DESC LIMIT 4";
                $qryArt = $db->query($strArt);
                foreach ($qryArt as $artigo) {
                    ?>
                    <div class="list-item cf">
                        <a class="list-item__img" href="articulistas/autor/<?php echo $artigo["nmTituloAmigavel"]; ?>">
                            <img alt="<?php echo $artigo["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $artigo["nmLinkImagem"]; ?>&w=180&h=180"/>
                        </a>

                        <h3 style="margin-bottom:0;">
                            <a href="articulistas/autor/<?php echo $artigo["nmTituloAmigavel"]; ?>"><?php echo $artigo["nmTituloConteudo"]; ?></a>
                        </h3>
                         <div class="list-item__meta">                            
                            <?php echo stripslashes(resume($artigo["nmResumo"], 80)); ?>
                        </div>
                        <div class="list-item__meta">                            
                            <?php echo stripslashes(resume($artigo["nmConteudo"], 130)); ?>
                        </div>

                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div> 