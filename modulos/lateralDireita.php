<aside class="fr">
    <div class="a-linha">
        <?php
                $strContador = "SELECT nmTituloConteudo,dtDataConteudo,nmResumo FROM tb_conteudo WHERE inPublicar=1 and idTipoConteudo=28 order by dtDataConteudo desc limit 1";
                $qryContador = $db->query($strContador);
           if(!empty($qryContador)){
        ?>
        <div class="contador">
            

            <h4><?php echo $qryContador[0]["nmTituloConteudo"]; ?></h4>

            <p>
                <?php echo $qryContador[0]["nmResumo"]; ?>
                <br />
                Faltam apenas:
            </p>

            <div id="countdown" class="contador_container"></div>
        </div>
        <?php 
        
           }else{ }
           
        ?>

        <div class="contato">
            <?php
                $strContato = "SELECT nmLinkImagem,nmLinkExterno,nmTituloConteudo,ordem FROM tb_conteudo WHERE idTipoConteudo = 25 AND ordem = 1 LIMIT 1";
                $qryContato = $db->query($strContato);
                    foreach ($qryContato as $item) {
            ?>
            <a title="<?php echo $item["nmTituloConteudo"]; ?>" href="<?php echo $item["nmLinkExterno"]; ?>">
                    <img alt="<?php echo $item["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>&w=480&h=240" />
                </a>
                    <?php } ?>
        </div>
    </div>

    <div class="a-linha">
        <div class="a-fala-manaus">
            <h2 class="title-section">
                <span class="title-section__name">
                    Minha Reportagem
                </span>
            </h2>

            <?php
                //banners
                $strBannerPop = "SELECT * FROM tb_conteudo WHERE idTipoConteudo = 32 ORDER BY idConteudo DESC LIMIT 1 ";
                $bannerJorPop = $db->query($strBannerPop);
            ?>
            <a title="<?php echo $bannerJorPop[0]["nmTituloConteudo"]; ?>" href="minha-reportagem">
                <img width="100%" alt="<?php echo $bannerJorPop[0]["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $bannerJorPop[0]["nmLinkImagem"]; ?>&w=480&h=480" />
            </a>

            <div id="desc_imagem"> <?php 
                    $hora = explode(":", substr($bannerJorPop[0]["dtDataCadastro"],-8));
                    echo $hora[0]."h".$hora[1]." - ".$bannerJorPop[0]["nmTituloConteudo"];  ?> </div>
        </div>

        <div class="a-pesquisas">
            <h2 class="title-section">
                <span class="title-section__name">
                    Pesquisas
                </span>
            </h2>
            <?php
                $strPesq = "SELECT nmLinkImagem,nmLinkExterno,nmTituloConteudo FROM tb_conteudo WHERE idTipoConteudo = 25 AND ordem > 1 AND ordem < 5 ORDER BY ordem ASC LIMIT 3";
                $qryPesq = $db->query($strPesq);
                    foreach ($qryPesq as $item) {
            ?>
            <a title="<?php echo $item["nmTituloConteudo"]; ?>" href="<?php echo $item["nmLinkExterno"]; ?>" target="_blank">
                    <img alt="<?php echo $item["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>&w=480&h=160" height="135px;" width="240px;" />
                </a>
                <br />
            <?php } ?>
        </div>
    </div>

    <div class="a-linha">
        <div class="a-banner">
            <?php
                $strCriterio = "SELECT nmLinkImagem,nmLinkExterno,nmTituloConteudo,ordem FROM tb_conteudo WHERE idTipoConteudo = 25 AND ordem = 5 LIMIT 1";
                $qryCriterio = $db->query($strCriterio);
                    foreach ($qryCriterio as $item) {
            ?>
            
            <a data-modal='<div  style="width: 800px; height:588px; overflow: auto;"> <?php require "modulos/criterioBrasil.php"; ?> </div>
           ' class="fancybox" rel="group" href=""> 
         
                    <img alt="<?php echo $item["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>&w=480&h=240" height="135px;" width="240px;" />
            </a>
                    <?php } ?>
        </div>

        <div class="a-ad">
            <?php
                $strPublicidade = "SELECT nmLinkImagem,nmLinkExterno,nmTituloConteudo FROM tb_conteudo WHERE idTipoConteudo = 14 and inPublicar = 1 and NOW() between dtDataConteudo and dtDataExpiracao and ordem = 2 order by ordem LIMIT 1";
                $qryPublicidade = $db->query($strPublicidade);
                    foreach ($qryPublicidade as $item) {
            ?>
                <a title="<?php echo $item["nmTituloConteudo"]; ?>" target="_blank" href="<?php echo $item["nmLinkExterno"]; ?>">
                    <img alt="<?php echo $item["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>&w=480&h=270" height="135px;" width="240px;" />
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="a-linha">
        <div class="a-banner">
            <?php
                $strServico = "SELECT nmLinkImagem,nmLinkExterno,nmTituloConteudo,ordem FROM tb_conteudo WHERE idTipoConteudo = 25 AND ordem = 6 LIMIT 1";
                $qryServico = $db->query($strServico);
                    foreach ($qryServico as $item) {
            ?>
                <a title="<?php echo $item["nmTituloConteudo"]; ?>" href="<?php echo $item["nmLinkExterno"]; ?>">
                    <img alt="<?php echo $item["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>&w=480&h=240" height="135px;" width="240px;"  />
                </a>
            <?php } ?>
            <?php
                $strVoice = "SELECT nmLinkImagem,nmLinkExterno,nmTituloConteudo,ordem FROM tb_conteudo WHERE idTipoConteudo = 25 AND ordem = 7 LIMIT 1";
                $qryVoice = $db->query($strVoice);
                    foreach ($qryVoice as $item) {
            ?>
            <a title="<?php echo $item["nmTituloConteudo"]; ?>" href="<?php echo $item["nmLinkExterno"]; ?>" target="_blank">
                    <img alt="<?php echo $item["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>&w=480&h=240" height="135px;" width="240px;"  />
                </a>
           <?php } ?>
        </div>

        <div class="a-ad">
            <?php
                $strPublicidade = "SELECT nmLinkImagem,nmLinkExterno,nmTituloConteudo FROM tb_conteudo WHERE idTipoConteudo = 14 and inPublicar = 1 and NOW() between dtDataConteudo and dtDataExpiracao and ordem = 3 order by ordem LIMIT 1";
                $qryPublicidade = $db->query($strPublicidade);
                    foreach ($qryPublicidade as $item) {
            ?>
                <a title="<?php echo $item["nmTituloConteudo"]; ?>" target="_blank" href="<?php echo $item["nmLinkExterno"]; ?>">
                    <img alt="<?php echo $item["nmTituloConteudo"]; ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $item["nmLinkImagem"]; ?>&w=480&h=480" height="135px;" width="240px;" />
                </a>
            <?php } ?>
        </div>
    </div>
</aside>
