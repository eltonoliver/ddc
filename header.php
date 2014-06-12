<?php
//Inicia um sessão
//session_start();

$arquivoModulo = substr($url_redirecionar->obter_file_php(), 0, -4);
if ($arquivoModulo == 'radio')
    $cssBody = "class='rddc'";
else
    $cssBody = null;

//if (!isset($_SESSION["ID_SESSAO"])) {
 //   $_SESSION["ID_SESSAO"] = session_id();
//}
?>

<!doctype html>
<!--[if lte IE 8]><html class="ie8 <?php echo $cssBody; ?>" lang="pt-br"><![endif]-->
<!--[if !IE]> --> <html lang="pt-br" <?php echo $cssBody; ?>>     <!-- <![endif]-->
    <head>
        <!--
            UGAGOGO INVENCIONICES TECNOLÓGICAS
            FALE COM A GENTE:
            contato@ugagogo.com.br
            (92) 3302-4609
            
                                         .:             :                                   
                                          :.           .:                                   
                                :          :           :            :                       
                                :          :           :             .                      
                                :.         :           .,            :                      
                                .,         :             :.      ..::.                      
                     ,.          :           ::.          :.    :                           
                      :.           ::         .:      .. ,:.  :.                            
                      :              :,       .:    :.         ,               :.           
                      .:            .,:      :.     :          :              ,:            
                        :           :       :        :.        :              :             
                         ,::,:.    ,       ,: . .MMMMMMMMMM= . :      .:.    :,     .::     
                              :.   :     ,MMN::::::::::::::::::~MM    :   :::       :       
             .:               :.     :MM::::::::::::::::::::::::::::M~,             :       
              :                :  .MM:::::::::::::::::::::::NM~IM::::::M    .   .  :        
                :::             :M::::::::::~..,::::::::::.. ::.  .,:::::?..,:              
                    ,         ,M::::::::.. ...:,.:::::::::M.O.,.::,: .M::::O.         .:.   
                   :.        M::::::::M.,:::..:::::::::::::::=~====:.: ~:::::M.     :       
                    :::::: ~M:+::::::.::N.==~M~.M~::::::::~M.   ..I~=M:M::::::M .::         
                          .M:=:::::?8:::==:.      .M:::::M.       .M==:M::::::::,           
                          M~=~::::::..M~==         .M:::~.         .==::::::::::~:  MMM.    
                         M:==::::::::D:==M.         ,:::M    =      M=::::::::::::M::::~.   
                ..M::~M..M:+:::::::::::==M    MMM.  .M::M  M8MMM   .N=::::::::::::M:O:::,.  
                .~:====,M:~=:::::::::::==M. .MMMM ...?::M. MMMM~   M==:::::::::::::~::D~~.  
                O:=:~::~M:==::::::::::::==?. MZMMD..MMM?MM  MMM   M==::::::::::::::M=::8:.  
                D:=:~:M:::==::::::::::::~=~O.  M~..M==::::$D.,,,.N=::::::::::::::::M~M:::.  
                M:=M::M~~?==::::::::::::::==~=......,=M===$......,  . MO::::::::::::8~::O   
                .M::::M:7::=::::::::::::::::MM.......MMMMM.......~.     M~::::::::::::=.    
                  M:::~~:::==:::::::::::M,.,,.?......      .Z..      .. .M:::::::::M        
                   M::::::::=~:::::::::$,,,.                          ,. M:::::::::I        
                    .M7:M.M::=::::::::?.,,,.                   ..,.,,.NM 8::::::::M.        
                           M::=:::::::M., .         .     ,,...=M7~MM:::MM:::::::I.         
                            .~:=::::::M,,,.MMMM.  ..M....M:~NM::::::::::::::::::?.          
                              M:=~:::::,.M~:::::MM.=:::~~::::::::::::::::::::::M.           
                               .M:=:::::M::::::::::::::::::::::::::::::::::::~M             
                                  M:~::::::::::::::::=:::::::::::::::::::::M8               
                                    M::::::::::::::::::==:::::::::::::::,M.                 
                                     .MM~::::::::::::::::::::::::::::MM                     
                                         .MM=:::::::::::::::::::8M..                        
                                               .MMMMMM8MMM?                            
        -->
        <!-- BASE URL -->
        <base href="<?php echo $url_raiz; ?>">

        <!-- META -->
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="content-language" content="pt-br">
        <?php
        if ($url_redirecionar->obter_file_php() == "posts.php" || $url_redirecionar->obter_file_php() == "pagina.php") {
            $qryMeta = $db->query("SELECT nmLinkImagem,nmTituloConteudo,nmConteudo FROM tb_conteudo WHERE nmTituloAmigavel = " . $db->clean($titulo));
            ?>
            <title><?php echo $geralConfig[0]["nmTituloSite"] . ' - ' . $qryMeta[0]["nmTituloConteudo"]; ?></title>
            <meta name="description" content="<?php echo strip_tags(resume($qryMeta[0]["nmConteudo"], 200)); ?>"/>
        <?php } else { ?>
            <title><?php echo $geralConfig[0]["nmTituloSite"]; ?></title>
            <meta name="description" content="<?php echo $geralConfig[0]["nmMetaDescricao"]; ?>"/>
        <?php } ?>
        <meta name="keywords" content="<?php echo $geralConfig[0]["nmMetaKeywords"]; ?>" />
        <meta name="robots" content="index, follow" />
        <meta name="author" content="<?php echo $geralConfig[0]["nmMetaAuthor"]; ?>"/>

        <?php if ($qryMeta) { ?>
            <!-- FACEBOOK -->

            <meta property="og:title" content="<?php echo $geralConfig[0]["nmTituloSite"] . ' - ' . $qryMeta[0]["nmTituloConteudo"]; ?>" />
            <meta property="og:type" content="website">
            <meta property="og:url" content="<?php echo $urlAtualCompleta; ?>">
            <meta property="og:image" content="<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qryMeta[0]["nmLinkImagem"]; ?>">
            <meta property="og:site_name" content="<?php echo $geralConfig[0]["nmTituloSite"]; ?>">
            <meta property="og:description" content="<?php echo strip_tags(resume($qryMeta[0]["nmConteudo"], 200)); ?>" />

            <?php /* ?>
              <!--Twitter-->
              <meta name="twitter:card"        content="summary_large_image">
              <meta name="twitter:site"        content="@leleuu_">
              <meta name="twitter:creator"     content="@leleuu_">
              <meta name="twitter:title"       content="<?php echo $geralConfig[0]["nmTituloSite"] . ' - ' . $qryMeta[0]["nmTituloConteudo"]; ?>">
              <meta name="twitter:description" content="<?php echo strip_tags(resume($qryMeta[0]["nmConteudo"], 200)); ?>">
              <meta name="twitter:image:src"   content="<?php echo $url_raiz; ?>arquivos/enviados/image/<?php echo $qryMeta[0]["nmLinkImagem"]; ?>">
              <?php */ ?>
        <?php } else { ?>
            <meta property="og:title"        content="<?php echo $geralConfig[0]["nmTituloSite"]; ?>">
            <meta property="og:description"  content="<?php echo $geralConfig[0]["nmMetaDescricao"]; ?>">
            <meta property="og:url"          content="<?php echo $urlAtualCompleta; ?>">
            <meta property="og:image"        content="<?php echo $url_raiz; ?>img/blank-315.jpg">
            <meta property="og:type"         content="blog">
            <meta property="og:site_name"    content="<?php echo $geralConfig[0]["nmTituloSite"]; ?>">
        <?php } ?>

        <?php /* ?>
          <!-- FAVICON -->
          <link rel="apple-touch-icon" href="img.png">
          <link rel="icon" href="img.png">
          <!--[if IE]>
          <link rel="shortcut icon" href="img.ico">
          <![endif]-->
          <meta name="msapplication-TileColor" content="#ffffff">
          <meta name="msapplication-TileImage" content="img.png">
          <?php */ ?>

        <link rel="shortcut icon" href="img/favicon.ico">
        <?php
        if ($arquivoModulo != 'flip') {
            ?>
            <!-- CSS -->
            <link rel="stylesheet" href="css/style.css" />
            <link rel="stylesheet" href="css/trabalhecon.css" />
            <link rel="stylesheet" href="css/storecss.css" />
            <link rel="stylesheet" href="css/mudancamenu.css" />
            <link rel="stylesheet" href="css/nprogress.css" />

            <link rel="stylesheet" type="text/css" href="http://localhost/ddc/css/style_slide.css" />

  
            <?php /* <link rel="stylesheet" href="css/radio.css" /> */ ?>
            <?php }
        ?>
        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <script type="text/javascript">
            var url_raiz = '<?php echo $url_raiz ?>';
        </script>        
    </head>