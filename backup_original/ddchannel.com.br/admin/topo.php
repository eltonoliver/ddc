</table>
<?php if (isset($_SESSION['ID']) && $_SESSION['PERM'] == 1) { ?>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="60%" height="133" align="left" class="tbTopoMax"><img width="300px" src="<?php echo $url_raiz . 'img/' . $geralConfig[0]["nmLinkLogoTopo"]; ?>"></td>
            <td width="40%" height="133" align="right" class="tbTopoMax">
                <?php if (isset($_SESSION['ID']) && $_SESSION['PERM'] == 1) { ?>
                    <h3><?php echo $geralConfig[0]["nmTituloSite"]; ?><br/>Sistema de Gerenciamento de Conteúdo</h3><?php } ?>
            </td>
        </tr>
    </table>
<?php } else { ?>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td height="133" align="center" class="tbTopoMax"><img width="300px" src="<?php echo $url_raiz . 'img/' . $geralConfig[0]["nmLinkLogoTopo"]; ?>"></td>
        </tr>
    </table>
<?php } ?>


<?php if (isset($_SESSION['ID']) && $_SESSION['PERM'] == 1 && $_SESSION['CLID'] == $_SERVER['SCRIPT_NAME']) { ?>
    <table width="100%" class="tabelaMenu">
        <tr>
            <td width="1%"><a href="admin"><img src="<?php echo $url_raiz; ?>admin/img/home.png" height="20" width="20" align="absbottom" border="0"/></a></td>
            <td width="67%" align="left">
                <div id="smoothmenu1" class="ddsmoothmenu">
                    <?php
                    //getArvoreMenus(0,1);
                    //new dBUg($_SESSION["MENUS"]);
                    //montaMenusPefil($_SESSION["MENUS"]);
                    echo $_SESSION["MENUS"];
                    ?>
                    <br style="clear: left" />
                </div>
            </td>
            <td width="30%" align="right">Usuário: <?php echo $_SESSION["NOME"]; ?> | 
                <a href="<?php echo $url_raiz; ?>admin/controller/act-login?acao=deslogando" title="Sair">
                    <img src="<?php echo $url_raiz; ?>admin/img/del.png" border="0" align="absbottom" width="20" height="20" alt="Sair"/></a></td>
        </tr>
    </table>

    <?php /* ?>
      <table width="100%"cellpadding="0" cellspacing="0" class="tabelaMenu">
      <tr>
      <td height="46" width="70%" align="left">

      <a href="admin.php"><img src="<?php echo $url_raiz; ?>admin/img/home.png" height="20" width="20" align="absbottom" border="0"/></a>&nbsp;|
      <a href="dadosGeral.php" <?php if(selecionaPagina('dadosGeral.php')){?> class="selecionado" <?php } ?>>Geral</a>&nbsp;|&nbsp;
      <a href="dadosEmpresa.php" <?php if(selecionaPagina('dadosEmpresa.php')){?> class="selecionado" <?php } ?>>P�ginas</a>&nbsp;|&nbsp;
      <a href="dadosEndereco.php" <?php if(selecionaPagina('dadosEndereco.php')){?> class="selecionado" <?php } ?>>Endere�o e Contato</a>&nbsp;|&nbsp;
      <?php if($geralConfig[0]["inRedesSociais"] == 1){ ?>
      <a href="dadosRedes.php" <?php if(selecionaPagina('dadosRedes.php')){?> class="selecionado" <?php } ?>>Redes Sociais</a>&nbsp;|&nbsp;
      <?php } ?>
      <a href="menuCategorias.php" <?php if(selecionaPagina('menuCategorias.php') || selecionaPagina('cadCategoria.php')){?> class="selecionado" <?php } ?>>Categorias</a>&nbsp;|&nbsp;
      <a href="menuConteudo.php" <?php if(selecionaPagina('menuConteudo.php') || selecionaPagina('cadConteudo.php')){?> class="selecionado" <?php } ?>>Conte�dos</a>&nbsp;|&nbsp;

      <a href="menuClientes.php" <?php if(selecionaPagina('menuClientes.php') || selecionaPagina('cadCliente.php')){?> class="selecionado" <?php } ?>>Coment�rios</a>&nbsp;|&nbsp;
      <a href="menuCandidatos.php" <?php if(selecionaPagina('menuCandidatos.php') || selecionaPagina('cadCandidato.php')){?> class="selecionado" <?php } ?>>Candidatos</a>&nbsp;|&nbsp;
      <a href="menuPerfis.php" <?php if(selecionaPagina('menuPerfis.php') || selecionaPagina('cadPerfil.php')){?> class="selecionado" <?php } ?>>Perfis</a>&nbsp;|&nbsp;
      <a href="menuMenus.php" <?php if(selecionaPagina('menuMenus.php') || selecionaPagina('cadMenu.php')){?> class="selecionado" <?php } ?>>Menus</a>&nbsp;|&nbsp;
      <a href="menuUsuarios.php" <?php if(selecionaPagina('menuUsuarios.php') || selecionaPagina('cadUsuario.php')){?> class="selecionado" <?php } ?>>Usu�rios</a>&nbsp;|&nbsp;

      </td>
      <td height="46" width="30%" align="right">
      Usu�rio: <?php echo $_SESSION["NOME"]; ?> | <a href="deslogando.php" title="Sair"><img src="<?php echo $url_raiz; ?>/admin/img/del.png" border="0" align="absbottom" width="20" height="20" alt="Sair"/></a>
      </td>
      </tr>
      </table>
      <?php */ ?>

<?php } else { ?>
    <table width="100%"cellpadding="0" cellspacing="0" class="tabelaMenu">
        <tr> 
            <td height="46" align="center" colspan="2"><h3><?php echo $geralConfig[0]["nmTituloSite"]; ?><br/>Sistema de Gerenciamento de Conteúdo</h3></td>
        </tr>
    </table>
<?php } ?>
<table width="100%"cellpadding="0" cellspacing="0" class="tbCorpo">


