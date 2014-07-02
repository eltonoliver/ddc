<?php
    if ($_SERVER["SERVER_NAME"] == '192.168.0.5' || $_SERVER["SERVER_NAME"] == 'ugagogo-web') {
        $mysql_address = 'ugagogo-web';
        $mysql_username = 'admin';
        $mysql_password = 'vertrigo';
        $mysql_database = '2014_ddc';
    } else if ($_SERVER['SERVER_NAME'] == 'localhost' ||
            strstr($_SERVER['SERVER_NAME'], 'programador') ||
            strstr($_SERVER['SERVER_NAME'], 'designer') ||
            strstr($_SERVER['REMOTE_ADDR'], '192.168')
    ) {
        $mysql_address = 'ugagogo-web';
        $mysql_username = 'admin';
        $mysql_password = 'vertrigo';
        $mysql_database = '2014_ddc';
    } elseif ($_SERVER['SERVER_NAME'] == 'clientesugagogo.com.br') {
        //Online
        $mysql_address = 'localhost';
        $mysql_username = 'clientes_ddc2014';
        $mysql_password = 'XUW@4w!9TWAA';
        $mysql_database = 'clientes_ddc';
    } else {
        //Online
        $mysql_address = 'localhost';
        $mysql_username = 'informed_ddcUser';
        $mysql_password = 'o4ool_gyJ)aU';
        $mysql_database = 'informed_ddc';
    }
?>