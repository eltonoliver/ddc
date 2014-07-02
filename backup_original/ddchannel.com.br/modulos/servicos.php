<h2 class="title-section">
    <span class="title-section__name">
        Serviços
    </span>

    <a class="title-section__more" href="javascript:history.back(-1);">
        Voltar
    </a>
</h2>

<div class="post-meta">
    <?php include($raiz . 'modulos/addThis.php'); ?>
</div>

<?php if (!isset($id) || !$id) { ?>

  <article class="services">
        <form class="fast-search">
            <label for="fs_term">O que você procura?</label>

            <input id="fs_term" name="fs_term" placeholder="ex. banco, segunda via, tv a cabo" type="search" value="" />

            <input class="btn" type="submit" value="Procurar" />
        </form>
        <div class="services-area">
            <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 10 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h3 class="services-title"> <?php echo $qryPagina[0]['nmCategoria']; ?> </h3>
               <ul class="services-list-logos">
				<?php
                    foreach ($qryPagina as $conteudo) {
                        if (empty($conteudo["nmLinkExterno"])) {
                            $conteudo["nmLinkExterno"] = 'servicos';
                        }else{
                    $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
                    }        
                ?>        
                   <li>
                      <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <img alt="<?php echo $conteudo['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $conteudo['nmLinkImagem']; ?>&w=88&h=88" />
                     </a>
                   </li>
                  <?php } ?>
               </ul>
        </div>
        <div class="services-area">
             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 11 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h3 class="services-title"> <?php echo $qryPagina[0]['nmCategoria']; ?> </h3>
            <ul class="services-list-logos">
        <?php
            
            $meses = count($qryPagina);
            foreach ($qryPagina as $conteudo) {
                if (empty($conteudo["nmLinkExterno"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }else{
                    $nmLinkPaginaConteudo = "teste";
                    }    
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>        
                   <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <img alt="<?php echo $conteudo['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $conteudo['nmLinkImagem']; ?>&w=88&h=88" />
                     </a>
                   </li>
                  <?php } ?>
               </ul>
        
                <!--< ?php } ?> -->
        </div>
        <div class="services-area">
            <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 12 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h3 class="services-title"> <?php echo $qryPagina[0]['nmCategoria']; ?> </h3>
            <ul class="services-list-logos">
        <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>        
                   <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <img alt="<?php echo $conteudo['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $conteudo['nmLinkImagem']; ?>&w=88&h=88" />
                     </a>
                   </li>
                  <?php } ?>
               </ul>
        
                <!--< ?php } ?> -->
        </div>
        
        <div class="services-area fl fl-half">
             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 13 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h3 class="services-title"> <?php echo $qryPagina[0]['nmCategoria']; ?> </h3>
            <ul class="services-list-logos">
         <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
          ?>             
                <li>
                   <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <img alt="<?php echo $conteudo['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $conteudo['nmLinkImagem']; ?>&w=88&h=88" />
                     </a>
                </li>
  
                <?php } ?>
            </ul>
        </div>
        <div class="services-area fr fr-half">
             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 14 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h3 class="services-title"> <?php echo $qryPagina[0]['nmCategoria']; ?> </h3>
            <ul class="services-list-logos">
         <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
          ?>   
            
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <img alt="<?php echo $conteudo['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $conteudo['nmLinkImagem']; ?>&w=88&h=88" />
                     </a>
                </li>
  
                <?php } ?>
            </ul>
        </div>
        <div class="services-area">
             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 15 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h3 class="services-title"> <?php echo $qryPagina[0]['nmCategoria']; ?> </h3>
            <ul class="services-list-logos">
        <?php
           
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>        
                   <li>
                   <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <img alt="<?php echo $conteudo['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $conteudo['nmLinkImagem']; ?>&w=88&h=88" />
                     </a>
                   </li>
                  <?php } ?>
               </ul>
        </div>
        <div class="services-area">
             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 16 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h3 class="services-title"> <?php echo $qryPagina[0]['nmCategoria']; ?> </h3>
            <ul class="services-list-logos">
        <?php
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>        
                   <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <img alt="<?php echo $conteudo['nmTituloConteudo'] ?>" src="timthumb.php?src=<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $conteudo['nmLinkImagem']; ?>&w=88&h=88" />
                     </a>
                   </li>
                  <?php } ?>
               </ul>
        </div>
        
        <div class="services-area s3">
            <h3 class="services-title">Governo Federal</h3>
            <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 20 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
          
            <ul class="services-list-text">
            <?php
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>

             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 21 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
              
              <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 34 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>


            <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 22 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php

            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
              
               <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 35 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
               <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 36 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
               <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 37 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
        </div>

        <div class="services-area s3">
            <h3 class="services-title">Governo do Amazonas</h3>

             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 23 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>

          <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 24 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php

            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>

              <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 25 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
               <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 38 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo stripslashes($qryPagina[0]['nmCategoria']); ?> </h5>
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo stripslashes($conteudo["nmTituloConteudo"]); ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
              
               <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 39 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h3 class="services-title"> <?php echo stripslashes($qryPagina[0]['nmCategoria']); ?> </h3>
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo stripslashes($conteudo["nmTituloConteudo"]); ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
               <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 40 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h3 class="services-title"> <?php echo $qryPagina[0]['nmCategoria']; ?> </h3>
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
              
            <h3 class="services-title">Instituições do Judiciário</h3>

             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 41 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
             <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5> 
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
              <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 42 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
             <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5> 
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
              <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 43 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
             <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5> 
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
        </div>

        <div class="services-area s3">
            <h3 class="services-title">Certidões Negativas</h3>

             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 26 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
             <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5> 
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>

            <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 27 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
              <h5> <?php echo $qryPagina[0]['nmCategoria']; ?> </h5>
            <ul class="services-list-text">
            <?php
            
             foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
            ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
            <h3 class="services-title">Estudantes</h3>

             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 44 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
             
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>" href="<?php echo $conteudo["nmLinkExterno"]; ?>" target="_blank">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
            
               <h3 class="services-title">Telefone Utéis</h3>

             <?php
              $idTipo = 24;
				$strPagina = "SELECT DISTINCT b.nmCategoria, a.* FROM tb_conteudo a INNER JOIN tb_categoria b ON b.idCategoria = a.idCategoria WHERE a.idTipoConteudo = ". $idTipo ." AND a.idCategoria = 47 AND a.inPublicar = 1 ORDER BY a.ordem ASC";
				$qryPagina = $db->query($strPagina);
			?>
            
            <ul class="services-list-text">
            <?php
            
            foreach ($qryPagina as $i => $conteudo) {
                if (vazio($conteudo["nmPaginaConteudo"])) {
                    $conteudo["nmPaginaConteudo"] = 'servicos';
                }
                $nmLinkPaginaConteudo = $url_raiz . $conteudo["nmPaginaConteudo"] . '/id/' . $conteudo["idConteudo"];
        ?>
                <li>
                    <a title="<?php echo $conteudo['nmTituloConteudo']; ?>">
                        <?php echo $conteudo["nmTituloConteudo"]; ?>
                     </a>
                </li>
         <?php } ?>
            </ul>
        </div>
  </article>

<?php
    } else {
        $strPagina = "SELECT idTipoConteudo,nmConteudo,nmLinkImagem,nmTituloConteudo FROM vwconteudo WHERE idConteudo = " . $db->clean($id) . " AND inPublicar = 1 LIMIT 1";
        $qryPagina = $db->query($strPagina);
        if (!$qryPagina) {
            redirecionar($url_raiz . '404');
        }
        $idTipo = $qryPagina[0]["idTipoConteudo"];
?>

    <article class="post-text">
        <h3 class="title-post"><?php echo $qryPagina[0]["nmTituloConteudo"]; ?></h3>

        <?php if (is_file($raiz . "arquivos/enviados/image/" . $qryPagina[0]["nmLinkImagem"])) { ?>
            <img class="post-text-img" alt="<?php echo $qryPagina[0]["nmTituloConteudo"]; ?>" src="<?php echo $url_raiz . 'arquivos/enviados/image/' . $qryPagina[0]['nmLinkImagem']; ?>" />
        <?php } ?>

        <?php echo stripslashes($qryPagina[0]["nmConteudo"]); ?>
    </article>

<?php } ?>
