<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Sistema de Gerenciamento de Conteúdo:<?php echo $geralConfig[0]["nmTituloSite"]; ?></title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="shortcut icon" href="img/favicon.ico" /> 
        <!-- METAS -->
        <meta name="copyright" content="<?php echo $geralConfig[0]["nmEmpresa"]; ?>"/>
        <meta name="author" content="<?php echo $geralConfig[0]["nmMetaRegistro"]; ?>"/>
        <meta name="keywords" content="<?php echo $geralConfig[0]["nmMetaKeywords"]; ?>"/> 
        <meta name="description" content="<?php echo $geralConfig[0]["nmMetaDescricao"]; ?>"/>

        <!-- CSS GERAL -->
        <link href="<?php echo $url_raiz; ?>admin/css/cssadmin.css" rel="stylesheet" type="text/css" />

        <!-- Jquery -->
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/jquery-1.4.2.js"></script>
        <!-- Plugin request from -->
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/jquery.form.js"></script>
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/ajaxRequest.js"></script>

        <!-- plugin da rodinha do mouse -->
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/jquery.mousewheel.js"></script>
        <!-- plugin de mascaras -->
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/jquery.mascaras.js"></script>

        <!-- Javascripts -->
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/jquery.jqcrypt/scripts/jquery.jqcrypt.pack.js"></script>
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/global.js"></script>
        <!--<script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/encrypt.js"></script>-->

        <!-- jdpicker -->
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/jdpicker_1.0.3/jquery.jdpicker.js"></script>
        <link href="<?php echo $url_raiz; ?>admin/js/jdpicker_1.0.3/jdpicker.css" rel="stylesheet" type="text/css" />

<!-- <script type="text/javascript" src="jquery.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="<?php echo $url_raiz; ?>admin/js/ddsmoothmenu/ddsmoothmenu.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $url_raiz; ?>admin/js/ddsmoothmenu/ddsmoothmenu-v.css" />
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/ddsmoothmenu/ddsmoothmenu.js"></script>

        <script type="text/javascript">

            ddsmoothmenu.init({
                mainmenuid: "smoothmenu1", //menu DIV id
                orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
                classname: 'ddsmoothmenu', //class added to menu's outer DIV
                //customtheme: ["#1c5a80", "#18374a"],
                contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
            })
        </script>

        <!-- Modal Message -->
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/modalMessage/ajax.js"></script>
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/modalMessage/modal-message.js"></script>
        <script type="text/javascript" src="<?php echo $url_raiz; ?>admin/js/modalMessage/ajax-dynamic-content.js"></script>
        <link href="<?php echo $url_raiz; ?>admin/js/modalMessage/modal-message.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript">

            messageObj = new DHTML_modalMessage();	// We only create one object of this class
            messageObj.setShadowOffset(5);	// Large shadow

            function displayMessage(url, altura, largura)
            {
                messageObj.setSource(url);
                messageObj.setCssClassMessageBox(false);
                messageObj.setSize(largura, altura);
                messageObj.setShadowDivVisible(true);	// Enable shadow for these boxes
                messageObj.display();
            }

            function closeMessage()
            {
                messageObj.close();
            }

            function chamaPopArquivo(obj) {
                displayMessage('teste2.php?chamada=' + obj, 500, 700);
            }
            function chamaPopPagina(obj) {
                displayMessage('teste2.php?chamada=' + obj, 500, 700);
            }

            function chamaPop(obj) {
                displayMessage('teste2.php?chamada=' + obj, 500, 700);
            }

            function adicionarTag(obj, tipo) {
                //pode ser usada para adicionar tag a um arquivo ou a um conteudo
                displayMessage('<?php echo $url_raiz; ?>admin/adicionar-tag?ajax=1&idItem=' + obj + '&tipo=' + tipo, 200, 300);
            }
        </script>

        <link rel="stylesheet" href="<?php echo $url_raiz; ?>admin/js/multiUpload/jquery-ui.css" id="theme"/>
        <link rel="stylesheet" href="<?php echo $url_raiz; ?>admin/js/multiUpload/jquery.fileupload-ui.css"/>

        <script type="text/javascript">
            var url_raiz = '<?php echo $url_raiz ?>';
        </script>
        <script  src="<?php echo $url_raiz; ?>js/jquery.complexify.js"></script>
    </head>